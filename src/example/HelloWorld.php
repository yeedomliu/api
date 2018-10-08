<?php

namespace yeedomliu\api\example;

use yeedomliu\api\Base;

class HelloWorld extends Base
{

    public function url() {
        return 'https://cvm.tencentcloudapi.com/?Action=DescribeInstances';
    }

}