<?php

namespace wii\plugin\api\requestfields;

/**
 * 设置默认的get请求参数
 *
 * @package wii\plugin\api\requestfields
 */
trait DefaultGetFields
{

    /**
     * get字段
     *
     * @var array
     */
    protected $defaultGetFields = [];

    /**
     * @return array
     */
    public function getDefaultGetFields() {
        return $this->defaultGetFields;
    }

    /**
     * @param array $defaultGetFields
     *
     * @return $this
     */
    public function setDefaultGetFields(array $defaultGetFields) {
        $this->defaultGetFields = $defaultGetFields;

        return $this;
    }

}