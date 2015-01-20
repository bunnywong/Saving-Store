<?php
class ControllerPaymentCod extends Controller {
	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cod.tpl';
		} else {
			$this->template = 'default/template/payment/cod.tpl';
		}

		$this->render();
	}

	public function confirm() {
		$this->load->model('checkout/order');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cod_order_status_id'));

		// Clear: coupon_in_process
		unset($this->session->data['coupon_in_process']);

		// Apply old cart(without coupon)
		unset($this->session->data['cart']);
		$this->session->data['cart'] = $this->session->data['temp_cart'];
		unset($this->session->data['temp_cart']);

		// Apply coupon
		$this->session->data['coupon'] = $this->session->data['next_coupon'];

		// Redirect to checkout step // TEMP ***
		$this->redirect($this->url->link('checkout/cart'));	// DEBUG
	}
}
?>