<?php

class ModelExtensionPaymentEzCash extends Model {

    public function install() {
		$this->db->query("
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ez_cash_resp` (
		  `transaction_id` varchar(128) NOT NULL,
		  `status_code` varchar(128) NOT NULL,
		  `status_description` varchar(128) NOT NULL,
		  `amount` varchar(20) NOT NULL,
		  `reference_id` varchar(20) NOT NULL
        )ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ez_cash_resp`;");
    }
}
