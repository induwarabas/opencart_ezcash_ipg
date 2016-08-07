<?php
class ControllerPaymentEzCash extends Controller {
    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

		$merchantID = "TESTMERCHANT";


		$publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCW8KV72IMdhEuuEks4FXTiLU2o
bIpTNIpqhjgiUhtjW4Si8cKLoT7RThyOvUadsgYWejLg2i0BVz+QC6F7pilEfaVS
L/UgGNeNd/m5o/VoX9+caAIyu/n8gBL5JX6asxhjH3FtvCRkT+AgtTY1Kpjb1Btp
1m3mtqHh6+fsIlpH/wIDAQAB
-----END PUBLIC KEY-----
EOD;
		if ($this->config->get('ez_cash_mode') == "Live") {
			$publicKey = $this->config->get('ez_cash_public_key');
			$merchantID = $this->config->get('ez_cash_merchant_id');
		}
        $data['orderID'] = $this->session->data['order_id'];

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $total = number_format($order_info['total'], 2, '.', '');
        $sensitiveData = $merchantID.'|'.$this->session->data['order_id'].'|'.$total.'|'.$this->url->link('payment/ez_cash/callback');
        $encrypted = '';
        if (!openssl_public_encrypt($sensitiveData, $encrypted, $publicKey))
            die('Failed to encrypt data');

        $invoice = base64_encode($encrypted);

        $data['invoice'] = $invoice;

        return $this->load->view('payment/ez_cash', $data);
    }

    public function callback() {
		$this->load->model('payment/ez_cash');
		$this->load->language('payment/ez_cash');
		$encrypted = $this->request->post['merchantReciept'];
		$privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAJuIUgSzNuWm3US8
0brZr/5cMSPue9f0IwUrEhka1gLlC4uQon6QjQem4TWQ8anoMKYwfYgRnCGQsbrT
KwOApwTA4Bt6dg9jKXlIE6rXqqO6g2C/uD+G2p+W4k0ZI1isuqqjjkup5ZPkNaeW
R9/961Qx3CyrWDk6n0OkzDJ6UNzLAgMBAAECgYEAh+/dv73jfVUaj7l4lZct+2MY
kA8grt7yvNGoP8j0xBLsxE7ltzkgClARBoBot9f4rUg0b3j0vWF59ZAbSDRpxJ2U
BfWEtlXWvN1V051KnKaOqE8TOkGK0PVWcc6P0JhPrbmOu9hhAN3dMu+jd7ABFKgC
4b8EIlHA8bl8po8gwAECQQDliMBTAzzyhB55FMW/pVGq9TBo2oXQsyNOjEO+rZNJ
zIwJzFrFhvuvFj7/7FekDAKmWgqpuOIk0NSYfHCR54FLAkEArXc7pdPgn386ikOc
Nn3Eils1WuP5+evoZw01he4NSZ1uXNkoNTAk8OmPJPz3PrtB6l3DUh1U/DEZjIiI
7z5igQJAFXvFNH/bFn/TMlYFZDie+jdUvpulZrE9nr52IMSyQngIq2obHN3TdMHK
R73hPhN5tAQ9d0E8uWFqZJNRHfbjHQJASY7pNV3Ov/QE0ALxqE3W3VDmJD/OjkOS
jriUPNIAwnnHBgp0OXHMCHkSYX4AHpLr1cWjARw9IKB1lBmF7+YFgQJAFqUgYj11
ioyuSf/CSotPIC7YyNEnr+TK2Ym0N/EWzqNXoOCDxDTgoWLQxM3Nfr65tWtV2097
BjCbFfbui/IyUw==
-----END PRIVATE KEY-----
EOD;
		if ($this->config->get('ez_cash_mode') == "Live") {
			$privateKey = $this->config->get('ez_cash_private_key');
		}

		$encrypted = base64_decode($encrypted); //decode the encrypted query string
		$decrypted = '';
		if (!openssl_private_decrypt($encrypted, $decrypted, $privateKey)) {
			$this->session->data['failure_text'] = sprintf($this->language->get('text_failure_message_2'), $this->url->link('information/contact'));
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		} else {
			$info = explode("|", $decrypted."||||||");

			$this->model_payment_ez_cash->insertTransaction($info[0],$info[1],$info[2],$info[3],$info[5]);
			if ($info[1] == 2) {
				$comment = "Payment done via ezCash internet payment gateway with ezCash reference number ".$info[5];
				$this->model_checkout_order->addOrderHistory($info[0], $this->config->get('ez_cash_order_status_id'), $comment,true);
				$this->response->redirect($this->url->link('checkout/success', '', true));
			} else {
				$this->session->data['failure_text'] = sprintf($this->language->get('text_failure_message'), $info[2], $this->url->link('information/contact'));
				$this->response->redirect($this->url->link('checkout/failure', '', true));
			}
		}
    }
}
?>