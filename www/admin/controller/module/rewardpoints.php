<?php
class ControllerModuleRewardPoints extends Controller {
	private $error = array(); 
	
	private function loadSetting($setting, $default = 0) {
		return isset($this->request->post[$setting]) ? $this->request->post[$setting] : (($this->config->get($setting)) ? $this->config->get($setting) : $default);
	}

	public function index() {   
		$this->load->language('module/rewardpoints');
		$this->load->model('setting/setting');
		$this->load->model('module/rewardpoints');
		$this->data = $this->load->language('module/rewardpoints');

		$this->document->setTitle($this->language->get('heading_title'));
		
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('rewardpoints', $this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		//LOAD GLOBAL LANGUAGE DATA
		$this->data['text_yes']      = $this->language->get('text_yes');
		$this->data['text_no']       = $this->language->get('text_no');
		$this->data['text_enabled']  = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
 		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

  		$this->data['breadcrumbs']   = array();
   		$this->data['breadcrumbs'][] = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), 'separator' => false);
   		$this->data['breadcrumbs'][] = array('text' => $this->language->get('text_module'), 'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'), 'separator' => ' :: ');
   		$this->data['breadcrumbs'][] = array('text' => $this->language->get('heading_title'), 'href' => $this->url->link('module/rewardpoints', 'token=' . $this->session->data['token'], 'SSL'), 'separator' => ' :: ');
		
		//SETUP BUTTONS / ACTIONS
		$this->data['button_email']  = sprintf($this->language->get('button_send_reminders'), $this->model_module_rewardpoints->getTotalPendingReminders());
		$this->data['button_save']   = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['email']         = $this->url->link('module/rewardpoints/sendreminders', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action']        = $this->url->link('module/rewardpoints', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']        = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// LOAD SETTINGS FROM SETTINGS DATABASE
		$this->data['rewardpoints_auto_checkout']           = $this->loadSetting('rewardpoints_auto_checkout');
		$this->data['rewardpoints_purchase_url']            = $this->loadSetting('rewardpoints_purchase_url', '');
		$this->data['rewardpoints_completed_orders']        = $this->loadSetting('rewardpoints_completed_orders', array());
		$this->data['rewardpoints_cancelled_orders']        = $this->loadSetting('rewardpoints_cancelled_orders', array());
		
		// REWARD POINTS DISPLAY SETTINGS
		$this->data['rewardpoints_currency_mode']           = $this->loadSetting('rewardpoints_currency_mode');
		$this->data['rewardpoints_currency_prefix']         = $this->loadSetting('rewardpoints_currency_prefix', '');
		$this->data['rewardpoints_currency_suffix']         = $this->loadSetting('rewardpoints_currency_suffix', '');
		$this->data['rewardpoints_hidden_zero']             = $this->loadSetting('rewardpoints_hidden_zero');
		$this->data['rewardpoints_subtext_display']         = $this->loadSetting('rewardpoints_subtext_display');
		$this->data['rewardpoints_pop_notification']        = $this->loadSetting('rewardpoints_pop_notification',1);
		
		// REWARD POINT BONUSES
		$this->data['rewardpoints_registration_bonus']      = $this->loadSetting('rewardpoints_registration_bonus');
		$this->data['rewardpoints_newsletter_bonus']        = $this->loadSetting('rewardpoints_newsletter_bonus');
		$this->data['rewardpoints_newsletter_unsubscribe']  = $this->loadSetting('rewardpoints_newsletter_unsubscribe');
		$this->data['rewardpoints_order_bonus']             = $this->loadSetting('rewardpoints_order_bonus');
		$this->data['rewardpoints_review_bonus']            = $this->loadSetting('rewardpoints_review_bonus');
		$this->data['rewardpoints_review_limit']            = $this->loadSetting('rewardpoints_review_limit');
		$this->data['rewardpoints_review_auto_approve']     = $this->loadSetting('rewardpoints_review_auto_approve');
		
		// EMAIL REMINDER SETTINGS 
		$this->data['rewardpoints_email_content']           = $this->loadSetting('rewardpoints_email_content', array());
		$this->data['rewardpoints_email_reminder_enabled']  = $this->loadSetting('rewardpoints_email_reminder_enabled');
		$this->data['rewardpoints_email_status']            = $this->loadSetting('rewardpoints_email_status', array());
		$this->data['rewardpoints_email_date']              = $this->loadSetting('rewardpoints_email_date');
		$this->data['rewardpoints_email_days']              = $this->loadSetting('rewardpoints_email_days');
		$this->data['rewardpoints_email_test']              = $this->loadSetting('rewardpoints_email_test', '');
		$this->data['rewardpoints_email_cron']              = $this->loadSetting('rewardpoints_email_cron', 'NOT YET RUN');
		
		
		//ORDER STATUS LOAD
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
 
		//LOAD LANGUAGES
		$this->load->model('localisation/language');		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		// RENDER PAGE 
		$this->template = 'module/rewardpoints.tpl';
		$this->children = array('common/header', 'common/footer');
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/rewardpoints')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return (!$this->error) ? true : false;
	}
	
	public function cronReminders() {
		$this->load->language('module/rewardpoints');
		$this->load->model('module/rewardpoints');

		$entries_processed = $this->model_module_rewardpoints->sendEmailReminders();
		$result =  sprintf($this->language->get('text_success_cron'), $entries_processed, date("Y-m-d H:i:s"));
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $result . "' WHERE `key` = 'rewardpoints_email_cron'");

	}
	
	public function sendReminders() {
		$this->load->language('module/rewardpoints');
		$this->load->model('module/rewardpoints');

		$entries_processed = $this->model_module_rewardpoints->sendEmailReminders();
		
		$this->session->data['success'] = sprintf($this->language->get('text_success_email'), $entries_processed);
						
		$this->redirect($this->url->link('module/rewardpoints', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		$this->response->setOutput($this->render());
		
	}

	public function sendTestEmail() {
		$this->load->language('module/rewardpoints');
		$this->load->model('module/rewardpoints');
		$this->load->model('setting/setting');


		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('rewardpoints', $this->request->post);		
		}
		
		// Re-Load Settings
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
		 
		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$this->config->set($setting['key'], $setting['value']);
			} else {
				$this->config->set($setting['key'], unserialize($setting['value']));
			}
		}
			
		$entries_processed = $this->model_module_rewardpoints->sendEmailTest();
		$this->session->data['success'] = sprintf($this->language->get('text_success_email_test'), $entries_processed);
		$this->redirect($this->url->link('module/rewardpoints', 'token=' . $this->session->data['token'], 'SSL'));
		$this->response->setOutput($this->render());
		
	}
	
	public function install() {
			$changes = array();
			$errors = array();
			
			
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_reward` CHANGE `points` `points` DECIMAL(15, 4) NOT NULL DEFAULT '0';");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_reward` CHANGE `points` `points` DECIMAL(15, 4) NOT NULL DEFAULT '0';");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` CHANGE `points` `points` DECIMAL(15, 4) NOT NULL DEFAULT '0';");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `points` `points` DECIMAL(15, 4) NOT NULL DEFAULT '0';");
			$changes[] = '\'<i>points</i>\' field has been altered to support decimals in the the \'<i>customer_reward</i>\' database table.';
			$changes[] = '\'<i>points</i>\' field has been altered to support decimals in the the \'<i>product_reward</i>\' database table.';
			$changes[] = '\'<i>points</i>\' field has been altered to support decimals in the the \'<i>product_option_value</i>\' database table.';
			$changes[] = '\'<i>points</i>\' field has been altered to support decimals in the the \'<i>product</i>\' database table.';
			
			//PRODUCT REVIEW REMINDER IS DISPATCHED IN ORDER TABLE
			$query = $this->db->query("DESC `" . DB_PREFIX . "order` product_review_reminder");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD product_review_reminder INT(1) NOT NULL DEFAULT '0'");
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET product_review_reminder = '1'");
				$changes[] = '\'<i>product_review_reminder</i>\' field has been added to the \'<i>customer_reward</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>product_review_reminder</i>\' field already existing in \'<i>customer_reward</i>\' database table.';
			}
			
			//CUSTOMER REWARD REASON CODE ID
			$query = $this->db->query("DESC " . DB_PREFIX . "customer_reward reason_code");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_reward` ADD reason_code VARCHAR(25) NOT NULL DEFAULT ''");
				$changes[] = '\'<i>reason_code</i>\' field has been added to the \'<i>customer_reward</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>reason_code</i>\' field already existing in \'<i>customer_reward</i>\' database table.';
			}
			
			//CUSTOMER REWARD REASON CODE ID
			$query = $this->db->query("DESC " . DB_PREFIX . "customer_reward alt_id");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_reward` ADD alt_id INT(11) NOT NULL DEFAULT '0'");
				$changes[] = '\'<i>alt_id</i>\' field has been added to the \'<i>customer_reward</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>alt_id</i>\' field already existing in \'<i>customer_reward</i>\' database table.';
			}
			
			//PRODUCT DISCOUNTS POINTS
			$query = $this->db->query("DESC " . DB_PREFIX . "product_discount points");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD points DECIMAL(15,4) NOT NULL DEFAULT '0'");
				$changes[] = '\'<i>points</i>\' field has been added to the \'<i>product_discount</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>points</i>\' field already existing in \'<i>product_discount</i>\' database table.';
			}

			//POINT ONLY PURCHASE ON PRODUCT TABLE
			$query = $this->db->query("DESC " . DB_PREFIX . "product points_only_purchase");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD points_only_purchase INT(1) NOT NULL DEFAULT '0'");
				$changes[] = '\'<i>points_only_purchase</i>\' field has been added to the \'<i>product</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>points_only_purchase</i>\' field already existing in \'<i>product</i>\' database table.';
			}
			
			//POINTS_SPECIAL ON PRODUCT_SPECIAL TABLE
			$query = $this->db->query("DESC " . DB_PREFIX . "product_special points_special");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD points_special DECIMAL(15,4) NOT NULL DEFAULT '0' AFTER price");
				$changes[] = '\'<i>points_special</i>\' field has been added to the \'<i>product_special</i>\' database table.';
			} else {
				$changes[] = '<strong>[no change]</strong> \'<i>points_special</i>\' field already existing in \'<i>product_special</i>\' database table.';
			}

			$status = "<strong>Reward Points Extended Installation</strong><br />";
			if ($changes) {
				$status .= "The following changes have been made:";
				$status .= "<ul>";
				foreach ($changes as $change) {
					$status .= '<li>' . $change . '</li>';
				}
				$status .= "</ul>";
			}
			$this->session->data['success'] = $status;
	}

}
?>