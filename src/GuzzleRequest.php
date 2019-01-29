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

    /**
     * @return \GuzzleHttp\Client
     */
    public function getRequest() {
        return $this->request;
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
            $request = $this->getRequest();
            $options = [];
            // 请求字段处理
            {
                $url = $this->getFullUrl();
                $fields = $this->getFullFields();
                if ($base->isPostRequest()) {
                    $options['body'] = $fields;
                }
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
                    $options['headers'] = $base->getHeaders();
                    if ($base->isPostRequest()) {
                        $return = $request->post($url, $options)->getBody()->getContents();
                    } else {
                        $return = $request->get($url, $options)->getBody()->getContents();
                    }
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