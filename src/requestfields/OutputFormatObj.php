<?php

namespace wii\plugin\api\requestfields;

use wii\plugin\api\outputformat\Raw;

/**
 * 接口渲染处理对象，可以是json/raw或可以快速扩展其它类型
 *
 * @package wii\plugin\api\requestfields
 */
trait OutputFormatObj
{

    /**
     * 返回结果处理对象
     *
     * @var \wii\plugin\api\outputformat\Base
     */
    protected $outputFormatObj = null;

    /**
     * @return \wii\plugin\api\outputformat\Base
     */
    public function getOutputFormatObj() {
        return empty($this->outputFormatObj) ? new Raw() : $this->outputFormatObj;
    }

    /**
     * @param \wii\plugin\api\outputformat\Base $outputFormatObj
     *
     * @return $this
     */
    public function setOutputFormatObj($outputFormatObj) {
        $this->outputFormatObj = $outputFormatObj;

        return $this;
    }

}
