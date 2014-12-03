<?php 
class ControllerCheckoutRegister extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');
                $this->load->model('account/signup');
                $this->data['isActive']=  $this->model_account_signup->isActiveMod();
                $this->data['modData']=  $this->model_account_signup->getModData();
                $modData1 = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
                $isActive1=$isActive2['enablemod'];
		
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_your_password'] = $this->language->get('text_your_password');
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
		$this->data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		
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

		$this->data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');
			
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		
		if (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
		
  	 	if (!$modData1['country_show'] && $modData1['def_country']) {
			$this->data['country_id'] = $modData1['def_country'];		
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}
		
    	if (!$modData1['state_show'] && $modData1['def_state']) {
			$this->data['zone_id'] = $modData1['def_state'];		
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
		} else {
      		$this->data['zone_id'] = '';
    	}
				
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

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
		
  	$this->load->model('account/customer');
  	$this->model_account_customer->customInstall();
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
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['optionsP'] = $this->model_account_customer->getCustomOptions(1);
		$this->data['optionsA'] = $this->model_account_customer->getCustomOptions(2);
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
			
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/register.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/register.tpl';
		} else {
			$this->template = 'default/template/checkout/register.tpl';
		}
		
		$this->response->setOutput($this->render());		
  	}
	
	public function validate() {
		$this->language->load('checkout/checkout');
		
		$this->load->model('account/address');
		$this->load->model('account/customer');
                $this->load->model('account/signup');
                $modData = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
                $isActive=$isActive2['enablemod'];
		
		$json = array();
		
		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');			
		}
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}				
		}
						
		if (!$json) {					
			$this->model_account_customer->customInstall();
  		foreach ($this->model_account_customer->getCustomOptions() as $option) {
  			if($option['section']==1 || ($option['section']==2 && !$modData['show_address']))
			if($option['required']){if (($option['type'] == 'date' || $option['type'] == 'radio' || $option['type'] == 'select')  && (!isset($this->request->post['option'.$option['option_id']]) || empty($this->request->post['option'.$option['option_id']]))) {
				$json['error']['optionVE'.$option['option_id']]  = $option['error'];
			}
  			if (( $option['type'] == 'text' || $option['type'] == 'textarea')  && (!isset($this->request->post['option'.$option['option_id']]) || 
  			empty($this->request->post['option'.$option['option_id']]))) {
				$json['error']['optionVE'.$option['option_id']]  = $option['error'];
			}
			if (( $option['type'] == 'text' || $option['type'] == 'textarea')  && $option['max'] && $option['min'] && (utf8_strlen($this->request->post['option'.$option['option_id']]) < $option['min']
  			|| utf8_strlen($this->request->post['option'.$option['option_id']]) > $option['max']
  			)) {
				$json['error']['optionVE'.$option['option_id']]  = $option['error'];
			}
			if ($option['type'] == 'checkbox' ){
				$flag=true;
				foreach ($option['option_value'] as $option_value) {
			   		if(isset( $this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']]) && !empty( $this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])) {
						$flag=false;
					}
				}
				if($flag){
					$json['error']['optionVE'.$option['option_id']]  = $option['error'];
				}			
			}		
		}}				
			if ((!$isActive || ($isActive && $modData['f_name_req'] && $modData['f_name_show']))  && ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32))) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
		
			if ((!$isActive || ($isActive && $modData['l_name_req'] && $modData['l_name_show'])) && ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32))) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
		
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}
	
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
			
		if ( $isActive && $modData['mob_show'] && $modData['mob_req'] && $modData['mob_fix'] && ((utf8_strlen($this->request->post['telephone']) != $modData['mob_fix']))){
            $json['error']['telephone'] = $this->language->get('error_telephone');
        }else if ( $isActive  && $modData['mob_show'] && $modData['mob_req'] && !$modData['mob_fix'] && $modData['mob_min'] && $modData['mob_max'] && ((utf8_strlen($this->request->post['telephone']) < $modData['mob_min']) || (utf8_strlen($this->request->post['telephone']) > $modData['mob_max']))){
            $json['error']['telephone'] = $this->language->get('error_telephone');
        }else if ($isActive && $modData['mob_show'] && $modData['mob_req'] && !$modData['mob_max'] && !$modData['mob_fix'] && !$modData['mob_min'] && ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
      		$json['error']['telephone'] = $this->language->get('error_telephone');
        }else if (!$isActive &&  ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32))) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
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
					$json['error']['company_id'] = $this->language->get('error_company_id');
				}
				
				// Tax ID
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
					$json['error']['tax_id'] = $this->language->get('error_tax_id');
				}						
			}
			
			if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['address1_req'] && $modData['address1_show'])) && ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128))) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
	
			if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['city_req'] && $modData['city_show'])) && ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128))) {
				$json['error']['city'] = $this->language->get('error_city');
			}
	
			$this->load->model('localisation/country');
			if(!$isActive || ($isActive && !$modData['show_address'] && $modData['pin_req'] && $modData['pin_show'] ))
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['pin_req'] && $modData['pin_show'] )) && $country_info) {
				if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
				 
				// VAT Validation
				$this->load->helper('vat');
				
				if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
					$json['error']['tax_id'] = $this->language->get('error_vat');
				}				
			}
	
			if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['country_req'] && $modData['country_show'])) && $this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if ((!$isActive || ($isActive && !$modData['show_address'] && $modData['state_req'] && $modData['state_show'] )) && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
				$json['error']['zone'] = $this->language->get('error_zone');
			}
	
			if($isActive && $modData['pass_fix'] && (utf8_strlen($this->request->post['password']) != $modData['pass_fix'])){
            $json['error']['password'] = $this->language->get('error_password');
        }else if($isActive && !$modData['pass_fix'] && $modData['pass_min'] &&  $modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < $modData['pass_min']) || (utf8_strlen($this->request->post['password']) > $modData['pass_max']))){
           $json['error']['password'] = $this->language->get('error_password');
        }else if($isActive && !$modData['pass_min'] && !$modData['pass_fix'] && !$modData['pass_max'] && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
            $json['error']['password'] = $this->language->get('error_password');
        }else if(!$isActive  && ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20))){
				$json['error']['password'] = $this->language->get('error_password');
			}
	
			if ((!$isActive || ($isActive && $modData['passconf_show'] )) && $this->request->post['confirm'] != $this->request->post['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
			
			if ($this->config->get('config_account_id')) {
				$this->load->model('catalog/information');
				
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
				
				if ($information_info && !isset($this->request->post['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}
		
		if (!$json) {
			$this->model_account_customer->addCustomer($this->request->post,$modData,$isActive);
			
			$this->session->data['account'] = 'register';
			
			if ($customer_group && !$customer_group['approval']) {
				$this->customer->login($this->request->post['email'], $this->request->post['password']);
				
				$this->session->data['payment_address_id'] = $this->customer->getAddressId();
				if(isset($this->request->post['country_id']))$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				if(isset($this->request->post['zone_id']))$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
				$this->load->model('account/customer');
				foreach ($this->model_account_customer->getCustomOptions(0) as $option) {				
					if($option['section']==1)
						$this->session->data['xtensions']['personal'][$option['identifier']]=$this->model_account_customer->getCustomerOptionsA($this->customer->getId(),$option['option_id'],$option['type'],0);
					else if($option['section']==2)
						$this->session->data['xtensions']['payment'][$option['identifier']]=$this->model_account_customer->getCustomerOptionsA($this->customer->getId(),$option['option_id'],$option['type'],$this->session->data['payment_address_id']);
				}					
				if (!empty($this->request->post['shipping_address'])) {
					$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
					if (isset($this->request->post['country_id']))$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
					if(isset($this->request->post['zone_id']))$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
					if(isset($this->request->post['postcode']))$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
					foreach ($this->model_account_customer->getCustomOptions(2) as $option) {					
						$this->session->data['xtensions']['shipping'][$option['identifier']]=$this->model_account_customer->getCustomerOptionsA($this->customer->getId(),$option['option_id'],$option['type'],$this->session->data['shipping_address_id']);
					}					
				}
			} else {
				$json['redirect'] = $this->url->link('account/success');
			}
			$isAddress=$this->model_account_address->getAddresses();
			if(empty($isAddress)){
				$json['addressRequired']=true;
			}
			unset($this->session->data['guest']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);	
			unset($this->session->data['payment_methods']);
		}	
		
		$this->response->setOutput(json_encode($json));	
	} 
}
?>