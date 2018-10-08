<?php

namespace yeedomliu\api\requestfields;

/**
 * 代理类trait
 *
 * @package yeedomliu\api\requestfields
 */
trait ProxyClass
{

    /**
     * 请求前缀
     *
     * @var \yeedomliu\api\proxy\Base
     */
    protected $proxyClass;

    /**
     * @return \yeedomliu\api\proxy\Base
     */
    public function getProxyClass() {
        return $this->proxyClass;
    }

    /**
     * @param \yeedomliu\api\proxy\Base $proxyClass
     *
     * @return $this
     */
    public function setProxyClass($proxyClass) {
        $this->proxyClass = $proxyClass;

        return $this;
    }

}