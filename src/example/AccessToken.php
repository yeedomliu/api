<?php

namespace yeedomliu\api\example;

use yeedomliu\api\Base;
use yeedomliu\api\outputformat\Json;

class AccessToken extends Base
{

    public function url() {
        return "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=wwe8e9b507c1253d29&corpsecret=H09CQQBdZg1g5tn20N0VDV7leOR6YuvPxsp0VjrIapA";
    }

}