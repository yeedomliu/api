<?php

namespace wii\plugin\api;

use wii\plugin\api\eventfields\Exception;
use wii\plugin\api\eventfields\Fields;
use wii\plugin\api\eventfields\Method;
use wii\plugin\api\eventfields\CurlOptions;
use wii\plugin\api\eventfields\Result;
use wii\plugin\api\eventfields\Status;
use wii\plugin\api\eventfields\Url;
use wii\plugin\api\requestfields\Headers;

/**
 * 接口请求
 *
 *
 */
class Event extends \yii\base\Event
{

    use Fields, Method, CurlOptions, Result, Url, Status, Exception, Headers;

    /**
     * @var \wii\plugin\api\Request
     */
    protected $requestObj;

    /**
     * @return \wii\plugin\api\Request
     */
    public function getRequestObj(): \wii\plugin\api\Request {
        return $this->requestObj;
    }

    /**
     * @param \wii\plugin\api\Request $requestObj
     *
     * @return $this
     */
    public function setRequestObj(\wii\plugin\api\Request $requestObj) {
        $this->requestObj = $requestObj;

        return $this;
    }


}