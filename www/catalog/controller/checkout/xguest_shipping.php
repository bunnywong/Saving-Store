<?php
class ControllerCheckoutXGuestShipping extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');
		$this->load->model('account/signup');
		$this->data['isActive']=  $this->model_account_signup->isActiveMod();
		$this->data['modData']=  $this->model_account_signup->getModData();
		$modData1 = $this->model_account_signup->getModData();
		$isActive2 = $this->model_account_signup->isActiveMod();
		$isActive1=$isActive2['enablemod'];

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
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
		$this->data['title_address_1'] = $this->language->get('title_address_1');
		$this->data['title_address_2'] = $this->language->get('title_address_2');
		$this->data['title_postcode'] = $this->language->get('title_postcode');
		$this->data['title_city'] = $this->language->get('title_city');

		$this->data['button_continue'] = $this->language->get('button_continue');
			
		if (isset($this->session->data['guest']['shipping']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['shipping']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['lastname'])) {
			$this->data['lastname'] = $this->session->data['guest']['shipping']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['company'])) {
			$this->data['company'] = $this->session->data['guest']['shipping']['company'];
		} else {
			$this->data['company'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['address_1'])) {
			$this->data['address_1'] = $this->session->data['guest']['shipping']['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['address_2'])) {
			$this->data['address_2'] = $this->session->data['guest']['shipping']['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['postcode'])) {
			$this->data['postcode'] = $this->session->data['guest']['shipping']['postcode'];
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];
		} else {
			$this->data['postcode'] = '';
		}

		if (isset($this->session->data['guest']['shipping']['city'])) {
			$this->data['city'] = $this->session->data['guest']['shipping']['city'];
		} else {
			$this->data['city'] = '';
		}

		if (!$modData1['country_show_checkout'] && $modData1['def_country']) {
			$this->data['country_id'] = $modData1['def_country'];
		} elseif (isset($this->session->data['guest']['shipping']['country_id'])) {
			$this->data['country_id'] = $this->session->data['guest']['shipping']['country_id'];
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (!$modData1['state_show_checkout'] && $modData1['def_state']) {
			$this->data['zone_id'] = $modData1['def_state'];
		} elseif (isset($this->session->data['guest']['shipping']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/xguest_shipping.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/xguest_shipping.tpl';
		} else {
			$this->template = 'default/template/checkout/xguest_shipping.tpl';
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
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Check if guest checkout is avaliable.
		if (!$this->config->get('config_guest_checkout') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		if (!$json) {
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
					
				if ((!$isActive || ($isActive && $modData['address1_req_checkout'] && $modData['address1_show_checkout'])) && ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128))) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}

				if ((!$isActive || ($isActive && $modData['city_req_checkout'] && $modData['city_show_checkout'])) && ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128))) {
					$json['error']['city'] = $this->language->get('error_city');
				}
					
				$this->load->model('localisation/country');
					
				if(!$isActive || ($isActive && $modData['pin_req_checkout'] && $modData['pin_show_checkout'] ))
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
					
				if ((!$isActive || ($isActive && $modData['pin_req_checkout'] && $modData['pin_show_checkout'] )) && $country_info) {
					if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
						$json['error']['postcode'] = $this->language->get('error_postcode');
					}
				}

				if ((!$isActive || ($isActive && $modData['country_req_checkout'] && $modData['country_show_checkout'])) && $this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
					
				if ((!$isActive || ($isActive && $modData['state_req_checkout'] && $modData['state_show_checkout'])) && (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '')) {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
		}

		if (!$json) {
			foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option) {
				if($option['type']!= 'checkbox'){
					$this->session->data['guest']['shipping']['option'.$option['option_id']] =  $this->request->post['option'.$option['option_id']];
				}
				else if($option['type']== 'checkbox') {
					$valueX ='';
					foreach ($option['option_value'] as $option_value) {
						$value = isset($this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])?$this->request->post['optionV'.$option['option_id'].'C'.$option_value['option_value_id']]:"";
						if($value)
						$valueX .= $value.',';
					}
					$this->session->data['guest']['shipping']['option'.$option['option_id']] = rtrim($valueX,',');
				}
			}
			$this->load->model('account/xcustomer');
			foreach ($this->model_account_xcustomer->getCustomOptions(2) as $option) {
				if($option['type']=='select' || $option['type']=='radio' || $option['type']=='checkbox')
				$this->session->data['xtensions']['shipping'][$option['identifier']]=$this->model_account_xcustomer->getGuestOptionsA($option['option_id'],$option['type'],$this->session->data['guest']['shipping']['option'.$option['option_id']]);
				else
				$this->session->data['xtensions']['shipping'][$option['identifier']]=$this->session->data['guest']['shipping']['option'.$option['option_id']];
			}
			$this->session->data['guest']['shipping']['firstname'] = trim($this->request->post['firstname']);
			$this->session->data['guest']['shipping']['lastname'] = trim($this->request->post['lastname']);
			$this->session->data['guest']['shipping']['company'] = trim($this->request->post['company']);
			$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
			$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
			$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
			$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
			$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
			$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];
				
			$this->load->model('localisation/country');
				
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
			if ($country_info) {
				$this->session->data['guest']['shipping']['country'] = $country_info['name'];
				$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['shipping']['country'] = '';
				$this->session->data['guest']['shipping']['iso_code_2'] = '';
				$this->session->data['guest']['shipping']['iso_code_3'] = '';
				$this->session->data['guest']['shipping']['address_format'] = '';
			}
				
			$this->load->model('localisation/zone');
				
			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
				$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['shipping']['zone'] = '';
				$this->session->data['guest']['shipping']['zone_code'] = '';
			}
				
			$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
			$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
			$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
				
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>