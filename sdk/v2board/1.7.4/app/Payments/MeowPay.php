<?php

// 
//   自己写别抄，抄NMB抄
// 
declare(strict_types=1);

namespace App\Payments;

class MeowPay
{
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }
    public function form()
    {
        return [
            'app_id' => [
                'label' => 'AppID',
                'description' => '应用ID',
                'type' => 'input',
            ],

            'currency_type' => [
                'label' => '货币',
                'description' => '默认CNY',
                'type' => 'input'
            ]
        ];
    }
    public function pay($order)
    {
        $meowpay = new Payment($this->config['app_id'], $order['trade_no'], $this->config['currency_type'], $order['total_amount']);
        $pay_link = $meowpay->get_pay_link();
        return [
            'type' => 1, // 0:qrcode 1:url
            'data' => $pay_link,
        ];
    }
    public function notify($params)
    {
        $r = (object) $params['params'];
        $app_id = $r->{'app_id'};
        if ($app_id == $this->config['app_id']) {
            $res = json_encode([
                'jsonrpc' => '2.0', 'id' => $params['id'], 'result' => ['status' => 'Done']
            ]);
            return [
                'trade_no' => $r->{'trade_no'},
                'callback_no' => $r->{'payment_id'},
                'custom_result' =>$res
            ];
        } else {
            return false;
        }
    }
}

function post_request($url, $data)
{
    $headerArray = array("Content-Type: application/json", "charset='utf-8'", "Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

final class Payment
{
    var $url = "https://api.meowpay.org/json_rpc/";
    var $app_id;
    var $trade_no;
    var $amount;
    var $currency_type;

    function __construct(
        string $app_id,
        string $trade_no,
        string $currency_type,
        int $amount
    ) {
        $this->app_id = $app_id;
        $this->trade_no = $trade_no;
        $this->amount = $amount;
        $this->currency_type = $currency_type;
    }
    function get_pay_link($url = null, $method = "create_payment")
    {
        if ($url === null) {
            $url = $this->url;
        };
        $js_rq_data = [];
        $js_rq_data['jsonrpc'] = '2.0';
        $js_rq_data['id'] = '0';
        $js_rq_data['method'] = $method;
        $js_rq_data['params']['app_id'] = $this->app_id;
        $js_rq_data['params']['trade_no'] = $this->trade_no;
        $js_rq_data['params']['amount'] = $this->amount;
        $js_rq_data['params']['currency_type'] = $this->currency_type;
        $rq = json_encode($js_rq_data);
        $response = post_request($url, $rq);
        return $response['result']['payment_info']['pay_link'];
    }
}
