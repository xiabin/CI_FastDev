<?php
/**
 * Created by PhpStorm.
 * User: binxia
 * Date: 2015/6/9
 * Time: 14:16
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
$config['all_controllers'] = array(
    'allow' => 'ACL_HAS_ROLE',//表示所有拥有角色的用户
);
//遵循qeephp中的acl规则
//可参考注释代码
/*
$config['all_controllers'] = array(
    'allow' => 'ACL_HAS_ROLE',//表示所有拥有角色的用户
);

$config['home'] = array(
    'deny' => '0'
);

$config['client_manage'] = array(
    'deny' => 'visitor'
);
$config['account_manage'] = array(
    'deny' => 'visitor'
);

$config['waybill_manage'] = array(
    'deny' => 'visitor',
    'actions' => array(
        'del_waybill' => array('deny' => 'un_auth_user'),
        'update_customer' => array('deny' => 'un_auth_user'),
        'update_waybill_statues'=> array('deny' => 'un_auth_user')
    )
);

$config['recycle_manage'] = array(
    'deny' => 'visitor',
    'actions' => array(
        'revoke_del_bill' => array('deny' => 'un_auth_user')
    )
);*/

