# PHP SDK 使用文件

## 0 版本记录

|日期|描述|操作|
|---|---|---|
|2017-09-01|创建PHP SDK|[下载](https://gitlab.oneitfarm.com/idg/Pay/blob/master/pay-sdk-20170901.zip)|

## 1 接入

下载最新SDK,解压放入项目中。

使用前引用 `autoload.php` 即可

```php

require_once '{your path}/pay-sdk/autoload.php';

```

## 2 调用

每个接口都在 `Request\` 目录下有对应 Class。每个接口的使用可以见各个接口文档。

使用示例:创建微信移动支付交易

```php

<?php

$payConfig = new \CIPay\PayConfig();
$payConfig->setAppkey("your appkey");
$payConfig->setAppsecret("your appsecret");
$payConfig->setChannel(0);      //也可不设置 默认0

try {
    $createWeixinMobileTrade = new \CIPay\Request\CreateWeixinMobileTrade();
    $createWeixinMobileTrade->setSubject("测试物品");
    $createWeixinMobileTrade->setTotalFee(1);
    $createWeixinMobileTrade->setOutTradeNo("1238767920472312");
    $createWeixinMobileTrade->setNotifyUrl("http://idg-tangsiyuan.tunnel.nibaguai.com/pay/t.php");
    $data = $createWeixinMobileTrade->execute($payConfig);

    //todo...
    var_dump($data);
} catch (Exception $e) {
    echo "请求失败:" . $e->getMessage();
}

```