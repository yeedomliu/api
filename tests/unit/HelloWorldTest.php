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
        echo '<pre>';
        print_r((new HelloWorld())->start());
        echo '</pre>';
        exit();
    }
}
