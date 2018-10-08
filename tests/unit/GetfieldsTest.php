<?php

use yeedomliu\api\example\HelloWorld;

class GetfieldsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * 只有getFields情况
     */
    public function testOnlygetfields() {
        $getFields = ['a' => 1, 'b' => 2];
        $actual = (new HelloWorld())->setGetFields($getFields);
        $excepted = "{$actual->url()}&" . http_build_query($getFields);
        $this->assertEquals($excepted, $actual->getUri());
    }

    /**
     * 只有defaultGetFields
     */
    public function testOnlydefaultgetfields() {
        $defaultGetFields = ['default' => 1, 'a' => 1];
        $actual = (new HelloWorld())->setDefaultGetFields($defaultGetFields);
        $excepted = "{$actual->url()}&" . http_build_query($defaultGetFields);
        $this->assertEquals($excepted, $actual->getUri());
    }

    /**
     * 两者混合情况
     */
    public function testMixed() {
        $getFields = ['a' => 1, 'b' => 2];
        $defaultGetFields = ['default' => 1, 'a' => 1];
        $actual = (new HelloWorld())->setGetFields($getFields)->setDefaultGetFields($defaultGetFields);

        $fields = array_merge($defaultGetFields, $getFields); // getFields优先defaultGetFields
        $excepted = "{$actual->url()}&" . http_build_query($fields);
        $this->assertEquals($excepted, $actual->getUri());
    }


}
