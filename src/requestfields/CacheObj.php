<?php

namespace yeedomliu\api\requestfields;

/**
 * 接口缓存对象
 *
 * @package yeedomliu\api\requestfields
 */
trait CacheObj
{

    protected $cacheObj;

    /**
     * @return mixed
     */
    public function getCacheObj() {
        return $this->cacheObj;
    }

    /**
     * @param $cacheObj
     *
     * @return $this
     */
    public function setCacheObj($cacheObj): void {
        $this->cacheObj = $cacheObj;

        return $this;
    }


}