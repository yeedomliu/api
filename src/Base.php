<?php

namespace yeedomliu\api;

use wii\helpers\Inflector;
use yeedomliu\api\fieldstyle\LcfirstCamelize;
use yeedomliu\api\requestfields\CacheKey;
use yeedomliu\api\requestfields\CacheTime;
use yeedomliu\api\requestfields\CurlOptions;
use yeedomliu\api\requestfields\DefaultGetFields;
use yeedomliu\api\requestfields\DefaultPostFields;
use yeedomliu\api\requestfields\ExcludeEmptyField;
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

    use CacheTime, CacheKey, FullUrl, UrlPrefix, ExcludeEmptyField, PostRequest, Url, GetFields, PostFields, DefaultPostFields, DefaultGetFields, HeaderGetterSetter, OutputFormatObj, CurlOptions, ProxyClass, HttpBuildQuery;

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
            $keyValues = [];
            foreach ($getFieldArray as $name => $value) {
                $keyValues[] = "{$name}={$value}";
            }
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
     * 请求开始
     *
     * @return $this
     */
    public function eventStart() {
        \yii\base\Event::off(Request::className(), Request::EVENT_REQUEST_START);

        return $this;
    }

    /**
     * 请求事件before处理
     * 1.https请求处理
     *
     * @return $this
     */
    public function eventBefore() {
        \yii\base\Event::off(Request::className(), Request::EVENT_REQUEST_BEFORE);
        \yii\base\Event::on(Request::className(), Request::EVENT_REQUEST_BEFORE, function (Event $event) {
            if (stripos($event->getUrl(), "https://") !== false) {
                $event->getRequestObj()->addCurlOption(CURLOPT_SSL_VERIFYPEER, false)->addCurlOption(CURLOPT_SSL_VERIFYHOST, false)->addCurlOption(CURLOPT_SSLVERSION, 1);
            }
        });

        return $this;
    }

    /**
     * 请求事件after处理
     * 1.记录日志
     *
     * @return $this
     */
    public function eventAfter() {
        \yii\base\Event::off(Request::className(), Request::EVENT_REQUEST_AFTER);
        \yii\base\Event::on(Request::className(), Request::EVENT_REQUEST_AFTER, function (Event $event) {
            \Yii::info([
                           'url'     => $event->getUrl(),
                           'fields'  => $event->getFields(),
                           'method'  => $event->getMethod(),
                           'result'  => $event->getResult(),
                           'header'  => $event->getHeaders(),
                           'options' => $event->getCurlOptions(),
                           'status'  => $event->getStatus(),
                       ], 'request.result');
        });

        return $this;
    }

    /**
     * 请求事件异常处理
     *
     * @return $this
     */
    public function eventException() {
        \yii\base\Event::off(Request::className(), Request::EVENT_REQUEST_EXCEPTION);
        \yii\base\Event::on(Request::className(), Request::EVENT_REQUEST_EXCEPTION, function (Event $event) {
            \Yii::info([
                           'url'            => $event->getUrl(),
                           'fields'         => $event->getFields(),
                           'method'         => $event->getMethod(),
                           'result'         => $event->getResult(),
                           'options'        => $event->getCurlOptions(),
                           'header'         => $event->getHeaders(),
                           'status'         => $event->getStatus(),
                           'exception_msg'  => $event->getException()->getMessage(),
                           'exception_code' => $event->getException()->getCode(),
                       ], 'request.exception');
        });

        return $this;
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
                        $method = 'get' . Inflector::camelize($prop->name);
                        $fields[ $name ] = call_user_func_array([$this, $method], []);
                    }
                }
            }
        }

        // 合并默认post/post字段/自定义字段，排除字段
        {
            $fields = array_merge($fields, $this->getDefaultPostFields(), $this->getPostFields(), $this->customFields());
            if ($this->excludeFields()) {
                foreach ($this->excludeFields() as $excludeField) {
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

        // 事件处理
        {
            $this->eventStart()->eventBefore()->eventAfter()->eventException();
        }

        // 请求处理
        {
            $result = $requestObj->setHttpBuildQuery($this->isHttpBuildQuery())
                                 ->setOutputFormatObj($this->getOutputFormatObj())
                                 ->setJsonEncodeFields($this->jsonEncodeFields())
                                 ->setFields($fields)
                                 ->setExcludeFields($this->excludeFields())
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
     * 排除字段
     *
     * @return array
     */
    public function excludeFields() {
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