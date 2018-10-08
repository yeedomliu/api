<?php

namespace yeedomliu\api\requestfields;

use yeedomliu\api\outputformat\Raw;

/**
 * 接口渲染处理对象，可以是json/raw或可以快速扩展其它类型
 *
 * @package yeedomliu\api\requestfields
 */
trait OutputFormatObj
{

    /**
     * 返回结果处理对象
     *
     * @var \yeedomliu\api\outputformat\Base
     */
    protected $outputFormatObj = null;

    /**
     * @return \yeedomliu\api\outputformat\Base
     */
    public function getOutputFormatObj() {
        return empty($this->outputFormatObj) ? new Raw() : $this->outputFormatObj;
    }

    /**
     * @param \yeedomliu\api\outputformat\Base $outputFormatObj
     *
     * @return $this
     */
    public function setOutputFormatObj($outputFormatObj) {
        $this->outputFormatObj = $outputFormatObj;

        return $this;
    }

}
