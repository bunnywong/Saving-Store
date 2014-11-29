<?php 
class ControllerAccountRegister extends Controller {
	private $error = array();
	      
  	public function index() {
		if ($this->customer->isLogged()) {
	  		$this->redirect($this->url->link('account/account', '', 'SSL'));
    	}

    	$this->language->load('account/register');    	
		
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
					
		$this->load->model('account/customer');
                $this->load->model('account/signup');
                $this->data['isActive']=  $this->model_account_signup->isActiveMod();
                $this->data['modData']=  $this->model_account_signup->getModData();
                $modData1 = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
                $isActive1=$isActive2['enablemod'];
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($modData1,$isActive1)) {
			$this->model_account_customer->addCustomer($this->request->post,$modData1,$isActive1);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);
			
			unset($this->session->data['guest']);
			
			// Default Shipping Address
			if ($this->config->get('config_tax_customer') == 'shipping') {
				if(false)$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				if(false)$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				if(false)$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
			}
			
			// Default Payment Address
			if ($this->config->get('config_tax_customer') == 'payment') {
				if(false)$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				if(false)$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
			}
							  	  
	  		$this->redirect($this->url->link('account/success'));
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
        	'text'      => $this->language->get('text_register'),
			'href'      => $this->url->link('account/register', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
						
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	
    	$this->language->load('account/xtensions');
    	$this->data['title_firstname'] = $this->language->get('title_firstname');
    	$this->data['title_lastname'] = $this->language->get('title_lastname');
    	$this->data['title_email'] = $this->language->get('title_email');
    	$this->data['title_telephone'] = $this->language->get('title_telephone');
    	$this->data['title_fax'] = $this->language->get('title_fax');
		$this->data['title_company'] = $this->language->get('title_company');		
		$this->data['title_company_id'] = $this->language->get('title_company_id');		
    	$this->data['title_address_1'] = $this->language->get('title_address_1');
    	$this->data['title_address_2'] = $this->language->get('title_address_2');
    	$this->data['title_postcode'] = $this->language->get('title_postcode');
    	$this->data['title_city'] = $this->language->get('title_city');    			
    	$this->data['title_password'] = $this->language->get('title_password');
    	$this->data['title_confirm'] = $this->language->get('title_confirm');
    	
		$this->data['button_continue'] = $this->language->get('button_continue');
    
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}		
	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
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
		
  		if (isset($this->error['company_id'])) {
			$this->data['error_company_id'] = $this->error['company_id'];
		} else {
			$this->data['error_company_id'] = '';
		}
		
  		if (isset($this->error['tax_id'])) {
			$this->data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$this->data['error_tax_id'] = '';
		}
								
  		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
    	$this->data['action'] = $this->url->link('account/register', '', 'SSL');
		
		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} else {
			$this->data['fax'] = '';
		}
		
		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} else {
			$this->data['company'] = '';
		}

		$this->load->model('account/customer_group');
		
		$this->data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->request->post['customer_group_id'])) {
    		$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
		
		// Company ID
		if (isset($this->request->post['company_id'])) {
    		$this->data['company_id'] = $this->request->post['company_id'];
		} else {
			$this->data['company_id'] = '';
		}
		
		// Tax ID
		if (isset($this->request->post['tax_id'])) {
    		$this->data['tax_id'] = $this->request->post['tax_id'];
		} else {
			$this->data['tax_id'] = '';
		}
						
		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (!$modData1['country_show'] && $modData1['def_country']) {
			$this->data['country_id'] = $modData1['def_country'];		
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id']; 	
		} elseif (!$modData1['state_show'] && $modData1['def_state']) {
			$this->data['zone_id'] = $modData1['def_state'];		
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
		} else {
      		$this->data['zone_id'] = '';
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
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
		
		if(!($this->request->server['REQUEST_METHOD'] == 'POST')){
			$this->data['newsletter'] = '1';
		} else if (isset($this->request->post['newsletter'])) {
    		$this->data['newsletter'] = $this->request->post['newsletter'];
		} else {
			$this->data['newsletter'] = '';
		}	

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/info', 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) {
      		$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = false;
		}
		
  	$this->model_account_customer->customInstall();
  	foreach ($this->model_account_customer->getCustomOptions() as $option) {
		if ( isset($this->error['optionVE'.$option['option_id']])) {
			$this->data['optionVE'.$option['option_id']] = $this->error['optionVE'.$option['option_id']];
		} else {
			$this->data['optionVE'.$option['option_id']] = '';
		}
		}
  	foreach ($this->model_account_customer->getCustomOptions() as $option) {
			if ($option['type'] != 'checkbox' && isset($this->request->post['option'.$option['option_id']])) {
				$this->data['optionV'.$option['option_id']] = $this->request->post['option'.$option['option_id']];
			}
			else if($option['type'] != 'checkbox' ) {
				$this->data['optionV'.$option['option_id']] = '';
			}
			if($option['type'] == 'checkbox'){
			foreach ($option['option_value'] as $option_value) {
			if (isset($this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])) {
				$this->data['optionV_O'.$option['option_id'].'C'.$option_value['option_value_id']] = $this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']];
			}else{
				$this->data['optionV_O'.$option['option_id'].'C'.$option_value['option_value_id']]='';
			}
					}
			}
		}
		
		$this->data['optionsP'] = $this->model_account_customer->getCustomOptions(1);
		$this->data['optionsA'] = $this->model_account_customer->getCustomOptions(2);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/register.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/register.tpl';
		} else {
			$this->template = 'default/template/account/register.tpl';
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
  	foreach ($this->model_account_customer->getCustomOptions() as $option) {
  		if($option['section']==1 || ($option['section']==2 && !$modData['show_address']))
			if($option['required']){if (($option['type'] == 'date' || $option['type'] == 'radio' || $option['type'] == 'select')  && (!isset($this->request->post['option'.$option['option_id']]) || empty($this->request->post['option'.$option['option_id']]))) {
				$this->error['optionVE'.$option['option_id']]  = $option['error'];
			}
  			if (( $option['type'] == 'text' || $option['type'] == 'textarea')  && (!isset($this->request->post['option'.$option['option_id']]) || 
  			empty($this->request->post['option'.$option['option_id']]))) {
				$this->error['optionVE'.$option['option_id']]  = $option['error'];
			}
			if (( $option['type'] == 'text' || $option['type'] == 'textarea')  && $option['max'] && $option['min'] && (utf8_strlen($this->request->post['option'.$option['option_id']]) < $option['min']
  			|| utf8_strlen($this->request->post['option'.$option['option_id']]) > $option['max']
  			)) {
				$this->error['optionVE'.$option['option_id']]  = $option['error'];
			}
			if ($option['type'] == 'checkbox' ){
				$flag=true;
				foreach ($option['option_value'] as $option_value) {
			   		if(isset( $this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']]) && !empty( $this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])) {
						$flag=false;
					}
				}
				if($flag){
					$this->error['optionVE'.$option['option_id']]  = $option['error'];
				}			
			}		
		}}
    	if ((!$isActive || ($isActive && $modData['f_name_req'] && $modData['f_name_show']))  && ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32))) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((!$isActive || ($isActive && $modData['l_name_req'] && $modData['l_name_show'])) && ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32))) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}
		
    	if ( $isActive && $modData['mob_req'] && $modData['mob_show'] && $modData['mob_fix'] && ((utf8_strlen($this->request->post['telephone']) != $modData['mob_fix']))){
            $this->error['telephone'] = $this->language->get('error_telephone');
        }else if ( $isActive && $modData['mob_req'] && $modData['mob_show'] && !$modData['mob_fix'] && $modData['mob_min'] && $modData['mob_max'] && ((utf8_strlen($this->request->post['telephone']) < $modData['mob_min']) || (utf8_strlen($this->request->post['telephone']) > $modData['mob_max']))){
            $this->error['telephone'] = $this->language->get('error_telephone');
        }else if ($isActive && $modData['mob_req'] && $modData['mob_show'] && !$modData['mob_max'] && !$modData['mob_fix'] && !$modData['mob_min'] && ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
        }else if (!$isActive &&  ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
		// Customer Group
		$this->load->model('account/customer_group');
		
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
		if ($customer_group) {	
			// Company ID
			if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
				$this->error['company_id'] = $this->language->get('error_company_id');
			}
			
			// Tax ID 
			if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
				$this->error['tax_id'] = $this->language->get('error_tax_id');
			}						
		}
		
    	if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['address1_req'] && $modData['address1_show'])) && ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128))) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((!$isActive || ($isActive  && !$modData['show_address'] && $modData['city_req'] && $modData['city_show'])) && ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128))) {
      		$this->error['city'] = $this->language->get('error_city');
    	}

		$this->load->model('localisation/country');
		if(!$isActive || ($isActive  && !$modData['show_address'] && $modData['pin_req'] && $modData['pin_show'] ))
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ((!$isActive || ($isActive  && !$modData['show_address'] && $modData['pin_req'] && $modData['pin_show'] )) && $country_info) {
			if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
			
			// VAT Validation
			$this->load->helper('vat');
			
			if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
				$this->error['tax_id'] = $this->language->get('error_vat');
			}
		}

    	if ((!$isActive || ($isActive  && !$modData['show_address'] && $modData['country_req'] && $modData['country_show'])) && $this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ((!$isActive || ($isActive  && !$modData['show_address'] && $modData['state_req'] && $modData['state_show'] )) && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}

        if($isActive && $modData['pass_fix'] && (utf8_strlen($this->request->post['password']) != $modData['pass_fix'])){
            $this->error['password'] = $this->language->get('error_password');
        }else if($isActive && !$modData['pass_fix'] && $modData['pass_min'] &&  $modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < $modData['pass_min']) || (utf8_strlen($this->request->post['password']) > $modData['pass_max']))){
           $this->error['password'] = $this->language->get('error_password');
        }else if($isActive && !$modData['pass_min'] && !$modData['pass_fix'] && !$modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
            $this->error['password'] = $this->language->get('error_password');
        }else if(!$isActive  && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ((!$isActive || ($isActive && $modData['passconf_show'] )) && $this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}
		
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			
			if ($information_info && !isset($this->request->post['agree'])) {
      			$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}	
}
?>