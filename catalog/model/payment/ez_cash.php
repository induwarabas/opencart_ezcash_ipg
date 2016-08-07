<?php
class ModelPaymentEzCash extends Model {
    public function getMethod($address, $total) {
        $this->load->language('payment/ez_cash');
		$terms = '';
		if ($this->config->get('ez_cash_mode') == "Test") {
			$terms = 'Test';
		}
        $method_data = array(
            'code'     => 'ez_cash',
            'terms'     => $terms,
            'title'    => $this->language->get('text_title'),
            'sort_order' => $this->config->get('custom_sort_order')
        );

        return $method_data;
    }

	public function insertTransaction($transID, $statusCode,$description,$amount, $reference) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ez_cash_resp` (`transaction_id`,`status_code`,`status_description`,`amount`,`reference_id`) VALUES ("
			."'".$transID."','".$statusCode."','".$description."','".$amount."','".$reference."')");
	}
}