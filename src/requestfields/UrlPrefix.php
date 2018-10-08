<?php

namespace yeedomliu\api\requestfields;

/**
 * url请求前缀
 *
 * @package yeedomliu\api\requestfields
 */
trait UrlPrefix
{

    /**
     * url前缀
     *
     * @var string
     */
    protected $urlPrefix = '';

    /**
     * @return string
     */
    public function getUrlPrefix() {
        return $this->urlPrefix;
    }

    /**
     * @param string $urlPrefix
     *
     * @return $this
     */
    public function setUrlPrefix($urlPrefix) {
        $this->urlPrefix = $urlPrefix;

        return $this;
    }

}
