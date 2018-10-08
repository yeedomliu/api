<?php

namespace yeedomliu\api\requestfields;

trait PostFields
{

    /**
     * post字段
     *
     * @var array
     */
    protected $postFields = [];

    /**
     * @return array
     */
    public function getPostFields() {
        return $this->postFields;
    }

    /**
     * @param array $postFields
     *
     * @return $this
     */
    public function setPostFields($postFields) {
        $this->postFields = $postFields;

        return $this;
    }
    
}
