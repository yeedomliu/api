<?php

namespace wii\plugin\api\eventfields;

trait Url
{

    /**
     * url
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
