<?php

namespace yeedomliu\api\helper;

/**
 * Class Inflector
 *
 * @package yeedomliu\api\helper
 */
class Inflector
{

    static public function camelize($word) {
        return str_replace(' ', '', ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word)));
    }

    static public function underscore($word) {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $word));
    }

}