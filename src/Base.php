<?php

namespace yeedomliu\api;

use yeedomliu\api\fieldstyle\LcfirstCamelize;
use yeedomliu\api\requestfields\CacheKey;
use yeedomliu\api\requestfields\CacheTime;
use yeedomliu\api\requestfields\CurlOptions;
use yeedomliu\api\requestfields\DefaultGetFields;
use yeedomliu\api\requestfields\DefaultPostFields;
use yeedomliu\api\requestfields\ExcludeEmptyField;
use yeedomliu\api\requestfields\ExcludeFields;
use yeedomliu\api\requestfields\FullUrl;
use yeedomliu\api\requestfields\GetFields;
use yeedomliu\api\requestfields\HeaderGetterSetter;
use yeedomliu\api\requestfields\HttpBuildQuery;
use yeedomliu\api\requestfields\OutputFormatObj;
use yeedomliu\api\requestfields\PostFields;
use yeedomliu\api\requestfields\PostRequest;
use yeedomliu\api\requestfields\ProxyClass;
use yeedomliu\api\requestfields\Url;
use yeedomliu\api\requestfields\UrlPrefix;

class Base
{

    use CacheTime, CacheKey, FullUrl, UrlPrefix, ExcludeFields, ExcludeEmptyField, PostRequest, Url, GetFields, PostFields, DefaultPostFields, DefaultGetFields, HeaderGetterSetter, OutputFormatObj, CurlOptions, ProxyClass, HttpBuildQuery;

    /**
     * 初始化操作
     */
    public function init() {
    }

    /**
     * 请求url地址
     *
     * @return string
     */
    public function url() {
        return $this->getUrl() ? $this->getUrl() : '';
    }

    /**
     * 获取带参数路径
     *
     * @return string
     */
    public function getUri() {
        $url = $this->url();
        $getFieldArray = array_merge($this->getDefaultGetFields(), $this->getGetFields());
        if ($getFieldArray) {
            $keyValues = array_map(function ($name, $value) {
                return "{$name}={$value}";
            }, array_keys($getFieldArray), $getFieldArray);
            if ($keyValues) {
                $url .= (preg_match('/\?/is', $url) ? "&" : "?") . join('&', $keyValues);
            }
        }

        return $url;
    }

    /**
     * 自定义处理request对象
     *
     * @param \yeedomliu\api\Request $requestObj
     *
     * @return \yeedomliu\api\Request
     */
    public function requestHandle(Request $requestObj) {
        return $requestObj;
    }

    /**
     * 获取request对象
     *
     * @return \yeedomliu\api\Request
     */
    public function getRequestObj() {
        $requestObj = (new Request());
        $this->isPostRequest() ? $requestObj->setPostMethod() : $requestObj->setGetMethod();

        return $this->requestHandle($requestObj->setPrefix($this->getUrlPrefix()));
    }

    /**
     * 对字段名字进行处理（可以进行不同风格的转换）
     *
     * @return \yeedomliu\api\fieldstyle\LcfirstCamelize
     */
    public function getFieldNameHandleObj() {
        return new LcfirstCamelize();
    }

    /**
     * Returns given word as CamelCased.
     *
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline".
     *
     * @see variablize()
     *
     * @param string $word the word to CamelCase
     *
     * @return string
     */
    public static function camelize($word) {
        return str_replace(' ', '', ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word)));
    }

    /**
     * 获取处理完字段数组
     * 1.获取trait字段数组
     * 2.加入默认字段、自定义字段、去除排除字段
     *
     * @return array
     */
    public function getHandledFields() {
        // 通过反射获取所有trait对象
        {
            $obj = new \ReflectionClass(get_called_class());
            $traits = $obj->getTraits();
            if ($obj->getParentClass() && self::class != $obj->getParentClass()->name) {
                $parentTraits = $obj->getParentClass()->getTraits();
                if ($parentTraits) {
                    $traits = array_merge($traits, $parentTraits);
                }
            }
        }

        // 从trait里提取值
        {
            $fields = [];
            if ($traits) {
                foreach ($traits as $trait) {
                    $props = $trait->getProperties();
                    if (empty($props)) {
                        continue;
                    }
                    foreach ($props as $prop) {
                        $name = $this->getFieldNameHandleObj()->handle($prop->name);
                        $method = 'get' . self::camelize($prop->name);
                        $fields[ $name ] = call_user_func_array([$this, $method], []);
                    }
                }
            }
        }

        // 合并默认post/post字段/自定义字段，排除字段
        {
            $fields = array_merge($fields, $this->getDefaultPostFields(), $this->getPostFields(), $this->customFields());
            if ($this->getExcludeFields()) {
                foreach ($this->getExcludeFields() as $excludeField) {
                    unset($fields[ $excludeField ]);
                }
            }
        }

        // 排除空字段值
        {
            if ($this->isExcludeEmptyField()) {
                foreach ($fields as $key => $value) {
                    if (is_object($value)) {
                        continue;
                    }
                    if (is_array($value)) {
                        if (empty($value)) {
                            unset($fields[ $key ]);
                        }
                    } elseif (0 == strlen($value)) {
                        unset($fields[ $key ]);
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * 开始执行请求
     *
     * @return mixed
     */
    public function start() {
        $this->init();

        $requestObj = $this->getRequestObj();
        // 请求头部处理
        {
            $requestHeaders = array_merge($this->requestHeaders(), $this->getFullSetHeaderArray());
            if ($requestHeaders) {
                foreach ($requestHeaders as $key => $value) {
                    $requestObj->addHeader($key, $value);
                }
            }
        }

        // 把trait的属性都转换为字段名
        {
            $fields = $this->getHandledFields();
        }

        // 请求处理
        {
            $result = $requestObj->setHttpBuildQuery($this->isHttpBuildQuery())
                                 ->setOutputFormatObj($this->getOutputFormatObj())
                                 ->setJsonEncodeFields($this->jsonEncodeFields())
                                 ->setFields($fields)
                                 ->setExcludeFields($this->getExcludeFields())
                                 ->setUrl($this->getUri())
                                 ->setProxyClass($this->getProxyClass())
                                 ->setMethod($this->isPostRequest() ? Request::METHOD_POST : Request::METHOD_GET)
                                 ->setFullUrl($this->getFullUrl())
                                 ->setCurlOptions($this->getCurlOptions())
                                 ->setCacheTime($this->getCacheTime())
                                 ->setCacheKey($this->getCacheKey())
                                 ->request();

            $this->checkResult($result);
        }

        return $this->customOutput($result);
    }

    /**
     * 自定义输出
     *
     * @param $result
     *
     * @return mixed
     */
    public function customOutput($result) {
        return $result;
    }

    /**
     * 检查结果是否正确
     *
     * @param $result
     */
    public function checkResult($result) {
    }

    /**
     * 自定义字段
     *
     * @return array
     */
    public function customFields() {
        return [];
    }

    /**
     * json_encode字段值
     *
     * @return bool
     */
    public function jsonEncodeFields() {
        return false;
    }

    /**
     * 请求头部信息数组，以key/value形式
     *
     * @return array
     */
    public function requestHeaders() {
        return [];
    }

}