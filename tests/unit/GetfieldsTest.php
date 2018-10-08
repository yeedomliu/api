<?php

use yeedomliu\api\example\HelloWorld;

class GetfieldsTest extends \PHPUnit\Framework\TestCase
{

    public function testOnlygetfields() {
        $getFields = ['a' => 1];
        $actual = (new HelloWorld())->setGetFields($getFields);
        $excepted = "{$actual->url()}&" . http_build_query($getFields);
        $this->assertEquals($excepted, $actual->getUri());
    }

    public function testDefaultgetfields() {
        $defaultGetFields = ['default' => 1];
        $actual = (new HelloWorld())->setDefaultGetFields($defaultGetFields);
        $excepted = "{$actual->url()}&" . http_build_query($defaultGetFields);
        $this->assertEquals($excepted, $actual->getUri());
    }

}
