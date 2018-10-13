<?php

namespace yeedomliu\api\fieldstyle;

use yeedomliu\api\helper\Inflector;

/**
 * Class Camelize
 *
 * 类似把 "send_email" 转换成 "SendEmail"
 *
 * @package yeedomliu\api\fieldstyle
 */
class Camelize extends Base
{

    public function handle($name) {
        return Inflector::camelize($name);
    }


}