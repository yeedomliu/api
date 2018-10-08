<?php

use yeedomliu\api\example\HelloWorld;

class ExcludefieldsTest extends \PHPUnit\Framework\TestCase
{

    public function testExcludefields() {
        $postFields = ['a' => 1, 'b' => 2];
        $defaultPostFields = ['default' => 1, 'a' => 1];
        $excludeFields = ['b'];
        $actual = (new HelloWorld())->setExcludeFields($excludeFields)->setPostFields($postFields)->setDefaultPostFields($defaultPostFields);

        $excepted = array_merge($defaultPostFields, $postFields);
        unset($excepted[ current($excludeFields) ]);
        $this->assertEquals($excepted, $actual->getHandledFields());
    }

    public function testExcludeemptyfields() {
        $postFields = ['a' => 1, 'b' => '', 'c' => [], 'd' => 0];
        $actual = (new HelloWorld())->setExcludeEmptyField(true)->setPostFields($postFields);

        $excepted = $postFields;
        unset($excepted['b'], $excepted['c']);
        $this->assertEquals($excepted, $actual->getHandledFields());
    }

}
