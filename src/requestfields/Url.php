<?php

namespace yeedomliu\api\requestfields;

trait Url
{

    /**
     * 资源路径
     *
     * @var string
     */
    protected $url = '';

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url) {
        $this->url = $url;

        return $this;
    }


}
