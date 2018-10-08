<?php

namespace wii\plugin\api\requestfields;

trait JsonEncodeFields
{

    /**
     * 是否json_encode请求字段
     *
     * @var bool
     */
    protected $jsonEncodeFields = false;

    /**
     * @return bool
     */
    public function isJsonEncodeFields(): bool {
        return $this->jsonEncodeFields;
    }

    /**
     * @param bool $jsonEncodeFields
     *
     * @return $this
     */
    public function setJsonEncodeFields(bool $jsonEncodeFields) {
        $this->jsonEncodeFields = $jsonEncodeFields;

        return $this;
    }


}
