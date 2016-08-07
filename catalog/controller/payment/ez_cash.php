<?php
class ControllerPaymentEzCash extends Controller {
    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/success');

       $merchantID = $this->config->get('ez_cash_merchant_id');
       $publicKey = $this->config->get('ez_cash_public_key');

        $data['orderID'] = $this->session->data['order_id'];

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $total = number_format($order_info['total'], 2, '.', '');
        $sensitiveData = $merchantID.'|'.$this->session->data['order_id'].'|'.$total.'|'.$this->url->link('payment/ez_cash/callback');
        print_r($order_info);
        print_r($merchantID);
        print_r($total);
        print_r($sensitiveData);

        echo $publicKey;
        echo $sensitiveData;

        $encrypted = '';
        if (!openssl_public_encrypt($sensitiveData, $encrypted, $publicKey))
            die('Failed to encrypt data');

        $invoice = base64_encode($encrypted);

        print_r($invoice);
        $data['invoice'] = $invoice;

        return $this->load->view('payment/ez_cash', $data);
    }

    public function confirm() {
        if ($this->session->data['payment_method']['code'] == 'ez_cash') {
            $this->load->model('checkout/order');

            $source = "";
            $reference = "";
            if (isset($this->request->post['ez_cash_source'])) {
                $source = $this->request->post['ez_cash_source'];
            }

            if (isset($this->request->post['ez_cash_reference'])) {
                $reference = $this->request->post['ez_cash_reference'];
            }

            $comment = "Confirmed order without payment";
            if ($reference !== '') {
                $comment = $source . " payment reference: ". $reference;
            }

            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ez_cash_order_status_id'), $comment, true);
        }
    }

    public function callback() {
		$this->load->model('payment/ez_cash');
		$this->load->language('payment/ez_cash');
		$encrypted = $this->request->post['merchantReciept'];
		$privateKey = $this->config->get('ez_cash_private_key');

		$encrypted = base64_decode($encrypted); //decode the encrypted query string
		$decrypted = '';
		if (!openssl_private_decrypt($encrypted, $decrypted, $privateKey)) {
			$this->session->data['failure_text'] = sprintf($this->language->get('text_failure_message_2'), $this->url->link('information/contact'));
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		} else {
			echo "Decrypted value: " . $decrypted;
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