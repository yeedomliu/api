<?php

namespace yeedomliu\api\proxy;

use wii\base\Component;
use yeedomliu\api\requestfields\ExcludeFields;
use yeedomliu\api\requestfields\Fields;
use yeedomliu\api\requestfields\Headers;
use yeedomliu\api\requestfields\HttpBuildQuery;
use yeedomliu\api\requestfields\JsonEncodeFields;
use yeedomliu\api\requestfields\Method;
use yeedomliu\api\requestfields\CurlOptions;
use yeedomliu\api\requestfields\Prefix;
use yeedomliu\api\requestfields\Raw;
use yeedomliu\api\requestfields\OutputFormatObj;
use yeedomliu\api\requestfields\Url;

abstract class Base extends Component
{

    use Prefix, Url, Fields, Method, JsonEncodeFields, Headers, ExcludeFields, CurlOptions, HttpBuildQuery, OutputFormatObj;

    abstract public function getContent();

}