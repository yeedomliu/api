<?php

namespace wii\plugin\api\requestfields;

trait Timeout
{

    /**
     * 超时时间
     *
     * @var int
     */
    protected $timeout = 10;

    /**
     * @return int
     */
    public function getTimeout() {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;

        return $this;
    }


}