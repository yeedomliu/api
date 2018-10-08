<?php

namespace yeedomliu\api\eventfields;

trait Result
{

    /**
     * 请求结果
     *
     * @var string
     */
    protected $result = '';

    /**
     * @return string
     */
    public function getResult(): string {
        return $this->result;
    }

    /**
     * @param string $result
     *
     * @return $this
     */
    public function setResult(string $result) {
        $this->result = $result;

        return $this;
    }


}
