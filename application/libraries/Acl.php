<?php

/**
 * Created by PhpStorm.
 * User: binxia
 * Date: 2015/6/9
 * Time: 14:18
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * ACL 实现了权限检查服务
 *
 * “基于角色”通过比对拥有的角色和访问需要的角色来决定是否通过权限检查。
 *
 * 在进行权限检查时，要求分别提供角色组和访问控制列表（ACL）。
 * 然后由 QACL 比对角色组和 ACL，并返回检查结果。
 *
 * rolesBasedCheck() 用于比对权限，并返回结果。
 * role_normalize() 方法用于将 roles 角色配置 转换为符合规范的 数组形式。
 * acl_normalize() 方法用于将 ACL 转换为符合规范的 ACL。
 */
class Acl
{
    /**
     * 预定义角色常量
     */
    const ACL_EVERYONE = 'acl_everyone';
    const ACL_NULL = 'acl_null';
    const ACL_NO_ROLE = 'acl_no_role';
    const ACL_HAS_ROLE = 'acl_has_role';
    const ALL_CONTROLLERS = 'all_controllers';
    const ALL_ACTIONS = 'all_actions';

    /**
     * @var array
     * url字段
     */
    var $uri = array('space' => '', 'controller' => '', 'action' => '');
    /**
     * @var
     * acl规则
     */
    var $acl;
    /**
     * @var
     * 控制器规则
     */
    var $acl_controller;
    /**
     * @var
     * action 规则
     */
    var $acl_action;
    /**
     * @var
     * 角色信息
     */
    var $roles;

    /**
     * 构造函数
     */
    function __construct()
    {
        if (function_exists('get_instance') && class_exists('CI_Controller')) {
            $CI =& get_instance();
            $this->uri = array(
                'space' => $CI->router->directory,
                'controller' => $CI->router->class,
                'action' => $CI->router->method
            );
        }
    }

    /**
     * 对 roles 进行权限验证
     * @param array $roles
     * @param array $acl_uri space空间命名(CI中的controllers/下dir分目录)    controller控制器文件    action为控制器处理函数
     * @param array $acl 自定义权限验证配置
     * @return array
     */
    function checkAcl($roles, $uri = array(), $acl = array())
    {
        if (!empty($uri)) $this->uri = array_merge($this->uri, $uri);
        $acl_file = 'acl';
        if (isset($this->uri['space'])) {
            if (!empty($this->uri['space'])) {
                $acl_file .= rtrim('_' . $this->uri['space'],"\\/");
            }
        }
        if (empty($acl)) {
            $CI =& get_instance();
            $CI->config->load($acl_file, true);
        }
        $this->roles = $this->roles_normalize($roles);
        $this->acl = $CI->config->item($acl_file);
        $this->acl_controller = $this->_controllerACL($this->uri['controller']);
        return $this->_actionACL($this->uri['action']);
    }

    /**
     * 对 roles 整理，返回整理结果
     * @param array $roles
     * @return array
     */
    function roles_normalize($roles)
    {
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }
        return array_map('strtolower', array_filter(array_map('trim', $roles), 'strlen'));
    }

    /**
     * 对 ACL 整理，返回整理结果
     * @param array $acl 要整理的 ACL
     * @return array
     */
    function acl_normalize(array $acl)
    {
        $acl = array_change_key_case($acl, CASE_LOWER);
        $ret = array();
        $keys = array('allow', 'deny');
        foreach ($keys as $key) {
            do {
                if (!isset($acl[$key])) {
                    $values = self::ACL_NULL;
                    break;
                }
                $acl[$key] = strtolower($acl[$key]);
                if ($acl[$key] == self::ACL_EVERYONE || $acl[$key] == self::ACL_HAS_ROLE
                    || $acl[$key] == self::ACL_NO_ROLE || $acl[$key] == self::ACL_NULL
                ) {
                    $values = $acl[$key];
                    break;
                }
                $values = $this->roles_normalize($acl[$key]);
                if (empty($values)) {
                    $values = self::ACL_NULL;
                }
            } while (FALSE);
            $ret[$key] = $values;
        }
        return $ret;
    }

    /**
     * 对 controller 访问控制做处理
     * @param string $controller
     * @return array
     */
    protected function _controllerACL($controller)
    {
        if (isset($this->acl[$controller])) {
            $this->acl = array_change_key_case($this->acl, CASE_LOWER);
            return (array)$this->acl[$controller];
        }
        return isset($this->acl[self::ALL_CONTROLLERS]) ? (array)$this->acl[self::ALL_CONTROLLERS]
            : array('allow' => self::ACL_EVERYONE);
    }

    /**
     * 对 action 访问控制做处理
     * @param string $action
     * @return array
     */
    protected function _actionACL($action)
    {
        if (isset($this->acl_controller['actions'][$action])) {
            return $this->_rolesBasedCheck($this->acl_controller['actions'][$action]);
        }
        if (isset($this->acl_controller['actions'][self::ALL_ACTIONS])) {
            return $this->_rolesBasedCheck($this->acl_controller['actions'][self::ALL_ACTIONS]);
        }
        if (isset($this->acl_controller)) {
            return $this->_rolesBasedCheck($this->acl_controller);
        }
    }

    /**
     * 进行实际权限校验
     * @param string $acl
     * @return array
     */
    protected function _rolesBasedCheck($acl)
    {
        $this->acl_action = $this->acl_normalize($acl);
        if ($this->acl_action['allow'] == self::ACL_EVERYONE) {
            // 如果 allow 允许所有角色，deny 没有设置，则检查通过
            if ($this->acl_action['deny'] == self::ACL_NULL) {
                return TRUE;
            }

            // 如果 deny 为 acl_no_role，则只要用户具有角色就检查通过
            if ($this->acl_action['deny'] == self::ACL_NO_ROLE) {
                if (empty($this->roles)) {
                    return FALSE;
                }
                return TRUE;
            }

            // 如果 deny 为 acl_has_role，则只有用户没有角色信息时才检查通过
            if ($this->acl_action['deny'] == self::ACL_HAS_ROLE) {
                if (empty($this->roles)) {
                    return TRUE;
                }
                return FALSE;
            }

            // 如果 deny 也为 acl_everyone，则表示 acl 出现了冲突
            if ($this->acl_action['deny'] == self::ACL_EVERYONE) {
                return FALSE;
            }

            // 只有 deny 中没有用户的角色信息，则检查通过
            foreach ($this->roles as $role) {
                if (in_array($role, $this->acl_action['deny'])) {
                    return FALSE;
                }
            }
            return TRUE;
        }

        do {
            // 如果 allow 要求用户具有角色，但用户没有角色时直接不通过检查
            if ($this->acl_action['allow'] == self::ACL_HAS_ROLE) {
                if (!empty($this->roles)) {
                    break;
                }
                return FALSE;
            }

            // 如果 allow 要求用户没有角色，但用户有角色时直接不通过检查
            if ($this->acl_action['allow'] == self::ACL_NO_ROLE) {
                if (empty($this->roles)) {
                    break;
                }
                return FALSE;
            }

            if ($this->acl_action['allow'] != self::ACL_NULL) {
                // 如果 allow 要求用户具有特定角色，则进行检查
                $passed = FALSE;
                foreach ($this->roles as $role) {
                    if (in_array($role, $this->acl_action['allow'])) {
                        $passed = TRUE;
                        break;
                    }
                }
                if (!$passed) {
                    return FALSE;
                }
            }
        } while (FALSE);

        // 如果 deny 没有设置，则检查通过
        if ($this->acl_action['deny'] == self::ACL_NULL) {
            return TRUE;
        }

        // 如果 deny 为 acl_no_role，则只要用户具有角色就检查通过
        if ($this->acl_action['deny'] == self::ACL_NO_ROLE) {
            if (empty($this->roles)) {
                return FALSE;
            }
            return TRUE;
        }
        // 如果 deny 为 acl_has_role，则只有用户没有角色信息时才检查通过
        if ($this->acl_action['deny'] == self::ACL_HAS_ROLE) {
            if (empty($this->roles)) {
                return TRUE;
            }
            return FALSE;
        }

        // 如果 deny 为 acl_everyone，则检查失败
        if ($this->acl_action['deny'] == self::ACL_EVERYONE) {
            return FALSE;
        }

        // 只有 deny 中没有用户的角色信息，则检查通过
        foreach ($this->roles as $role) {
            if (in_array($role, $this->acl_action['deny'])) {
                return FALSE;
            }
        }
        return TRUE;
    }
}
// END Controller class

/* End of file Acl.php */
/* Location: ./application/libraries/Acl.php */