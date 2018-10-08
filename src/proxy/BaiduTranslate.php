<?php

namespace wii\plugin\api\proxy;

use linslin\yii2\curl\Curl;
use wii\plugin\api\outputformat\Raw;

class BaiduTranslate extends Base
{

    public function getContent() {
        $tmpUrl = urlencode($this->getUrl());
        $url = "http://translate.baiducontent.com/transpage?query={$tmpUrl}&from=en&to=zh&source=url";
        $curl = new Curl();
        $curl->setOption(CURLOPT_TIMEOUT, 5);
        $result = $curl->get($url, (new Raw()) == $this->getOutputFormatObj());
        $result = preg_replace_callback('/http:\/\/translate.baiducontent.com.+?source=url&query=(.+?)&from=en&to=zh&token=&monLang=zh/is', function ($matches) {
            return urldecode($matches[1]);
        }, $result); // 处理翻译url
        $result = preg_replace('/<trans data-src="(.+?)">.+?<\/trans>/is', '$1', $result); // 处理翻译词条

        return $result;
    }

}