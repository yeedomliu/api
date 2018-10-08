<?php

namespace yeedomliu\api\requestfields;

/**
 * http_build_query处理
 *
 * 如果提交多维数组需要设置为true对请求的字段进行处理
 *
 * @package yeedomliu\api\requestfields
 */
trait HttpBuildQuery
{

    /**
     * http_build_query处理
     *
     * @var boolean
     */
    protected $httpBuildQuery = false;

    /**
     * @return bool
     */
    public function isHttpBuildQuery(): bool {
        return $this->httpBuildQuery;
    }

    /**
     * @param bool $httpBuildQuery
     *
     * @return $this
     */
    public function setHttpBuildQuery(bool $httpBuildQuery) {
        $this->httpBuildQuery = $httpBuildQuery;

        return $this;
    }


}
