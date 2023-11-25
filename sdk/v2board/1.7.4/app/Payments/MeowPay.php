<?php

declare(strict_types=1);

namespace App\Payments;

use GuzzleHttp\Client;

final class MeowPay
{
    private array $config;

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
                'description' => '默认CNY，选填，支持CNY，USD，EUR...',
                'type' => 'input',
            ],
        ];
    }

    public function pay(array $order)
    {
        $currency_type = 'CNY';
        if (isset($this->config['currency_type'])) {
            $currency_type = $this->config['currency_type'];
        }
        $js_rq_data = [
            'jsonrpc' => '2.0',
            'id' => '0',
            'method' => 'create_payment',
            'params' => [
                'app_id' => $this->config['app_id'],
                'trade_no' => $order['trade_no'],
                'amount' => (int) $order['total_amount'],
                'currency_type' => $currency_type,
            ],
        ];
        $client = new Client();
        $res = $client->request(
            'POST',
            'https://api.meowpay.org/json_rpc/',
            ['json' => $js_rq_data],
        );
        $res_data = json_decode($res->getBody()->getContents(), true);
        return [
            'type' => 1, // 0:qrcode 1:url
            'data' => $res_data['result']['payment_info']['pay_link'],
        ];
    }

    public function notify($params)
    {
        $r = (object) $params['params'];
        $app_id = $r->{'app_id'};
        if ($app_id !== $this->config['app_id']) {
            return false;
        }
        $res = json_encode([
            'jsonrpc' => '2.0',
            'id' => $params['id'],
            'result' => ['status' => 'Done'],
        ]);
        return [
            'trade_no' => $r->{'trade_no'},
            'callback_no' => $r->{'payment_id'},
            'custom_result' => $res,
        ];
    }
}
