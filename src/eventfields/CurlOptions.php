<?php

namespace wii\plugin\api\eventfields;

trait CurlOptions
{

    /**
     * 请求选项
     *
     * @var array
     */
    protected $curlOptions = [];

    /**
     * @return array
     */
    public function getCurlOptions(): array {
        return $this->curlOptions;
    }

    /**
     * @param array $curlOptions
     *
     * @return $this
     */
    public function setCurlOptions(array $curlOptions) {
        $this->curlOptions = $curlOptions;

        return $this;
    }


}
