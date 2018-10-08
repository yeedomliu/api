<?php

namespace wii\plugin\api\outputformat;

/**
 * 原始格式类
 *
 * @package wii\plugin\api\outputformat
 */
class Raw extends Base
{

    public function handle($data) {
        return $data;
    }

}