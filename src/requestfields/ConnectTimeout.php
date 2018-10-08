<?php

namespace wii\plugin\api\requestfields;

trait ConnectTimeout
{

    /**
     * 连接超时时间
     *
     * @var int
     */
    protected $connectTimeout = 10;

    /**
     * @return int
     */
    public function getConnectTimeout() {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout
     *
     * @return $this
     */
    public function setConnectTimeout($connectTimeout) {
        $this->connectTimeout = $connectTimeout;

        return $this;
    }

}