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
class GuzzleRequest
{

    /**
     * GuzzleRequest constructor.
     *
     * @param \yeedomliu\api\Base $base
     */
    public function __construct(Base $base) {
        $this->base = $base;
        $this->request = new \GuzzleHttp\Client([
                                                    'verify'          => false,
                                                    'base_uri'        => $base->getUrlPrefix(),
                                                    'timeout'         => $base->getTimeout(),
                                                    'connect_timeout' => $base->getTimeout(),
                                                ]);

    }

    /**
     * @var \GuzzleHttp\Client
     */
    protected $request;

    /**
     * @var \yeedomliu\api\Base
     */
    protected $base;

    /**
     * @return \yeedomliu\api\Base
     */
    public function getBase() {
        return $this->base;
    }

    //    use Prefix, Url, FullUrl, Fields, Method, JsonEncodeFields, Headers, ExcludeFields, CurlOptions, HttpBuildQuery, CacheTime, CacheKey, ProxyClass, Timeout, ConnectTimeout, ExcludeEmptyField, OutputFormatObj;

    /**
     * 获取完整请求路径
     *
     * @return string
     */
    public function getFullUrl() {
        $base = $this->getBase();
        $fields = $base->getHandledFields();
        if ($base->getFullUrl()) {
            $url = $this->getFullUrl();
        } else {
            $url = $base->getPrefix() . $base->getUri();
            if ( ! $base->isPostMethod()) {
                $url .= (preg_match('/\?/is', $url) ? "&" : "?") . (is_array($fields) ? http_build_query($fields) : $fields);
            }
        }

        return $url;
    }

    public function getFullFields() {
        $base = $this->getBase();
        $fields = $base->getHandledFields();
        if ($base->isPostMethod()) {
            if ($base->isJsonEncodeFields()) {
                $fields = json_encode($fields);
            }
            if (is_array($fields) && $base->isHttpBuildQuery()) {
                $fields = http_build_query($fields);
            }
        }

        return $fields;
    }

    /**
     * 执行请求
     *
     * @return mixed
     */
    public function request() {
        try {
            $base = $this->getBase();
            // 请求字段处理
            {
                $url = $this->getFullUrl();
                $fields = $this->getFullFields();
                //                if ($this->isPostMethod()) {
                //                    $this->addCurlOption(CURLOPT_POST, true);
                //                    $this->addCurlOption(CURLOPT_POSTFIELDS, $fields);
                //                }
            }

            // 请求处理
            {
                $cacheKey = $base->getCacheKey();
                if ($base->getCacheTime() && $base->getCacheObj()) {
                    $return = $base->getCacheObj()->get($cacheKey);
                }
                if ( ! empty($return)) {
                    return $return;
                } else {
                    if ($base->getProxyClass()) {
                        $return = $base->getProxyClass()
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
                        //                        $curlOptions = [
                        //                            CURLOPT_HTTPHEADER     => $this->getHeaders(),
                        //                            CURLOPT_URL            => $url,
                        //                            CURLOPT_RETURNTRANSFER => 1,
                        //                            CURLOPT_TIMEOUT        => $this->getTimeout(),
                        //                            CURLOPT_CONNECTTIMEOUT => $this->getConnectTimeout(),
                        //                        ];
                        //                        if ($this->getCurlOptions()) {
                        //                            foreach ($this->getCurlOptions() as $key => $value) {
                        //                                $curlOptions[ $key ] = $value;
                        //                            }
                        //                        }
                        //                        $this->addCurlOptions($curlOptions);
                        //
                        //                        curl_setopt_array($ch, $this->getCurlOptions());
                        //                        $return = curl_exec($ch);
                        $request = $this->request;
                        $return = $base->isPostRequest() ? $request->post($url)->getBody()->getContents() : $request->get($url)->getBody()->getContents();
                    }
                    //                    $status = curl_getinfo($ch);
                }
            }

            $return = $base->getOutputFormatObj()->handle($return);

            if ($base->getCacheTime()) {
                $base->getCacheObj()->set($cacheKey, $return);
            }

            return $return;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}