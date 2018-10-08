<?php

namespace yeedomliu\api;

use wii\base\Component;
use yeedomliu\api\requestfields\CacheKey;
use yeedomliu\api\requestfields\CacheTime;
use yeedomliu\api\requestfields\ConnectTimeout;
use yeedomliu\api\requestfields\ExcludeEmptyField;
use yeedomliu\api\requestfields\ExcludeFields;
use yeedomliu\api\requestfields\Fields;
use yeedomliu\api\requestfields\FullUrl;
use yeedomliu\api\requestfields\Headers;
use yeedomliu\api\requestfields\HttpBuildQuery;
use yeedomliu\api\requestfields\JsonEncodeFields;
use yeedomliu\api\requestfields\Method;
use yeedomliu\api\requestfields\CurlOptions;
use yeedomliu\api\requestfields\Prefix;
use yeedomliu\api\requestfields\ProxyClass;
use yeedomliu\api\requestfields\OutputFormatObj;
use yeedomliu\api\requestfields\Timeout;
use yeedomliu\api\requestfields\Url;

/**
 * 接口请求
 *
 * 接口保持最简洁的功能，其它功能通过事件注入进来
 *
 */
class Request extends Component
{

    use Prefix, Url, FullUrl, Fields, Method, JsonEncodeFields, Headers, ExcludeFields, CurlOptions, HttpBuildQuery, CacheTime, CacheKey, ProxyClass, Timeout, ConnectTimeout, ExcludeEmptyField, OutputFormatObj;

    const METHOD_GET = 'get';

    const METHOD_POST = 'post';

    const EVENT_REQUEST_START = 'request_start';

    const EVENT_REQUEST_BEFORE = 'request_before';

    const EVENT_REQUEST_AFTER = 'request_after';

    const EVENT_REQUEST_EXCEPTION = 'request_exception';

    /**
     * 获取完整请求路径
     *
     * @return string
     */
    public function getFullUrl() {
        if ($this->fullUrl) {
            $url = $this->fullUrl;
        } else {
            $url = $this->getPrefix() . $this->getUrl();
            if ( ! $this->isPostMethod()) {
                $fields = $this->getFields();
                $fieldString = is_array($fields) ? http_build_query($fields) : $fields;
                $url .= preg_match('/\?/is', $url) ? "&" : "?";
                $url .= $fieldString;
            }
        }

        return $url;
    }

    public function getFullFields() {
        $fields = $this->getFields();
        if ($this->isPostMethod()) {
            if ($this->isJsonEncodeFields()) {
                $fields = json_encode($fields);
            }
            if (is_array($fields) && $this->isHttpBuildQuery()) {
                $fields = http_build_query($fields);
            }
        }

        return $fields;
    }

    public function getCacheKey(): string {
        return $this->cacheKey ? $this->cacheKey : "{$this->getFullUrl()}{$this->isPostMethod()}";
    }

    /**
     * 执行请求
     *
     * @return mixed
     */
    public function request() {
        try {
            $this->trigger(self::EVENT_REQUEST_START);

            // 请求字段处理
            {
                $url = $this->getFullUrl();
                $ch = curl_init();
                $fields = $this->getFullFields();
                if ($this->isPostMethod()) {
                    $this->addCurlOption(CURLOPT_POST, true);
                    $this->addCurlOption(CURLOPT_POSTFIELDS, $fields);
                }
            }

            // 请求处理
            {
                $event = (new Event())->setUrl($url)->setFields($fields)->setMethod($this->getMethod())->setCurlOptions($this->getCurlOptions())->setHeaders($this->getHeaders())->setRequestObj($this);
                $cacheKey = $this->getCacheKey();
                \Wii::info("request[{$url}] cache key[{$cacheKey}]");
                if ($this->getCacheTime()) {
                    $return = \Wii::app()->cache->get($cacheKey);
                }
                if ( ! empty($return)) {
                    \Wii::info("request[{$url}] from cache");

                    return $return;
                } else {
                    $this->trigger(self::EVENT_REQUEST_BEFORE, $event);
                    if ($this->getProxyClass()) {
                        $return = $this->getProxyClass()
                                       ->setPrefix($this->getPrefix())
                                       ->setUrl($url)
                                       ->setFields($fields)
                                       ->setJsonEncodeFields($this->isJsonEncodeFields())
                                       ->setHeaders($this->getHeaders())
                                       ->setExcludeFields($this->getExcludeFields())
                                       ->setCurlOptions($this->getCurlOptions())
                                       ->setHttpBuildQuery($this->isHttpBuildQuery())
                                       ->setMethod($this->getMethod())
                                       ->getContent();
                    } else {
                        $curlOptions = [
                            CURLOPT_HTTPHEADER     => $this->getHeaders(),
                            CURLOPT_URL            => $url,
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_TIMEOUT        => $this->getTimeout(),
                            CURLOPT_CONNECTTIMEOUT => $this->getConnectTimeout(),
                        ];
                        if ($this->getCurlOptions()) {
                            foreach ($this->getCurlOptions() as $key => $value) {
                                $curlOptions[ $key ] = $value;
                            }
                        }
                        $this->addCurlOptions($curlOptions);

                        curl_setopt_array($ch, $this->getCurlOptions());
                        $return = curl_exec($ch);
                    }
                    $status = curl_getinfo($ch);
                    $event->setStatus($status);

                    $this->trigger(self::EVENT_REQUEST_AFTER, $event->setResult($return));
                }
            }

            // 异常处理
            {
                //                if ($return === false) {
                //                    throw new \Exception("网络错误");
                //                }

                //                if ($status && intval($status["http_code"]) != 200) {
                //                    throw new \Exception("unexpected http code " . intval($status["http_code"]));
                //                }

                $return = $this->getOutputFormatObj()->handle($return);
                //                if (is_array($return)) {
                //                    if (0 != $return['errcode']) {
                //                        throw new \Exception("调用接口出现错误[{$return['errmsg']}]");
                //                    }
                //                }
            }
            if ($this->getCacheTime()) {
                \Wii::app()->cache->set($cacheKey, $return);
            }

            return $return;
        } catch (\Exception $e) {
            $this->trigger(self::EVENT_REQUEST_EXCEPTION, $event->setException($e));
            throw $e;
        }
    }

}