<?php

namespace yeedomliu\api\eventfields;

trait Status
{

    /**
     * 状态
     *
     * @var array
     */
    protected $status = [];

    /**
     * @return array
     */
    public function getStatus(): array {
        return $this->status;
    }

    /**
     * @param array $status
     *
     * @return $this
     */
    public function setStatus(array $status) {
        $this->status = $status;

        return $this;
    }

}
