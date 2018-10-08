<?php

namespace yeedomliu\api\outputformat;

/**
 * 原始格式类
 *
 * @package yeedomliu\api\outputformat
 */
class Raw extends Base
{

    public function handle($data) {
        return $data;
    }

}