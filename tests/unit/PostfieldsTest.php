<?php

use yeedomliu\api\example\HelloWorld;

class PostfieldsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * 只有postFields情况
     */
    public function testOnlypostfields() {
        $postFields = ['a' => 1, 'b' => 2];
        $actual = (new HelloWorld())->setPostFields($postFields);
        $excepted = $postFields;
        $this->assertEquals($excepted, $actual->getHandledFields());
    }

    /**
     * 只有defaultPostFields
     */
    public function testOnlydefaultpostfields() {
        $defaultPostFields = ['default' => 1, 'a' => 1];
        $actual = (new HelloWorld())->setDefaultPostFields($defaultPostFields);
        $excepted = $defaultPostFields;
        $this->assertEquals($excepted, $actual->getHandledFields());
    }

    /**
     * @depends testOnlydefaultpostfields
     * @depends testOnlypostfields
     *
     * 两者混合情况
     */
    public function testMixed() {
        $postFields = ['a' => 1, 'b' => 2];
        $defaultPostFields = ['default' => 1, 'a' => 1];
        $actual = (new HelloWorld())->setPostFields($postFields)->setDefaultPostFields($defaultPostFields);

        $excepted = array_merge($defaultPostFields, $postFields); // postFields优先defaultPostFields
        $this->assertEquals($excepted, $actual->getHandledFields());
    }

}
