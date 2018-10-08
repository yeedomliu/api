<?php

namespace yeedomliu\api\requestfields;

trait Fields
{

    /**
     * 请求字段数组
     *
     * @var array|string
     */
    protected $fields = [];

    /**
     * @return array|string
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param array|string $fields
     */
    public function setFields($fields) {
        $this->fields = $fields;

        return $this;
    }


}
