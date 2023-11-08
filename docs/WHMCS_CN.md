## 步骤
[注册账号](https://meowpay.org) ->升级为商业用户 ->获取APP配额 ->添加APP ->输入信息
->使用交互脚本安装
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

任何疑问请[联系我们](https://t.me/MeowpayChannel)