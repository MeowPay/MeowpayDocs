<?php

declare(strict_types=1);
// 
require_once("../../../init.php");
require_once("../../../includes/functions.php");
require_once("../../../includes/gatewayfunctions.php");
require_once("../../../includes/invoicefunctions.php");
// logModuleCall('MeowPay', 'notify', '', http_build_query($_POST));
$GATEWAY = getGatewayVariables('MeowPay');
if (!$GATEWAY["type"]) {
    die("Module Not Activated"); # Checks gateway module is active before accepting callback
}
$res = json_decode(file_get_contents("php://input"),True);
// $params = $HTTP_RAW_POST_DATA['params'];
$params = $res['params'];
$app_id = (string)$params['app_id'];

if ($app_id != $GATEWAY['app_id']) {
    die("$app_id" . "1");
} else {
    $invoiceId = (string)$params['trade_no'];
    $transId = (string)$params['payment_id'];
    $invoice = \Illuminate\Database\Capsule\Manager::table('tblinvoices')->where('id', $invoiceId)->first();
    $paymentAmount = $invoice->total;
    $feeAmount = 0;
}
checkCbTransID($transId);
addInvoicePayment($invoiceId, $transId, $paymentAmount, $feeAmount, 'MeowPay');
logTransaction($GATEWAY["name"], $res, "Successful-A");
die(json_encode([
    'jsonrpc' => '2.0', 'id' => $res['id'], 'result' => ['status' => 'Done']
]));
