<?php
/**
 * Created by PhpStorm.
 * User: yeedomliu
 * Date: 2018/10/8
 * Time: 12:57 PM
 */

namespace yeedomliu\api\example;


use yeedomliu\api\Base;

class WorkWechat extends Base
{

    public function url() {
        return 'https://cvm.tencentcloudapi.com/?Action=DescribeInstances';
    }

}