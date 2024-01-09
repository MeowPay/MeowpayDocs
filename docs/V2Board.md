## 步骤
<a href="https://meowpay.org" target="_blank" >注册账号</a> ->添加APP ->填写APP信息 ->使用交互脚本安装

``` bash
bash <(curl -sfSL https://raw.githubusercontent.com/Meowpay/MeowpayDocs/main/install.sh)
```


安装完毕后打开管理后台，支付配置->添加支付方式->输入appID ->启用 复制通知地址到 你的app信息

## 补充说明
return_url 为 https://您的网站地址/#/order

notify_url 为 https://您的网站地址/api/v1/guest/payment/notify/MeowPay/xxx

notify_url添加APP时可留空 安装完毕后可进入管理后台->支付配置 查看

图标url 为 https://meowpay.org/favicon.ico

**请务必使用HTTPS加密协议**

任何疑问请<a herf="https://t.me/MeowpayChannel" target="_blank" >联系我们</a>