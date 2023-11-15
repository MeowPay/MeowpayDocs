<?php

declare(strict_types=1);

namespace App\Services\Gateway;

use App\Models\Paylist;
use App\Services\Auth;
use Psr\Http\Message\ResponseInterface;
use function json_decode;

final class MeowPay extends AbstractPayment
{
    public static function _name(): string
    {
        return 'meowpay';
    }

    public static function _enable(): bool
    {
        // return self::getActiveGateway('meowpay');
        return true;
    }
    public function getReturnHTML($request, $response, $args)
    {
    }
    public function getStatus($request, $response, $args)
    {
    }
    public static function _readableName(): string
    {
        return 'meowpay';
    }
    public function purchase($request, $response, $args)
    {
        $price = $request->getParam('price');
        $price = (float)$price;
        $trade_no = uniqid();
        $trade_no = time();
        if ($price <= 0) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法的金额',
            ]);
        }
        $user = Auth::getUser();
        $pl = new Paylist();
        $pl->userid = $user->id;
        $pl->total = $price;
        $pl->tradeno = $trade_no;
        $pl->save();
        $app_id = $_ENV['meowpay_app_id'];
        $currency_type = "CNY";
        $amount = $price * 100;
        $meowpay = new Payment($app_id, (string) $trade_no, $currency_type, (int)$amount);
        $pay_link = $meowpay->get_pay_link();
        header('Location: ' . $pay_link);
    }

    public function notify($request, $response, $args): ResponseInterface
    {
        $r = (object) $request->getParsedBody();
        $params = (object) $r->{'params'};
        $app_id = $params->{'app_id'};
        if ($app_id == $_ENV['meowpay_app_id']) {
            $order_id = $params->{'trade_no'};
            $this->postPayment($order_id, "Meowpay");
            return $response->withJson(['jsonrpc' => '2.0', 'id' => $r->{'id'}, 'result' => ['status' => 'Done']]);
        }
        return $response->withJson(['jsonrpc' => '2.0', 'id' => $r->{'id'}, 'error' => ["code" => 0, "message" => [$r]]]);
    }

    public static function getPurchaseHTML(): string
    {
        return '<div class="card-inner">
        <h4>XMR</h4>
        <form class="vmqpay" action="/user/payment/purchase/meowpay" method="get">
            <input class="form-control maxwidth-edit" id="price" name="price"  placeholder="输入充值金额后，点击下方图标" autofocus="autofocus" type="number" min="1" max="9999" step="0.01" required="required">
            <button class=" btn btn-flat waves-attach" id="btnSubmit" type="submit">
                <img src="https://meowpay.org/favicon.ico" height="50px" />
            </button>
        </form>
    </div>
    ';
    }
}

use Aws\Result;


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
    var $request;
    var $return_url;
    var $notify_url;

    function __construct(
        string $app_id,
        string $trade_no,
        string $currency_type = null,
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
    // PaymentResponse
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
