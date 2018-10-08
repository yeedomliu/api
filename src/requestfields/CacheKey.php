<?php

namespace yeedomliu\api\requestfields;

/**
 * 接口缓存key，各自业务根据自己场景设置key
 *
 * @package yeedomliu\api\requestfields
 */
trait CacheKey
{

    /**
     * 缓存key
     *
     * @var string
     */
    protected $cacheKey = '';

    /**
     * @return string
     */
    public function getCacheKey(): string {
        return $this->cacheKey;
    }

    /**
     * @param string $cacheKey
     *
     * @return $this
     */
    public function setCacheKey(string $cacheKey) {
        $this->cacheKey = $cacheKey;

        return $this;
    }


}