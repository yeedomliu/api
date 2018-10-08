<?php

namespace yeedomliu\api;

use yeedomliu\api\eventfields\Exception;
use yeedomliu\api\eventfields\Fields;
use yeedomliu\api\eventfields\Method;
use yeedomliu\api\eventfields\CurlOptions;
use yeedomliu\api\eventfields\Result;
use yeedomliu\api\eventfields\Status;
use yeedomliu\api\eventfields\Url;
use yeedomliu\api\requestfields\Headers;

/**
 * 接口请求
 *
 *
 */
class Event extends \yii\base\Event
{

    use Fields, Method, CurlOptions, Result, Url, Status, Exception, Headers;

    /**
     * @var \yeedomliu\api\Request
     */
    protected $requestObj;

    /**
     * @return \yeedomliu\api\Request
     */
    public function getRequestObj(): \yeedomliu\api\Request {
        return $this->requestObj;
    }

    /**
     * @param \yeedomliu\api\Request $requestObj
     *
     * @return $this
     */
    public function setRequestObj(\yeedomliu\api\Request $requestObj) {
        $this->requestObj = $requestObj;

        return $this;
    }


}