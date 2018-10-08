<?php

namespace wii\plugin\api\fieldstyle;

use wii\helpers\Inflector;

/**
 * Class LcfirstCamelize
 *
 * 类似把 "send_email" 转换成 "sendEmail"
 *
 * @package wii\plugin\api\fieldstyle
 */
class LcfirstCamelize extends Base
{

    public function handle($name) {
        return lcfirst(Inflector::camelize($name));
    }


}