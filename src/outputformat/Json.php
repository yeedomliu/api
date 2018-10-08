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
            $return = \wii\helpers\Json::decode($data);
        } catch (\Exception $e) {
            \Wii::error("解析json异常[{$e->getMessage()}],内容[{$data}]");
            throw $e;
        }

        return $return;
    }


}