<?php

namespace yeedomliu\api\requestfields;

trait GetFields
{

    /**
     * get字段
     *
     * @var array
     */
    protected $getFields = [];

    /**
     * @return array
     */
    public function getGetFields() {
        return $this->getFields;
    }

    /**
     * @param array $getFields
     *
     * @return $this
     */
    public function setGetFields($getFields) {
        $this->getFields = $getFields;

        return $this;
    }

}