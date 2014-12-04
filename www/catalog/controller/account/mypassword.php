<?php
class ControllerAccountMyPassword extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/signup');
		$isActive2 = $this->model_account_signup->isActiveMod();
		$isActive1=$isActive2['enablemod'];
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/mypassword', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/password');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('account/signup');
		$this->data['isActive']=  $this->model_account_signup->isActiveMod();
		$this->data['modData']=  $this->model_account_signup->getModData();
		$modData1 = $this->model_account_signup->getModData();
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($modData1,$isActive1)) {
			$this->load->model('account/customer');
				
			$this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');
			 
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

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
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/mypassword', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
		);
			
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_password'] = $this->language->get('text_password');

		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		 
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}

		$this->data['action'] = $this->url->link('account/mypassword', '', 'SSL');

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/password.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/password.tpl';
		} else {
			$this->template = 'default/template/account/password.tpl';
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

	protected function validate($modData,$isActive) {
		if($isActive && $modData['pass_fix'] && (utf8_strlen($this->request->post['password']) != $modData['pass_fix'])){
			$this->error['password'] = $this->language->get('error_password');
		}else if($isActive && !$modData['pass_fix'] && $modData['pass_min'] &&  $modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < $modData['pass_min']) || (utf8_strlen($this->request->post['password']) > $modData['pass_max']))){
			$this->error['password'] = $this->language->get('error_password');
		}else if($isActive && !$modData['pass_min'] && !$modData['pass_fix'] && !$modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
			$this->error['password'] = $this->language->get('error_password');
		}else if(!$isActive  && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
