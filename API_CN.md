# API Address: https://api.meowpay.org/json_rpc/

## As Client

### create_payment

#### 请求:

```json
{
  "jsonrpc": "2.0",
  "id": "0",
  "method": "create_payment",
  "params": {
    "app_id": "0",
    "trade_no": "000",
    "amount": 9900,
    "currency_type": "USD",
    "notify_url": "https://example.com/payment/notify/meowpay",
    "return_url": "https://example.com/"
  }
}
```

| 字段          | 类型   | 必填 | Description                                                                                               |
| ------------- | ------ | ---- | --------------------------------------------------------------------------------------------------------- |
| app_id        | String | 必填 | UUID。 应用 ID 。                                                                                         |
| trade_no      | String | 必填 | 客户端创建的支付单号。                                                                                    |
| amount        | Number | 必填 | 正整数（金额乘以一百）。                                                                                  |
| currency_type | String | 可选 | USD/EUR/CNY。货币类型，优先级高于 App 设置。                                                              |
| notify_url    | String | 可选 | 通知地址。支付完成后会向这个地址发送一个 POST 请求，应用根据请求进行校验及后续处理。优先级高于 App 设置。 |
| return_url    | String | 可选 | 返回地址。支付完成后，支付页面跳转的地址。优先级高于 App 设置。                                           |

#### 返回:

```json
{
  "jsonrpc": "2.0",
  "id": "0",
  "result": {
    "app_id": "0",
    "payment_id": "00-000",
    "currency_type": "USD",
    "amount": 9900,
    "pay_link": "https://payment.meowpay.org/v1/payment_page?order_id=00-000",
    "completed": "0",
    "notified": "False",
    "callback_error": ""
  }
}
```

| 字段           | 类型   | 必填 | Description                                             |
| -------------- | ------ | ---- | ------------------------------------------------------- |
| app_id         | String | 必填 | UUID。 应用 ID。                                        |
| payment_id     | String | 必填 | UUID。MeowPay 生成的 订单 ID。                          |
| currency_type  | String | 必填 | 货币类型。                                              |
| amount         | Number | 必填 | 正整数（金额乘以一百）。                                |
| pay_link       | String | 必填 | 支付链接 。                                             |
| completed      | String | 必填 | Unix 时间戳(毫秒)。0 为未完成。                         |
| notified       | String | 必填 | True、False、Failed。分别代表已通知、未通知及通知失败。 |
| callback_error | String | 必填 | 通知错误信息。                                          |

## As Server

### notify

#### 请求:

```json
{
  "jsonrpc": "2.0",
  "id": "0",
  "method": "notify",
  "params": {
    "app_id": "0",
    "payment_id": "00-000",
    "trade_no": "000"
  }
}
```

| 字段       | 类型   | 必填 | Description                    |
| ---------- | ------ | ---- | ------------------------------ |
| app_id     | String | 可选 | UUID。 应用 ID。               |
| payment_id | String | 可选 | UUID。MeowPay 生成的 订单 ID。 |
| trade_no   | String | 可选 | 客户端创建的支付单号。         |

#### 返回:

```json
{
  "jsonrpc": "2.0",
  "id": "0",
  "result": {
    "status": "Ok"
  }
}
```

| 字段   | 类型   | 必填 | Description                                                           |
| ------ | ------ | ---- | --------------------------------------------------------------------- |
| status | String | 可选 | 成功的话给出这个返回值就好了，忘记怎么实现的了。或许没有错误码就 Ok。 |

# 如何使用

As Client: 利用 createPayment 方法生成支付链接并指定通知 URL。

As Server: 实现一个 notifyURL 的监听器，并定义一个 notify 方法来处理传入的通知。确保 appId 和 tradeNo 的有效性，以便进行后续操作。
