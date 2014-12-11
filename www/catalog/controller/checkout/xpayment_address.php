<?php
class ControllerCheckoutXPaymentAddress extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');
		$this->load->model('account/signup');
		$this->data['isActive']=  $this->model_account_signup->isActiveMod();
		$this->data['modData']=  $this->model_account_signup->getModData();
		$modData1 = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
		$isActive1=$isActive2['enablemod'];
		$this->data['cfname']= "";
		$this->data['clname']= "";
		if ($this->customer->isLogged()){
			$this->data['cfname']= $this->customer->getFirstName();
			$this->data['clname']= $this->customer->getLastName();
		}

		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->language->load('account/xtensions');
		$this->data['title_firstname'] = $this->language->get('title_firstname');
		$this->data['title_lastname'] = $this->language->get('title_lastname');
		$this->data['title_company'] = $this->language->get('title_company');
		$this->data['title_company_id'] = $this->language->get('title_company_id');
		$this->data['title_address_1'] = $this->language->get('title_address_1');
		$this->data['title_address_2'] = $this->language->get('title_address_2');
		$this->data['title_postcode'] = $this->language->get('title_postcode');
		$this->data['title_city'] = $this->language->get('title_city');

		$this->data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->session->data['payment_address_id'])) {
			$this->data['address_id'] = $this->session->data['payment_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}


		$this->data['addresses'] = array();
		$this->load->model('account/xaddress');
		$results = $this->model_account_xaddress->getAddresses();
		$this->load->model('account/xcustomer');
		$stringP = '';
		foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option){
			if($option['list_display'])$stringP .= '{'.$option['identifier'].'}'."\n";
		}
		foreach ($results as $result) {

			if ($result['address_format']) {
				$format = $result['address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" .$stringP. '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
      			);
      			foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option){
      				if($option['list_display'])array_push($find, '{'.$option['identifier'].'}');
      			}
      			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => substr($result['zone'], 5),
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']
      			);
      			foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option){
      				if($option['list_display']){$value = $this->model_account_xcustomer->getCustomerOptionsA((int)$this->customer->getId(),$option['option_id'],$option['type'],$result['address_id']);
      				$replace= array_merge($replace, array($option['identifier'] => $value));}
      			}
      			$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
      			);
		}

		//$this->load->model('account/xaddress');

		//$this->data['addresses'] = $this->model_account_xaddress->getAddresses();

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

		if ($customer_group_info) {
			$this->data['company_id_display'] = $customer_group_info['company_id_display'];
		} else {
			$this->data['company_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['company_id_required'] = $customer_group_info['company_id_required'];
		} else {
			$this->data['company_id_required'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		} else {
			$this->data['tax_id_display'] = '';
		}

		if ($customer_group_info) {
			$this->data['tax_id_required'] = $customer_group_info['tax_id_required'];
		} else {
			$this->data['tax_id_required'] = '';
		}

		if (!$modData1['country_show_checkout'] && $modData1['def_country']) {
			$this->data['country_id'] = $modData1['def_country'];
		} elseif (isset($this->session->data['payment_country_id'])) {
			$this->data['country_id'] = $this->session->data['payment_country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (!$modData1['state_show_checkout'] && $modData1['def_state']) {
			$this->data['zone_id'] = $modData1['def_state'];
		} elseif (isset($this->session->data['payment_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['payment_zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();
		$this->load->model('account/xcustomer');
		$this->model_account_xcustomer->customInstall();
		foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option) {
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
		$this->data['options'] = $this->model_account_xcustomer->getCustomOptions(2);


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/xpayment_address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/xpayment_address.tpl';
		} else {
			$this->template = 'default/template/checkout/xpayment_address.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function validate() {
		$this->language->load('checkout/checkout');
		$this->load->model('account/signup');
		$modData = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
		$isActive=$isActive2['enablemod'];
		$json = array();

		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
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
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				$this->load->model('account/xaddress');

				if (empty($this->request->post['paddress_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['paddress_id'], array_keys($this->model_account_xaddress->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				} else {
					// Default Payment Address
					$this->load->model('account/xaddress');

					$address_info = $this->model_account_xaddress->getAddress($this->request->post['paddress_id']);

					if ($address_info) {
						$this->load->model('account/customer_group');

						$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

						// Company ID
						if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && !$address_info['company_id']) {
							$json['error']['warning'] = $this->language->get('error_company_id');
						}

						// Tax ID
						if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && !$address_info['tax_id']) {
							$json['error']['warning'] = $this->language->get('error_tax_id');
						}
					}
				}

				if (!$json) {
					$this->session->data['payment_address_id'] = $this->request->post['paddress_id'];

					if ($address_info) {
						$this->session->data['payment_country_id'] = $address_info['country_id'];
						$this->session->data['payment_zone_id'] = $address_info['zone_id'];
					} else {
						unset($this->session->data['payment_country_id']);
						unset($this->session->data['payment_zone_id']);
					}

					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			} else {
				$this->load->model('account/xcustomer');
				$this->model_account_xcustomer->customInstall();
				foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option) {
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
					if ((!$isActive || ($isActive && $modData['f_name_req_checkout'] && $modData['f_name_show_checkout']))  && ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32))) {
						$json['error']['firstname'] = $this->language->get('error_firstname');
					}

					if ((!$isActive || ($isActive && $modData['l_name_req_checkout'] && $modData['l_name_show_checkout'])) && ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32))) {
						$json['error']['lastname'] = $this->language->get('error_lastname');
					}

					// Customer Group
					$this->load->model('account/customer_group');

					$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->customer->getCustomerGroupId());

					if ($customer_group_info) {
						// Company ID
						if ($customer_group_info['company_id_display'] && $customer_group_info['company_id_required'] && empty($this->request->post['company_id'])) {
							$json['error']['company_id'] = $this->language->get('error_company_id');
						}

						// Tax ID
						if ($customer_group_info['tax_id_display'] && $customer_group_info['tax_id_required'] && empty($this->request->post['tax_id'])) {
							$json['error']['tax_id'] = $this->language->get('error_tax_id');
						}
					}

					if ((!$isActive || ($isActive && $modData['address1_req_checkout'] && $modData['address1_show_checkout'])) && ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128))) {
						$json['error']['address_1'] = $this->language->get('error_address_1');
					}

					if ((!$isActive || ($isActive && $modData['city_req_checkout'] && $modData['city_show_checkout'])) && ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128))) {
						$json['error']['city'] = $this->language->get('error_city');
					}

					$this->load->model('localisation/country');

					if(!$isActive || ($isActive && $modData['pin_req_checkout'] && $modData['pin_show_checkout']))
					$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

					if ((!$isActive || ($isActive && $modData['pin_req_checkout'] && $modData['pin_show_checkout'])) && $country_info) {
						if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
							$json['error']['postcode'] = $this->language->get('error_postcode');
						}

						// VAT Validation
						$this->load->helper('vat');

						if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
							$json['error']['tax_id'] = $this->language->get('error_vat');
						}
					}

					if ((!$isActive || ($isActive && $modData['country_req_checkout'] && $modData['country_show_checkout'])) && $this->request->post['country_id'] == '') {
						$json['error']['country'] = $this->language->get('error_country');
					}

					if ((!$isActive || ($isActive && $modData['state_req_checkout'] && $modData['state_show_checkout'] )) && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
						$json['error']['zone'] = $this->language->get('error_zone');
					}

					if (!$json) {
						// Default Payment Address
						$this->load->model('account/xaddress');

						$this->session->data['payment_address_id'] = $this->model_account_xaddress->addAddress($this->request->post);
						$this->session->data['payment_country_id'] = $this->request->post['country_id'];
						$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
						$this->load->model('account/xcustomer');
						foreach ($this->model_account_xcustomer->getCustomOptions(0) as $option) {
							if($option['section']==1)
							$this->session->data['xtensions']['personal'][$option['identifier']]=$this->model_account_xcustomer->getCustomerOptionsA($this->customer->getId(),$option['option_id'],$option['type'],0);
							else if($option['section']==2)
							$this->session->data['xtensions']['payment'][$option['identifier']]=$this->model_account_xcustomer->getCustomerOptionsA($this->customer->getId(),$option['option_id'],$option['type'],$this->session->data['payment_address_id']);
						}

						unset($this->session->data['payment_method']);
						unset($this->session->data['payment_methods']);
					}
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>