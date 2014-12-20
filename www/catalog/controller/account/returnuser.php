<?php
class ControllerAccountReturnuser extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->document->setTitle('舊網站用戶');
		$this->language->load('account/forgotten');
		$this->load->model('account/customer');

		// --------------------------------------------------
		// Action after page POST

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$email = $this->request->post['email'];
			$pwd = $this->request->post['password'];

			$subject = '歡迎回來 SavingStore.com.hk';

			$custom =  $this->model_account_customer->getCustomerByEmail($email);

			$message  =  $custom['firstname']. "\n\n\n";

			$message  .= '請點擊以下連結啟動你的帳戶'. "\n\n";
			$message  .= $this->config->get('config_url').'index.php?route=account/returnuser&email='.base64_encode(base64_encode($email)).'&pwd='.base64_encode(base64_encode($pwd)). "\n\n\n";

			$message .= '謝謝!'."\n";
			$message .= 'SavingStore.com.hk'."\n";
//			$message .= '<img src="http://greenmap.hk/image/data/logo.png" style="max-width: 180px; height: auto;">';	// todo IMG

			// ----- ----- ----- ----- -----

			$mail 				= new Mail();
			$mail->protocol 	= $this->config->get('config_mail_protocol');
			$mail->parameter 	= $this->config->get('config_mail_parameter');
			$mail->hostname 	= $this->config->get('config_smtp_host');
			$mail->username 	= $this->config->get('config_smtp_username');
			$mail->password 	= $this->config->get('config_smtp_password');
			$mail->port 		= $this->config->get('config_smtp_port');
			$mail->timeout 		= $this->config->get('config_smtp_timeout');

			$mail->setTo($email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$this->session->data['success'] = '成功： 啟動賬戶發送到您的郵箱，請及時查收！';

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		// --------------------------------------------------
		// Bread Crumbs

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => '舊網站用戶',
			'href'      => $this->url->link('account/returnuser', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
		);

		// --------------------------------------------------
		// Text echo

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		// --------------------------------------------------
		// My Script - Do change pwd into DB

		if ( isset( $this->request->get['email']) && isset( $this->request->get['pwd']) ) {

			$email 	= $this->request->get['email'];
			$pwd 	= $this->request->get['pwd'];

			// DEBUG
			// Email 	: bWVAYnVubnl3b25nLmNvbQ==	(me@bunnywong.com)
			// Pwd 		: YnVubnlzb24=				(bunnyson)
			/*$this->data['error_warning']   = base64_decode(base64_decode($email));
			$this->data['error_warning']   .= ' / ';
			$this->data['error_warning']   .= base64_decode(base64_decode($pwd));*/

			// Decode
			$email 	= base64_decode(base64_decode($email));
			$pwd 	= base64_decode(base64_decode($pwd));

			// Check is valid email a/c
			if( count($this->model_account_customer->getCustomerByEmail($email)) ){
				// Set 1st return user IP into DB
				$this->model_account_customer->editIp($email);

				// Set pwd into DB
				$this->model_account_customer->editPassword($email, $pwd);

				// Next page(Login) event
				$this->session->data['success'] =  '你的帳戶已被啟動';
				$this->redirect($this->url->link('account/login', '', 'SSL'));
			}else{
				$this->error['warning'] = '連結參數錯誤，請檢查電子郵件';
			}
		}
		// --------------------------------------------------
		// Show warning

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = $this->url->link('account/returnuser', '', 'SSL');

		$this->data['back'] = $this->url->link('account/login', '', 'SSL');

		// --------------------------------------------------
		// Load Teamplate

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/return_customer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/return_customer.tpl';
		} else {
			$this->template = 'default/template/account/return_customer.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	// --------------------------------------------------

	protected function validate() {

		$custom =  $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		$ip  	=  $custom['ip'];

		if (!isset($this->request->post['email'])) {	// Blank email
			$this->error['warning'] = $this->language->get('error_email');

		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']) ) {			// Not empty user && Not 2nd return
			$this->error['warning'] = $this->language->get('error_email');

		} elseif( $this->request->post['password'] != $this->request->post['password2'] ){
			$this->error['warning'] = '確認密碼不符';

		} elseif( $ip != '' ){
			$this->error['warning'] = '帳戶已經啟動過了，請於左邊登入。如忘記密碼，請<a href="/index.php?route=account/forgotten"><strong>按此</strong></a>';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>