<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data,$modData,$isActive) {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
	if($isActive){
		$address_id=0;
      	$pquery= "INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id')."'" ;
        if($modData['f_name_show'])
            $pquery.=  ", firstname = '" . $this->db->escape($data['firstname'])."'" ;
        if($modData['l_name_show'])
            $pquery.= ", lastname = '" . $this->db->escape($data['lastname'])."'"  ;
        $pquery.=", email = '" . $this->db->escape($data['email'])."'";
        if($modData['mob_show'])
            $pquery.=", telephone = '" . $this->db->escape($data['telephone'])."'" ;
         if($modData['fax_show'])
            $pquery.=", fax = '" . $this->db->escape($data['fax'])."'";
         $pquery.= ", salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))
                         . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password']))))."'" ;
         if($modData['subsribe_show'])
            $pquery.=", newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0)."'" ;
         $pquery.=", customer_group_id = '" . (int)$customer_group_id . "', ip = '"
                         . $this->db->escape($this->request->server['REMOTE_ADDR'])
                         . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()";
                $this->db->query($pquery);
		$customer_id = $this->db->getLastId();
	 if(!$modData['show_address'] && ($modData['company_show'] || $modData['companyId_show'] || $modData['address1_show'] || $modData['address2_show']
                 || $modData['city_show'] || $modData['pin_show'] || $modData['country_show'] || ($modData['state_show'] && $modData['country_show']))) {
      	$pquery = "INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id ."'";
         if($modData['f_name_show'])
            $pquery.=", firstname = '" . $this->db->escape($data['firstname'])."'";
         if($modData['l_name_show'])
            $pquery.= ", lastname = '" . $this->db->escape($data['lastname'])."'";
         if($modData['company_show'])
            $pquery.=", company = '" . $this->db->escape($data['company'])."'" ;
         if($modData['companyId_show'])
            $pquery.=", company_id = '" . $this->db->escape($data['company_id'])."'" ;
            $pquery.=", tax_id = '" . $this->db->escape($data['tax_id'])."'" ;
         if($modData['address1_show'])
            $pquery.=", address_1 = '" . $this->db->escape($data['address_1'])."'" ;
         if($modData['address2_show'])
            $pquery.=", address_2 = '" . $this->db->escape($data['address_2'])."'" ;
         if($modData['city_show'])
            $pquery.=", city = '" . $this->db->escape($data['city'])."'" ;
         if($modData['pin_show'])
            $pquery.=", postcode = '" . $this->db->escape($data['postcode'])."'" ;
         if($modData['country_show'] || (!$modData['country_show'] && $modData['def_country']))
            $pquery.=", country_id = '" . (int)$data['country_id']."'" ;
         if($modData['state_show'] || (!$modData['state_show'] && $modData['def_state']))
            $pquery.=", zone_id = '" . (int)$data['zone_id'] . "'";
		$this->db->query($pquery);
		$address_id = $this->db->getLastId();
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
            $this->addCustomerOptionData($data,$customer_id,(int)$address_id,2);
         }
        }else{
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', customer_group_id = '" . (int)$customer_group_id . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this->db->getLastId();

      	$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");

		$address_id = $this->db->getLastId();

      	$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		$this->addCustomerOptionData($data,$customer_id,(int)$address_id,2);
        }
        //--------------------------------------------Xcustomer
      	$this->addCustomerOptionData($data,$customer_id,0,1);
		$this->language->load('mail/customer');
		$this->language->load('account/register');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		// My Script // todo
		$message .= '登入電郵：'. "\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			if(isset($data['firstname']))$message .= $this->language->get('entry_firstname') . '  ' . $data['firstname'] . "\n";
			if(isset($data['lastname']))$message .= $this->language->get('entry_lastname') . '  ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . '  ' . $customer_group_info['name'] . "\n";



			$message .= $this->language->get('entry_email') . '  '  .  $data['email'] . "\n";
			if(isset($data['telephone']))$message .= $this->language->get('entry_telephone') . '  ' . $data['telephone'] . "\n";
			if(isset($data['fax']))$message .= $this->language->get('entry_fax') . '  ' . $data['fax'] . "\n";
			foreach ($this->getCustomOptions(1) as $option){
				$message .= $option['name'] . ' : '  . $this->getCustomerOptionsA($customer_id, $option['option_id'], $option['type'], 0) . "\n";

			}


			if(!$modData['show_address']){
				$message .= "\n\n Address: \n\n\n";
				if(isset($data['company'])){
					$message .= $this->language->get('entry_company') . '  '  . $data['company'] . "\n";
				}
				if(isset($data['company_id'])){
					$message .= $this->language->get('entry_company_id') . '  '  . $data['company_id'] . "\n";
				}
				if(isset($data['tax_id'])){
					$message .= $this->language->get('entry_tax_id') . '  '  . $data['tax_id'] . "\n";
				}
				if(isset($data['address_1'])){
					$message .= $this->language->get('entry_address_1') . '  '  . $data['address_1'] . "\n";
				}
				if(isset($data['address_2'])){
					$message .= $this->language->get('entry_address_2') . '  '  . $data['address_2'] . "\n";
				}
				//Custom Address Fields
				foreach ($this->getCustomOptions(2) as $option){
					$message .= $option['name'] . ' : '  . $this->getCustomerOptionsA($customer_id, $option['option_id'], $option['type'], $address_id) . "\n";
				}
				if(isset($data['postcode'])){
					$message .= $this->language->get('entry_postcode') . '  '  . $data['postcode'] . "\n";
				}
				if(isset($data['city'])){
					$message .= $this->language->get('entry_city') . '  '  . $data['city'] . "\n";
				}
				if(isset( $data['country_id'])){
					$this->load->model('localisation/country');
					$id=  $this->model_localisation_country->getCountry( $data['country_id']);
					$message .= $this->language->get('entry_country') . '  '  . $id['name'] . "\n";
				}
				if(isset($data['zone_id'])){
					$this->load->model('localisation/zone');
					$id=  $this->model_localisation_zone->getZone( $data['zone_id']);
					$message .= $this->language->get('entry_zone') . '  '  . $id['name'] . "\n";
				}
				if(isset($data['newsletter'])){
					$message .= $this->language->get('entry_newsletter') . '  '  . ($data['newsletter']==1?'Yes':'No') . "\n";
				}

			}
			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));

			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function editCustomer($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		//--------------------------------------------Xcustomer
      	$this->addCustomerOptionData($data,(int)$this->customer->getId(),0,1);
	}

	public function editPassword($email, $password) {
      	$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (isset($data['filter_customer_group_id']) && !is_null($data['filter_customer_group_id'])) {
			$implode[] = "cg.customer_group_id = '" . $this->db->escape($data['filter_customer_group_id']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	//--------------------------------------------Xcustomer

	public function addCustomerOptionData($data,$customer_id,$address_id,$section){
	$this->db->query("delete from " . DB_PREFIX . "xcustom_customer_option where customer_id = ".(int)$customer_id . " AND address_id = ".(int)$address_id );
		foreach ($this->getCustomOptions($section) as $option){
			if($option['type']!='checkbox'){
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_customer_option
				set customer_id = ".(int)$customer_id." ,
				address_id = ".(int)$address_id." ,
				option_id = ".(int)$option['option_id']." ,
				option_value_id = ".(int)$option['option_id']." ,
				name = '".$this->db->escape($option['name'])."' ,
				value = '".$this->db->escape($data['option'.$option['option_id']])."' ,
				type = '".$option['type']."'
			");
			}
		if($option['type']=='checkbox'){
			foreach ($option['option_value'] as $option_value) {
			$value=isset($data['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])?$this->db->escape($data['optionV'.$option['option_id'].'C'.$option_value['option_value_id']]):"";
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_customer_option
				set customer_id = ".(int)$customer_id." ,
				address_id = ".(int)$address_id." ,
				option_id = ".(int)$option['option_id']." ,
				option_value_id = ".(int)$option_value['option_value_id']." ,
				name = '".$this->db->escape($option['name'])."' ,
				value = '".$value."' ,
				type = '".$option['type']."'
			");
			}
			}
		}
	}

	public function getGuestOptionsA($option_id,$type,$value) {
		if(empty($value))
			return '';
		$query = $this->db->query("
					select  cv.name
					from " . DB_PREFIX . "xcustom c left join " . DB_PREFIX . "xcustom_value_description cv on c.option_id = cv.option_id
					where  c.option_id = ".(int)$option_id.
					' and cv.language_id = ' . (int)$this->config->get('config_language_id').
					" and cv.option_value_id in (".$value.")"
					 );
		if($type=='checkbox' ){
			$name ='';
			$option_ids='';
			foreach ($query->rows as $result) {
				$name .= ' '.$result['name'].',';
			}
			$name=ltrim($name,' '); $name=rtrim($name,',');
			if($name){
				return $name;
			}else{
				return "";
			} }
		else{
			if(empty( $query->row["name"]))
				return "";
			else
				return  $query->row["name"];
		}
	}
	 public function getCustomerOptionsA($customer_id,$option_id,$type,$address_id) {
	 	$selectable =0;
	 	if($type=='select' || $type=='radio' || $type=='checkbox'){
	 		$selectable =1;}
		$query = $this->db->query("
					select  cco.value ".
					(($selectable) ?", cvd.name ":"")
					."from " . DB_PREFIX . "xcustom c left join " . DB_PREFIX . "xcustom_value cv on c.option_id = cv.option_id
					left join " . DB_PREFIX . "xcustom_value_description cvd on cvd.option_value_id = cv.option_value_id
					left join " . DB_PREFIX . "xcustom_customer_option cco on c.option_id = cco.option_id
					where cco.customer_id = ".(int)$customer_id.
					(($selectable)  ?' and cvd.language_id = ' . (int)$this->config->get('config_language_id') :"")."
					and cco.address_id = ".(int)$address_id."
					and c.option_id = ".(int)$option_id. " ".
					($selectable? " and cco.value = cv.option_value_id and cco.value =cvd.option_value_id and cco.value <> '' " : "")
					 );
		if($selectable &&  $type=='checkbox' ){
			$name ='';
			$option_ids='';
			foreach ($query->rows as $result) {
				$name .= ' '.$result['name'].',';
			}
			$name=ltrim($name,' '); $name=rtrim($name,',');
			if($name){
				return $name;
			}else{
				return "";
			} }
		elseif ($selectable){
			if(empty( $query->row["name"]))
				return "";
			else
				return  $query->row["name"];
		}else{
			if(empty( $query->row["value"]))
				return "";
			else
				return  $query->row["value"];
			}
	}


	public function getCustomerOptions($customer_id,$option_id,$option_value_id) {
		$query = $this->db->query("
					select  cco.value from " . DB_PREFIX . "xcustom c left join " . DB_PREFIX . "xcustom_value cv on c.option_id = cv.option_id
					left join " . DB_PREFIX . "xcustom_customer_option cco on c.option_id = cco.option_id
					where cco.customer_id = ".(int)$customer_id."
					and c.option_id = ".(int)$option_id. " ".
					($option_value_id !=null ? " and cco.option_value_id = cv.option_value_id and cco.option_value_id =".(int)$option_value_id : "")
					 );
		if(empty( $query->row["value"]))
			return "";
			else
			return  $query->row["value"];
	}

public function getCustomerOrderOptions($option_id,$data_in,$order_id) {
		$query = $this->db->query('select value from ' . DB_PREFIX . 'xcustom_customer_order_option where order_id = '.$order_id.' and data_in = "'.$data_in.'" and option_id = '.$option_id);
		if($query->row)
			return  $query->row["value"];
		else
			return  "";
	}
public function getCustomerAddressOptions($customer_id,$option_id,$option_value_id,$address_id) {
		$query = $this->db->query("
					select  cco.value from " . DB_PREFIX . "xcustom c left join " . DB_PREFIX . "xcustom_value cv on c.option_id = cv.option_id
					left join " . DB_PREFIX . "xcustom_customer_option cco on c.option_id = cco.option_id
					where cco.customer_id = ".(int)$customer_id."
					and cco.address_id = ".(int)$address_id."
					and c.option_id = ".(int)$option_id. " ".
					($option_value_id !=null ? " and cco.option_value_id = cv.option_value_id and cco.option_value_id =".(int)$option_value_id : "")
					 );
		if(empty( $query->row["value"]))
			return "";
		else
			return  $query->row["value"];
	}
public function getCustomOptions($section=0) {
		$product_option_data = array();
		$product_option_query = $this->db->query("SELECT * FROM
										" . DB_PREFIX . "xcustom o  LEFT JOIN
										" . DB_PREFIX . "xcustom_description od ON (o.option_id = od.option_id)
										WHERE o.isenable = 1 and
  										od.language_id = '" . (int)$this->config->get('config_language_id') ."' ".
		(($section>0)?"AND o.section = ".$section : "")
		. " ORDER BY 3") ;
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
				$product_option_value_query = $this->db->query(
				"SELECT * FROM
				" . DB_PREFIX . "xcustom_value ov LEFT JOIN
 				" . DB_PREFIX . "xcustom_value_description ovd ON (ov.option_value_id = ovd.option_value_id)
 				WHERE
 				ov.option_id = '" . (int)$product_option['option_id'] . "'  and
  				ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'
 				ORDER BY 4 ");
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
					);
				}
				$product_option_data[] = array(
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option_value_data,
					'required'          => $product_option['required'],
					'min'          => $product_option['minimum'],
					'max'          => $product_option['maximum'],
					'invoice'          => $product_option['invoice'],
					'error'          => $product_option['error'],
					'tips'			=> $product_option['tips'],
					'mask'			=> $product_option['mask'],
					'identifier'			=> $product_option['identifier'],
					'section'			=> $product_option['section'],
					'isnumeric'  => $product_option['isnumeric'],
					'email_display'  => $product_option['email_display'],
					'order_display'  => $product_option['order_display'],
					'list_display'  => $product_option['list_display'],
					'sort_order'  => $product_option['sort_order'],
				);
			} else {
				$product_option_data[] = array(
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					//'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required'],
					'min'          => $product_option['minimum'],
					'max'          => $product_option['maximum'],
					'invoice'          => $product_option['invoice'],
					'error'          => $product_option['error'],
					'tips'			=> $product_option['tips'],
					'mask'			=> $product_option['mask'],
					'identifier'			=> $product_option['identifier'],
					'section'			=> $product_option['section'],
					'isnumeric'  => $product_option['isnumeric'],
					'email_display'  => $product_option['email_display'],
					'order_display'  => $product_option['order_display'],
					'list_display'  => $product_option['list_display'],
					'sort_order'  => $product_option['sort_order'],
				);
			}
      	}
		return $product_option_data;
	}

	public function customInstall(){

	$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom (
  option_id int(11) NOT NULL AUTO_INCREMENT,
  type varchar(32) NOT NULL,
  sort_order int(3) NOT NULL,
  required tinyint(4) NOT NULL DEFAULT '0',
  invoice tinyint(4) NOT NULL DEFAULT '0',
  minimum int(11) NOT NULL DEFAULT '0',
  maximum int(11) NOT NULL DEFAULT '0',
  mask varchar(32) NOT NULL,
  identifier varchar(32) NOT NULL,
  section tinyint(4) NOT NULL DEFAULT '1',
  isnumeric tinyint(4) NOT NULL DEFAULT '0',
  email_display tinyint(4) NOT NULL DEFAULT '0' ,
order_display tinyint(4) NOT NULL DEFAULT '0' ,
list_display tinyint(4) NOT NULL DEFAULT '0' ,
isenable tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (option_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ");

		$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_customer_option (
  customer_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  option_value_id int(11) NOT NULL DEFAULT '0',
  address_id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL,
  value text,
  type varchar(32) NOT NULL,
  PRIMARY KEY (customer_id,address_id,option_id,option_value_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");



		$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_guest_option (
  order_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  option_value_id int(11) NOT NULL DEFAULT '0',
  section int(11) NOT NULL DEFAULT '1',
  data_in varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  value text,
  type varchar(32) NOT NULL,
  PRIMARY KEY (order_id,data_in,section,option_id,option_value_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_customer_order_option (
  order_id int(11) NOT NULL,
  customer_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  option_value_id int(11) NOT NULL DEFAULT '0',
  section int(11) NOT NULL DEFAULT '1',
  data_in varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  value text,
  type varchar(32) NOT NULL,
  PRIMARY KEY (order_id,customer_id,data_in,section,option_id,option_value_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");


		$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_description (
  option_id int(11) NOT NULL,
  language_id int(11) NOT NULL,
  name varchar(128) NOT NULL,
  error text,
  tips text,
  PRIMARY KEY (option_id,language_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_value (
  option_value_id int(11) NOT NULL AUTO_INCREMENT,
  option_id int(11) NOT NULL,
  image varchar(255) NOT NULL,
  sort_order int(3) NOT NULL,
  PRIMARY KEY (option_value_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8");
		$this->db->query("
CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "xcustom_value_description (
  option_value_id int(11) NOT NULL,
  language_id int(11) NOT NULL,
  option_id int(11) NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (option_value_id,language_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
		");
	}
}
?>
