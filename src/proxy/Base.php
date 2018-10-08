<?php

namespace wii\plugin\api\proxy;

use wii\base\Component;
use wii\plugin\api\requestfields\ExcludeFields;
use wii\plugin\api\requestfields\Fields;
use wii\plugin\api\requestfields\Headers;
use wii\plugin\api\requestfields\HttpBuildQuery;
use wii\plugin\api\requestfields\JsonEncodeFields;
use wii\plugin\api\requestfields\Method;
use wii\plugin\api\requestfields\CurlOptions;
use wii\plugin\api\requestfields\Prefix;
use wii\plugin\api\requestfields\Raw;
use wii\plugin\api\requestfields\OutputFormatObj;
use wii\plugin\api\requestfields\Url;

abstract class Base extends Component
{

    use Prefix, Url, Fields, Method, JsonEncodeFields, Headers, ExcludeFields, CurlOptions, HttpBuildQuery, OutputFormatObj;

    abstract public function getContent();

}