<?php
/**
 * Created by PhpStorm.
 * User: xiabin
 * Date: 16/6/1
 * Time: 下午8:29
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Login
 */
class Login extends BaseController
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->template_data['title'] = 'login';
    }

    /**
     * default
     */
    public function index()
    {
        $this->load->view('login',$this->template_data);

    }

}