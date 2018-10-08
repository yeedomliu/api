<?php
/**
 * Created by PhpStorm.
 * User: yeedomliu
 * Date: 2018/10/8
 * Time: 12:59 PM
 */

use yeedomliu\api\example\HelloWorld;

class HelloWorldTest extends \PHPUnit\Framework\TestCase
{

    public function testHelloworld() {
        $actual = preg_replace('/"RequestId":".+?"/', '"RequestId":""', (new HelloWorld())->start());
        $excepted = '{"Response":{"Error":{"Code":"InvalidParameter","Message":"Url key and value should be splited by `=`."},"RequestId":""}}';
        $this->assertEquals($excepted, $actual);
    }
    
}
