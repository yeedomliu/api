<?php

namespace wii\plugin\api\requestfields;

trait PostRequest
{

    /**
     * 是否是post请求
     *
     * @var boolean
     */
    protected $postRequest = false;

    /**
     * @return bool
     */
    public function isPostRequest() {
        return $this->postRequest;
    }

    public function getPostRequest() {
        return $this->postRequest;
    }

    /**
     * @param bool $postRequest
     */
    public function setPostRequest(bool $postRequest) {
        $this->postRequest = $postRequest;

        return $this;
    }

}
