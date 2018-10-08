<?php

namespace wii\plugin\api\requestfields;

/**
 * curl请求选项
 *
 * @package wii\plugin\api\requestfields
 */
trait CurlOptions
{

    /**
     * 请求选项配置
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

    /**
     * 添加选项
     *
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function addCurlOption($name, $value) {
        $this->curlOptions[ $name ] = $value;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function addCurlOptions(array $options) {
        if ($options) {
            foreach ($options as $name => $value) {
                $this->addCurlOption($name, $value);
            }
        }

        return $this;
    }

}
