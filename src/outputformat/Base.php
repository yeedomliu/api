<?php

namespace yeedomliu\api\outputformat;

/**
 * 返回结果处理基类
 *
 * @package yeedomliu\api\outputformat
 */
abstract class Base
{

    abstract public function handle($data);

}