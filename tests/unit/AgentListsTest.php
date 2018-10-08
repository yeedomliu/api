<?php

use yeedomliu\api\example\AgentLists;
use PHPUnit\Framework\TestCase;

class AgentListsTest extends TestCase
{

    public function testNormal() {
        $actual = (new AgentLists())->start();
        echo '<pre>';
        print_r($actual);
        echo '</pre>';
        exit();
        \Wii::app()->end();
        $this->assertTrue(is_array($actual));

        $excepted = [];
        $this->assertEquals($excepted, $actual);

    }

}
