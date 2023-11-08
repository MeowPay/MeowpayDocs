## 步骤
[注册账号](https://meowpay.org) ->升级为商业用户 ->获取APP配额 ->添加APP ->输入信息 ->使用交互脚本安装

``` bash
bash <(curl -sfSL https://raw.githubusercontent.com/Meowpay/MeowpayDocs/main/install.sh)
```

安装完毕后，添加

```php
$_ENV['meowpay_app_id'] = '你的APP_ID';
```

到config/.config.php 


## 补充说明
APP_ID，在[应用信息内](https://meowpay.org/app/list)可点击复制

22.01版 return_url 为：https://你的网站地址/user/code

23.05版 return_url 为：https://你的网站地址/user/order

notify_url 为 https://你的网站地址/payment/notify/meowpay

**请务必使用HTTPS加密协议**

任何疑问请[联系我们](https://t.me/MeowpayChannel)