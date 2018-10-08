<?php

namespace yeedomliu\api\requestfields;

/**
 * 可以自行配置完整的请求uri地址
 *
 * @package yeedomliu\api\requestfields
 */
trait FullUrl
{

    /**
     * 资源完整路径
     *
     * @var string
     */
    protected $fullUrl = '';

    /**
     * @return string
     */
    public function getFullUrl(): string {
        return $this->fullUrl;
    }

    /**
     * @param string $fullUrl
     *
     * @return $this
     */
    public function setFullUrl(string $fullUrl) {
        $this->fullUrl = $fullUrl;

        return $this;
    }


}
