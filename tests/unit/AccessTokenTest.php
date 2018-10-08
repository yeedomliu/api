<?php
/**
 * Created by PhpStorm.
 * User: yeedomliu
 * Date: 2018/10/9
 * Time: 5:29 AM
 */

use yeedomliu\api\example\AccessToken;

class AccessTokenTest extends \PHPUnit\Framework\TestCase
{

    public function testNormal() {
        $actual = (new AccessToken())->start();
        $this->assertTrue(is_array($actual));

        unset($actual['access_token']);
        $excepted = [
            'errcode'    => 0,
            'errmsg'     => 'ok',
            'expires_in' => 7200,
        ];
        $this->assertEquals($excepted, $actual);
    }

}
