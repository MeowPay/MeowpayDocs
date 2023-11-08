## 步骤
[注册账号](https://meowpay.org) ->升级为商业用户 ->获取APP配额 ->添加APP ->输入信息 ->使用交互脚本安装

``` bash
bash <(curl -sfSL https://raw.githubusercontent.com/Meowpay/MeowpayDocs/main/install.sh)
```


安装完毕后打开管理后台，支付配置->添加支付方式->输入appID ->启用 复制通知地址到 你的app信息

## 补充说明
return_url 为 https://您的网站地址/#/order

notify_url 为 https://您的网站地址/api/v1/guest/payment/notify/MeowPay/xxx

notify_url添加APP时可留空 安装完毕后可进入管理后台->支付配置 查看

**请务必使用HTTPS加密协议**