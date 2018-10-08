<?php

namespace wii\plugin\api\requestfields;

trait ExcludeFields
{

    /**
     * 排除字段数组
     *
     * @var array
     */
    protected $excludeFields = [];

    /**
     * @return array
     */
    public function getExcludeFields(): array {
        return $this->excludeFields;
    }

    /**
     * @param array $excludeFields
     */
    public function setExcludeFields(array $excludeFields) {
        $this->excludeFields = $excludeFields;

        return $this;
    }


}
