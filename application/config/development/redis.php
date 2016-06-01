<?php
/**
 * Created by PhpStorm.
 * User: xiabin
 * Date: 2016/4/12
 * Time: 16:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = 'host';
$config['password'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;