<?php

namespace yeedomliu\api\fieldstyle;

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
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $name));
    }

}