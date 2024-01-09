## 步骤
<a href="https://meowpay.org" target="_blank" >注册账号</a> ->添加APP ->填写APP信息 ->使用交互脚本安装
``` bash
bash <(curl -sfSL https://raw.githubusercontent.com/Meowpay/MeowpayDocs/main/install.sh)
```

安装完毕后

打开whmcs的管理界面 ->Apps & Integrations ->Browse->选择Meowpay ->输入AppID
***
## 补充说明
return_url 为： https://你的网站地址/clientarea.php?action=invoices

notify_url 为： https://你的网站地址/modules/gateways/MeowPay/notify.php

**请务必使用HTTPS加密协议**

任何疑问请<a herf="https://t.me/MeowpayChannel" target="_blank" >联系我们</a>