<?php

namespace yeedomliu\api\requestfields;

/**
 * 设置默认的post请求参数
 *
 * @package yeedomliu\api\requestfields
 */
trait DefaultPostFields
{

    /**
     * post字段
     *
     * @var array
     */
    protected $defaultPostFields = [];

    /**
     * @return array
     */
    public function getDefaultPostFields() {
        return $this->defaultPostFields;
    }

    /**
     * @param array $defaultPostFields
     *
     * @return $this
     */
    public function setDefaultPostFields(array $defaultPostFields) {
        $this->defaultPostFields = $defaultPostFields;

        return $this;
    }


}
