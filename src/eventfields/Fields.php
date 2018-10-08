<?php

namespace wii\plugin\api\eventfields;

trait Fields
{

    /**
     * 字段
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
     *
     * @return $this
     */
    public function setFields($fields) {
        $this->fields = $fields;

        return $this;
    }


}
