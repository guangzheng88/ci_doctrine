<?php
/**
 * 用来测试phpunit是否成功可用
 * @author 任广正
 * @date 2017-12-12 17:18:54
 */
class DemoGetTest extends CI_TestCase
{
    public function testGet($params=array(),$state=1)
    {
        $res = $this->CI->restClient->get("welcome/index",array('params'=>$params));
        $error = isset($res->error) ? $res->error : '';
        $this->assertInternalType('string',$res);   //断言变量的类型
        $this->assertEquals($state,1,$error);//断言不同的参数,返回状态是否正确
    }
}