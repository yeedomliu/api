<?php

namespace wii\plugin\api\requestfields;

/**
 * 代理类trait
 *
 * @package wii\plugin\api\requestfields
 */
trait ProxyClass
{

    /**
     * 请求前缀
     *
     * @var \wii\plugin\api\proxy\Base
     */
    protected $proxyClass;

    /**
     * @return \wii\plugin\api\proxy\Base
     */
    public function getProxyClass() {
        return $this->proxyClass;
    }

    /**
     * @param \wii\plugin\api\proxy\Base $proxyClass
     *
     * @return $this
     */
    public function setProxyClass($proxyClass) {
        $this->proxyClass = $proxyClass;

        return $this;
    }

}