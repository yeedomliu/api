<?php

namespace yeedomliu\api\requestfields;

/**
 * 请求header的getter/setter方法
 *
 * @package yeedomliu\api\requestfields
 */
trait HeaderGetterSetter
{

    /**
     * @var string
     */
    protected $headerHost = '';

    /**
     * @return string
     */
    public function getHeaderHost() {
        return $this->headerHost;
    }

    /**
     * @param string $headerHost
     */
    public function setHeaderHost($headerHost) {
        $this->headerHost = $headerHost;

        return $this;
    }

    /**
     * 获取完整设置请求头部数组
     *
     * @return array
     */
    public function getFullSetHeaderArray() {
        $return = [];
        if ($this->getHeaderHost()) {
            $return['Host'] = $this->getHeaderHost();
        }

        return $return;
    }

}