# API Address: https://api.meowpay.org/json_rpc/

## As Client

### create_payment

#### Input:

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

| Field         | Type   | Required | Description                                                                                                                        |
| ------------- | ------ | -------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| app_id        | String | Required | UUID. Identifier of application.                                                                                                   |
| trade_no      | String | Required | Trade number. Created by merchant.                                                                                                 |
| amount        | Number | Required | Positive Integet. Amount multiplied by 100.                                                                                        |
| currency_type | String | Optional | USD/EUR/CNY. Currency code. Has higher priority than application settings.                                                         |
| notify_url    | String | Optional | Notify url. Gateway will send a POST request to this url when payment is completed. Has higher priority than application settings. |
| return_url    | String | Optional | Return url. Gateway will redirect to this url when payment is completed. Has higher priority than application settings.            |

#### Output:

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

| Field          | Type   | Required | Description                                                                |
| -------------- | ------ | -------- | -------------------------------------------------------------------------- |
| app_id         | String | Required | UUID. Identifier of application.                                           |
| payment_id     | String | Required | UUID. Identifier of payment.                                               |
| currency_type  | String | Required | USD/EUR/CNY. Currency code. Has higher priority than application settings. |
| amount         | Number | Required | Positive Integet. Amount multiplied by 100.                                |
| pay_link       | String | Required | Payment link. Used for client to pay.                                      |
| completed      | String | Required | Unix Time Stamp(Millisecond) or Zero(Not completed).                       |
| notified       | String | Required | True,False or Failed.The payment is notified or not.                       |
| callback_error | String | Required | Callback error message.                                                    |

## As Server

### notify

#### Input:

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

| Field      | Type   | Required | Description                        |
| ---------- | ------ | -------- | ---------------------------------- |
| app_id     | String | Optional | UUID. Identifier of application.   |
| payment_id | String | Optional | UUID. Identifier of payment.       |
| trade_no   | String | Optional | Trade number. Created by merchant. |

#### Output:

```json
{
  "jsonrpc": "2.0",
  "id": "0",
  "result": {
    "status": "Ok"
  }
}
```

| Field  | Type   | Required | Description |
| ------ | ------ | -------- | ----------- |
| status | String | Optional |             |

# Usage

As client: Utilize the createPayment method to generate a payment link while specifying a notification URL.

As server: Implement a listener for the notifyURL and define a notify method to handle incoming notifications. Ensure validation of the appId and tradeNo to facilitate subsequent operations.
