<?php

namespace yeedomliu\api\requestfields;

use yeedomliu\api\Request;

trait Method
{

    /**
     * 请求方法
     *
     * @var string
     */
    protected $method = '';

    /**
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method) {
        $this->method = $method;

        return $this;
    }

    /**
     * 设置get请求
     *
     * @return $this
     */
    public function setGetMethod() {
        $this->method = Request::METHOD_GET;

        return $this;
    }

    /**
     * 设置post方法
     *
     * @return $this
     */
    public function setPostMethod() {
        $this->method = Request::METHOD_POST;

        return $this;
    }



    /**
     * 是否是post请求
     *
     * @return bool
     */
    public function isPostMethod() {
        return Request::METHOD_POST == strtolower(trim($this->method));
    }



}
