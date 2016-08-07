<?php
class ControllerPaymentEzCash extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('payment/ez_cash');
        $this->document->setTitle('eZ-Cash (by Dialog, Etisalat, Hutch)');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_setting_setting->editSetting('ez_cash', $this->request->post);
            $this->session->data['success'] = 'Saved.';
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_save'] = $this->language->get('text_button_save');
        $data['button_cancel'] = $this->language->get('text_button_cancel');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $data['entry_public_key'] = $this->language->get('entry_public_key');
        $data['entry_private_key'] = $this->language->get('entry_private_key');

        $data['action'] = $this->url->link('payment/ez_cash', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['ez_cash_merchant_id'])) {
            $data['ez_cash_merchant_id'] = $this->request->post['ez_cash_merchant_id'];
        } else {
            $data['ez_cash_merchant_id'] = $this->config->get('ez_cash_merchant_id');
        }

        if (isset($this->request->post['ez_cash_public_key'])) {
            $data['ez_cash_public_key'] = $this->request->post['ez_cash_public_key'];
        } else {
            $data['ez_cash_public_key'] = $this->config->get('ez_cash_public_key');
        }

        if (isset($this->request->post['ez_cash_private_key'])) {
            $data['ez_cash_private_key'] = $this->request->post['ez_cash_private_key'];
        } else {
            $data['ez_cash_private_key'] = $this->config->get('ez_cash_private_key');
        }

        if (isset($this->request->post['ez_cash_status'])) {
            $data['ez_cash_status'] = $this->request->post['ez_cash_status'];
        } else {
            $data['ez_cash_status'] = $this->config->get('ez_cash_status');
        }

        if (isset($this->request->post['ez_cash_order_status_id'])) {
            $data['ez_cash_order_status_id'] = $this->request->post['ez_cash_order_status_id'];
        } else {
            $data['ez_cash_order_status_id'] = $this->config->get('ez_cash_order_status_id');
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->request->post['ez_cash_sort_order'])) {
            $data['ez_cash_sort_order'] = $this->request->post['ez_cash_sort_order'];
        } else {
            $data['ez_cash_sort_order'] = $this->config->get('ez_cash_sort_order');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/ez_cash', 'token=' . $this->session->data['token'], true)
        );

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/ez_cash', $data));
    }

    public function install() {
        $this->load->model('payment/ez_cash');
        $this->model_payment_ez_cash->install();
    }

    public function uninstall() {
        $this->load->model('payment/ez_cash');
        $this->model_payment_ez_cash->uninstall();
    }
}