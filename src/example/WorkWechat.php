<?php

namespace yeedomliu\api\example;

use yeedomliu\api\Base;

class WorkWechat extends Base
{

    public function url() {
        return 'https://cvm.tencentcloudapi.com/?Action=DescribeInstances';
    }

}