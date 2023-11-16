<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function codepay_alipay_MetaData()
{
    return array(
        'DisplayName' => 'MeowPay',
        'APIVersion' => '1.0', //版本信息
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    );
}

function MeowPay_config()
{
    $configarray = [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => '喵支付-XMR',
        ],
        'app_id' => [
            'FriendlyName' => '应用号',
            'Type' => 'text',
            'Size' => '36',
            'Default' => '',
            'Description' => '输入你的AppId',
        ],
        'currency_code' => [
            'FriendlyName' => '货币类型',
            'Type' => 'text',
            'Size' => '4',
            'Value' => 'CNY',
            'Description' => 'USD/EUR/CNY',
        ],
    ];
    return $configarray;
}

function meowpay_link($params)
{
    $amount = $params['amount'] * 100;
    $meowpay = new Payment($params['app_id'], $params['invoiceid'], $params['currency_code'], (int)$amount);
    $pay_link = $meowpay->get_pay_link();
    return <<<HTML
    <a href="{$pay_link}" target="_blank" id="alipayDiv" class="btn btn-info btn-block">前往收银台</a>
HTML;
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
    var $return_url;
    var $notify_url;

    function __construct(
        string $app_id,
        string $trade_no,
        string $currency_type,
        int $amount,
        string $return_url = null,
        string $notify_url = null
    ) {
        $this->app_id = $app_id;
        $this->trade_no = $trade_no;
        $this->amount = $amount;
        $this->currency_type = $currency_type;
        $this->return_url = $return_url;
        $this->notify_url = $notify_url;
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
        $js_rq_data['params']['return_url'] = $this->return_url;
        $js_rq_data['params']['notify_url'] = $this->notify_url;
        $rq = json_encode($js_rq_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
        $response = post_request($url, $rq);
        return $response['result']['payment_info']['pay_link'];
    }
}
