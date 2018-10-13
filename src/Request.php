<?php

namespace yeedomliu\api;

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
class Request
{

    use Prefix, Url, FullUrl, Fields, Method, JsonEncodeFields, Headers, ExcludeFields, CurlOptions, HttpBuildQuery, CacheTime, CacheKey, ProxyClass, Timeout, ConnectTimeout, ExcludeEmptyField, OutputFormatObj;

    const METHOD_GET = 'get';

    const METHOD_POST = 'post';

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
                $url .= (preg_match('/\?/is', $url) ? "&" : "?") . (is_array($this->getFields()) ? http_build_query($this->getFields()) : $this->getFields());
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

    public function getCacheObj() {
        return null;
    }

    /**
     * 执行请求
     *
     * @return mixed
     */
    public function request() {
        try {
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
                $cacheKey = $this->getCacheKey();
                if ($this->getCacheTime()) {
                    $return = $this->getCacheObj()->get($cacheKey);
                }
                if ( ! empty($return)) {
                    return $return;
                } else {
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
                    //                    $status = curl_getinfo($ch);
                }
            }

            $return = $this->getOutputFormatObj()->handle($return);

            if ($this->getCacheTime()) {
                $this->getCacheObj()->set($cacheKey, $return);
            }

            return $return;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}