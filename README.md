接口请求封装服务
========


[TOC]



## 介绍

通用接口封装服务是针对接口请求调用的服务，具有**快速、简单、灵活、关注真正逻辑**的特点，使用该服务可以快速地针对请求接口功能进行封装，如微信、企业微信、腾讯云、新浪微博等众api快速实现。



背景
-----

以往封装接口时常常碰到几个问题:
* 接口代码重复
* 接口参数不明确，或是设置很多不必要的参数
* 接口代码量相对多
* 无法调试单个接口

接口封装服务解决了以上所有问题◉‿◉ 

入门
-----

### 10秒快速入门

通过以下代码，可以快速实现访问一个接口请求

```php
class QuickStart extends \yeedomliu\api\Base {
    public function url() {
		return 'https://cvm.tencentcloudapi.com/?Action=DescribeInstances';
    }
}
print_r((new QuickStart())->start());
```

输出结果：

```php
Array
(
    [Response] => Array
        (
            [Error] => Array
                (
                    [Code] => MissingParameter
                    [Message] => The request is missing a required parameter `Timestamp`.
                )

            [RequestId] => 7b5d0cad-e111-4a5c-877b-*********
        )

)
```

通过以上示例我们知道封装一个接口的事情：

* 一个类对应一个接口
* 接口的调用是使用`start`方法



我们以企业微信[获取access_token接口](http://work.weixin.qq.com/api/doc#10013/%E7%AC%AC%E4%B8%89%E6%AD%A5%EF%BC%9A%E8%8E%B7%E5%8F%96access_token)为例，需要请求地址https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=ID&corpsecret=SECRECT，带上corpid和corpsecret参数。

### 获取AccessToken接口类封装

所有的接口请求都必须继承`\yeedomliu\api\Base`类，它对接口请求做了统一包装，文件保存在`models/workwx/AccessToken.php`


```php
namespace app\models\workwx;

use yeedomliu\api\Base;

/**
 * 获取access_token
 *
 * @link    http://work.weixin.qq.com/api/doc#10013/%E7%AC%AC%E4%B8%89%E6%AD%A5%EF%BC%9A%E8%8E%B7%E5%8F%96access_token
 */
class AccessToken extends Base
{

    public function url() {
        return "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=***&corpsecret=***";
    }

}
```

创建action，只需要`new`一个对象并调用`start`方法就完成调用并输出结果，所有的接口封装类都以`start`为最终调用

```php
namespace app\controllers;

use app\models\workwx\AccessToken;

class WorkwechatController extends \yii\web\Controller
{

    public function actionAccesstoken() {
        echo '<pre>';
        print_r((new AccessToken())->start());
        echo '</pre>';
    }

}
```

调用结果显示

```Php
Array
(
    [errcode] => 0
    [errmsg] => ok
    [access_token] => -Gg-pj8oG26cfMdBQTb28bfTb0yfnmIEU61TrEDv5e9KyfI-g7b47AgpEWMiuwgQqokcim_Fs6g0WsQ5hPKHwijUpRQp3-Hri1QD-jwG0zBXeoq53Q8GLDf0Se2dx0bgiI-q***********************************************************************
    [expires_in] => 7200
)
```



### 获取应用接口接口类封装

我们再以类似的方法封装获取应用列表的接口：

* 封装请求类
* 调用

```php
namespace app\models\workwx;

use yeedomliu\api\Base;

/**
 * 获取应用列表
 *
 * @link    http://work.weixin.qq.com/api/doc#11214
 */
class AgentLists extends Base
{

    public function url() {
        return "https://qyapi.weixin.qq.com/cgi-bin/agent/list?access_token=" . (new AccessToken())->start()['access_token'];
    }

}
```

创建action，并输出结果

```php
namespace app\controllers;

use app\models\workwx\AgentLists;

class WorkwechatController extends \yii\web\Controller
{

    public function actionAgentlists() {
        echo '<pre>';
        print_r((new AgentLists())->start());
        echo '</pre>';
    }

}
```

调用结果显示

```php
Array
(
    [errcode] => 0
    [errmsg] => ok
    [agentlist] => Array
        (
            [0] => Array
                (
                    [agentid] => 1000002
                    [name] => SQM系统
                    [square_logo_url] => http://p.qlogo.cn/bizmail/vPnUI281Hzzp8CXHlvnSVGm2icibJvSKwSHiaD9rEJEEZ29uZFOOLRJdQ/0
                )

        )

)
```

通过以上2个示例发现，每个接口的调用封装在各自类，各自接口封装、调用独立了，但也没什么特别的，接下来将进入到真实好用、实用的功能！

### 公共功能提取

通过封装了2个接口，发现一些问题：

* 代码有点冗余，都需要写http://等前缀
* 其它的功能接口都需要加access_token参数

针对这两个问题，可以使用`requestPrefix`和`defaultGetFields`来解决，在基类我们把`请求前缀`和`默认get参数`加上

```php
namespace app\models\workwx;

class Base extends \yeedomliu\api\Base
{

    public function requestPrefix() {
        return 'https://qyapi.weixin.qq.com/cgi-bin/';
    }

    public function defaultGetFields() {
        return [
            'access_token' => (new AccessToken())->start()['access_token'],
        ];
    }

}
```

把之前`获取access token`和`应用列表接口`接口改造如下：

```Php
namespace app\models\workwx;

/**
 * 获取access_token
 *
 * @link    http://work.weixin.qq.com/api/doc#10013/%E7%AC%AC%E4%B8%89%E6%AD%A5%EF%BC%9A%E8%8E%B7%E5%8F%96access_token
 */
class AccessToken extends Base
{

    public function url() {
        return "gettoken";
    }

    public function defaultGetFields() {
        return [
            'corpid'     => \Wii::app()->params['workwx']['corpid'],
            'corpsecret' => \Wii::app()->params['workwx']['corpsecret'],
        ];
    }

}
```

```php
namespace app\models\workwx;

/**
 * 获取应用列表
 *
 * @link    http://work.weixin.qq.com/api/doc#11214
 */
class AgentLists extends Base
{

    public function url() {
        return "/agent/list";
    }

}
```

> 通过配置请求前缀和默认参数的方法把一些公共的功能提取到了基类当中，使得接口的封装更加简单，可以看到获取应用列表接口只需要短短几行代码就实现了

### 带参数的请求

实际情况，接口请求中带参数的情况比较多的，接口请求封装服务提供了很好的方案解决了这个问题，需要2个步骤：

* 添加字段trait类
* 接口封装类使用trait字段类

trait字段类比较简单，只有get/set方法，代码如下，

```Php
namespace app\models\workwx\fields;

trait Agentid
{

    /**
     * 授权方应用id
     *
     * @var string
     */
    protected $agentid = '';

    /**
     * @return string
     */
    public function getAgentid(): string {
        return $this->agentid;
    }

    /**
     * @param string $agentid
     *
     * @return $this
     */
    public function setAgentid(string $agentid) {
        $this->agentid = $agentid;

        return $this;
    }

}
```

在请求类中，只需要用use关键字就能使用了

```php
namespace app\models\workwx;

use app\models\workwx\fields\Agentid;

/**
 * 获取应用
 *
 * @link    http://work.weixin.qq.com/api/doc#10087
 */
class AgentDetail extends Base
{

    use Agentid;

    public function url() {
        return "agent/get";
    }

}
```

使用中，只需要在接口对象类动态的设置`agentid`参数就可以了

```php
public function actionAgentdetail()
{
    echo '<pre>';
    print_r((new AgentDetail())->setAgentid('1000002')->start());
    echo '</pre>';
}
```

通过这样的方式，可以很好的解决了2个问题：

* 查看接口参数比较明确
* 无需设置不必要的参数

输出结果

```Php
Array
(
    [errcode] => 0
    [errmsg] => ok
    [agentid] => 1000002
    [name] => SQM系统
    [square_logo_url] => http://p.qlogo.cn/bizmail/vPnUI281Hzzp8CXHlvnSVGm2icibJvSKwSHiaD9rEJEEZ29uZFOOLRJdQ/0
    [description] => SQM系统
    [allow_userinfos] => Array
        (
            [user] => Array
                (
                    [0] => Array
                        (
                            [userid] => jay
                        )

                    [1] => Array
                        (
                            [userid] => LiuYiDong
                        )

                    [2] => Array
                        (
                            [userid] => HaPiXiaoLiang
                        )

                )

        )

    [allow_partys] => Array
        (
            [partyid] => Array
                (
                )

        )

    [close] => 0
    [redirect_domain] => 
    [report_location_flag] => 0
    [isreportenter] => 0
    [home_url] => 
)
```

>  [带参数的请求视频](http://v.qq.com/x/page/t0657s6uzs9.html)

### post请求

通过简单的配置就能实现post请求

```php
public function isPostRequest() {
    return true;
}
```

> [post请求视频](https://v.qq.com/x/page/u065963cje7.html)

### 文件上传

和带参数请求一样，文件上传需要2步：

* 定义media文件上传trait字段
* 在上传类添加media字段，设置post提交

> 上传字段稍微有点不一样，获取值的时候需要返回`CURLFile`对象，并且要设置名字（最后一个参数）

```php
<?php

namespace app\models\workwx\fields;

trait Media
{

    /**
     * 使用multipart/form-data POST上传文件， 文件标识名为 “media”
     *
     * @var string
     */
    protected $media = '';

    /**
     * @return \CURLFile
     */
    public function getMedia() {
        return new \CURLFile($this->media, '', basename($this->media));
    }

    /**
     * @param string $media
     *
     * @return $this
     */
    public function setMedia(string $media) {
        $this->media = $media;

        return $this;
    }


}
```

> 上传文件类必须是post请求

```php
namespace app\models\workwx;

use app\models\workwx\fields\Media;

/**
 * 上传图片
 *
 * @link    http://work.weixin.qq.com/api/doc#13219
 */
class Uploadimg extends Base
{

    public function url() {
        return "media/uploadimg";
    }

    use Media;

    public function isPostRequest() {
        return true;
    }

}
```

只需要在上传时设置上传文件就可以了：

```php
public function actionUploadimg() {
    echo '<pre>';
    print_r((new Uploadimg())->setMedia('/tmp/pic.png')->start());
    echo '</pre>';
    exit();
}
```

可以看到，短短的几行代码就实现了文件上传功能

> 注意\CURLFile类最后面的名字设定，不然在某些场景下上传会有问题
>
> [文件上传视频](http://v.qq.com/x/page/b06597nbdrp.html)

### 字段json_encode提交

如果想以raw的方式请求只需要覆写`jsonEncodeFields`返回true就好了。

```php
public function jsonEncodeFields() {
    return true;
}
```

> [字段json_encode提交视频](http://v.qq.com/x/page/r0659nkf114.html)

### 单独调试一个或多个接口

以往，我们在调试一个接口的时候有可能会影响到其它接口，接口请求提供了请求前缀方法，可以实现单独高度一个或一类接口，但是其它的接口是不受影响的。

> [单独调试一个或多个接口视频](https://v.qq.com/x/page/t065940hocm.html)



## 配置项

|        方法名         |                             描述                             |
| :-------------------: | :----------------------------------------------------------: |
|         init          |                            初始化                            |
|     isPostRequest     |                         是否post请求                         |
|          url          |           请求url，`完整的前缀路径是请求前缀+url`            |
|     requestPrefix     |                           请求前缀                           |
| getFieldNameHandleObj | 不同接口的字段命令是不一样的，可能是_分隔、驼峰形式或其它，这里可以提供不同风格字段的处理 |
|     customFields      |                  自定义字段，key/value形式                   |
|     excludeFields     |     排除字段，不想提交参数带上的字段，以名字数组方式提供     |
|   defaultGetFields    | 默认get字段，不管是get/post请求，都会当作url参数。key/value方式 |
|     defaultFields     | 默认字段，`如果是get请求会追加到url参数，如果是post请求会当作post参数` |
|   jsonEncodeFields    |                      json_encode字段值                       |
|    httpBuildQuery     |      如果提交多维数组需要设置为true对请求的字段进行处理      |
|    requestHeaders     |              请求头部信息数组，以key/value形式               |

## 总结

通过以上示例的说明，接口封装服务可把接口请求的封装变成是一种享受，提供了以下优点：

* 字段定义清晰，一眼能识别出来
* 代码量减少50%以上
* 灵活的扩展机制
* 只需要在真正的逻辑




