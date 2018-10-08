<?php

use yeedomliu\api\example\HelloWorld;

class HelloWorldTest extends \PHPUnit\Framework\TestCase
{

    public function testHelloworld() {
        $actual = (new HelloWorld())->setOutputFormatObj(new \yeedomliu\api\outputformat\Raw())->start();
        $actual = preg_replace('/"RequestId":".+?"/', '"RequestId":""', $actual);
        $excepted = '{"Response":{"Error":{"Code":"InvalidParameter","Message":"Url key and value should be splited by `=`."},"RequestId":""}}';
        $this->assertEquals($excepted, $actual);
    }

    public function testOutputformatjson() {
        $actual = (new HelloWorld())->start();
        $this->assertTrue(is_array($actual));
        $this->assertEquals('Url key and value should be splited by `=`.', $actual['Response']['Error']['Message']);
    }

}
