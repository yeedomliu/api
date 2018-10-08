<?php

namespace yeedomliu\api\requestfields;

trait Headers
{

    /**
     * 请求头部数组
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 请求头部map数组
     *
     * @var array
     */
    protected $mapHeaders = [];

    /**
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers) {
        $this->headers = $headers;

        return $this;
    }

    /**
     * 添加头部信息
     *
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function addHeader($name, $value) {
        $this->headers[] = "{$name}:{$value}";
        $this->mapHeaders[ $name ] = $value;

        return $this;
    }

    /**
     * 获取请求头部map数组
     *
     * @return array
     */
    public function getMapHeaders() {
        return $this->mapHeaders;
    }
}
