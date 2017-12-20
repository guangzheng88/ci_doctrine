<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 控制器基类，方便做统一管理
 * 加载REST_Controller
 */
require_once(APPPATH.'libraries/REST_Controller.php');
class MY_Controller extends REST_Controller {

	public function __construct()
	{
        parent::__construct();
        //解决ajax跨域问题
        echo header("Access-Control-Allow-Origin:*");
	}
}
