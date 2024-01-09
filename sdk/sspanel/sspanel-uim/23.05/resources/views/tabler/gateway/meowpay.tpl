<div class="card-inner">
    <h4>喵支付： XMR TRX USDT</h4>
    <form action="/user/payment/purchase/meowpay" method="post">
        <input hidden id="price" name="price" value="{$invoice->price}">
        <input hidden id="invoice_id" name="invoice_id" value="{$invoice->id}">
        <button class=" btn btn-flat waves-attach" id="btnSubmit" type="submit">
            <img src="https://static.meowpay.org/images/svg/xmr.svg" height="50px" />
        </button>
        <button class=" btn btn-flat waves-attach" id="btnSubmit" type="submit">
            <img src="https://static.meowpay.org/images/svg/usdt.svg" height="50px" />
        </button>
        <button class=" btn btn-flat waves-attach" id="btnSubmit" type="submit">
            <img src="https://static.meowpay.org/images/svg/trx.svg" height="50px" />
        </button>
    </form>
</div>