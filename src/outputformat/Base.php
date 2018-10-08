<?php

namespace wii\plugin\api\outputformat;

/**
 * 返回结果处理基类
 *
 * @package wii\plugin\api\outputformat
 */
abstract class Base
{

    abstract public function handle($data);

}