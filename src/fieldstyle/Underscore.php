<?php

namespace yeedomliu\api\fieldstyle;

use yeedomliu\api\helper\Inflector;

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