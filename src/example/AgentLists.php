<?php

namespace yeedomliu\api\example;

use yeedomliu\api\Base;

class AgentLists extends Base
{

    public function url() {
        return "https://qyapi.weixin.qq.com/cgi-bin/agent/list?access_token=" . (new AccessToken())->start()['access_token'];
    }
}