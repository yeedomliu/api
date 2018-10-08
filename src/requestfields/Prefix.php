<?php

namespace yeedomliu\api\requestfields;

trait Prefix
{

    /**
     * 请求前缀
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * @return string
     */
    public function getPrefix(): string {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix(string $prefix) {
        $this->prefix = $prefix;

        return $this;
    }


}
