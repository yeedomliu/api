<?php

namespace yeedomliu\api\fieldstyle;

use wii\helpers\Inflector;

/**
 * Class Underscore
 *
 * 把 "CamelCased" 转换成 "underscored_word"
 *
 * @package yeedomliu\api\fieldstyle
 */
class Underscore extends Base
{

    public function handle($name) {
        return Inflector::underscore($name);
    }


}