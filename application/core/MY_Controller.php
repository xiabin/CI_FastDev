<?php
/**
 * Created by PhpStorm.
 * User: xiabin
 * Date: 2016/3/25
 * Time: 15:26
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Web_Controller
 */
class BaseController extends CI_Controller
{
    /**
     * @var
     * 模板解析数据
     */
    protected $template_data;

    /**
     * Web_Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template_data['static_url'] = base_url() . "static";
        $this->template_data['title'] = "CI scaffolding";
        // 开发模式下开启性能分析
        if (ENVIRONMENT === 'development' and !$this->input->is_ajax_request()) {
            $this->output->enable_profiler(true);
        }
    }
}


/**
 * Class Base_Controller
 */
class WebController extends BaseController
{


    /**
     * MY_Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //todo get role id
        $role_id = 0;
        $acl = $this->_acl($role_id);
        if ($acl === false) {
            redirect(base_url() . 'login/index');
        } else {
            if (strtolower($this->router->class) === 'login') {
                redirect(base_url() . 'welcome/index');
            }
        }

        $this->template_data['user_info'] = ['username' => "test", 'role_id' => 1];

    }

    /**
     * @param $role_id
     * @return mixed
     * 判断用是否有访问权限
     */
    private function _acl($role_id)
    {
        $this->load->library('Acl');
        //执行权限判断
        return $this->acl->checkAcl($role_id);
    }
}