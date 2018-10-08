<?php

namespace yeedomliu\api\outputformat;

/**
 * json格式化类
 *
 * @package yeedomliu\api\outputformat
 */
class Json extends Base
{

    public function handle($data) {
        try {
            $return = json_decode($data, true);
        } catch (\Exception $e) {
            throw $e;
        }

        return $return;
    }


}