<?php
class ModelSaleCustomer extends Model {
	public function addCustomer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

      	$customer_id = $this->db->getLastId();
      	$this->db->query("delete from " . DB_PREFIX . "xcustom_customer_option where customer_id = ".(int)$customer_id);
      	$this->addCustomerOptionData($data,$customer_id,0,1);
      	if (isset($data['address'])) {
      		foreach ($data['address'] as $address) {
      			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				$address_id = $this->db->getLastId();
				$this->addCustomerOptionData($address,$customer_id,$address_id,2);
				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
	}

	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', status = '" . (int)$data['status'] . "' WHERE customer_id = '" . (int)$customer_id . "'");

      	if ($data['password']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE customer_id = '" . (int)$customer_id . "'");
      	}
      	$this->db->query("delete from " . DB_PREFIX . "xcustom_customer_option where customer_id = ".(int)$customer_id);
      	$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
      	$this->addCustomerOptionData($data,$customer_id,0,1);
      	if (isset($data['address'])) {
      		foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");
				$address_id = $this->db->getLastId();
				$this->addCustomerOptionData($address,$customer_id,$address_id,2);
				if (isset($address['default'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}
		}
	}

	public function editToken($customer_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteCustomer($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	// My Script
	public function getOptionSql( $option_title, $csv_title ) {
		// Ref# 34
		$field_style = 'select';

		// Checkbox issue
		if( $csv_title == 'KnowUsForm' || $csv_title == 'AlwaysBuyPlace' )
			$field_style = 'checkbox';
		if( $csv_title == 'BabyNickName' || $csv_title == 'BirthMonth' )
			$field_style = 'text';

 //		echo $field_style.' '.$option_title.' - '.$csv_title.'<br>';	// DEBUG
/*
		# SQL Ref: http://x.co/6A3VG
#		IN (52, 56)

		SET @a = '34';

			SELECT
				*, GROUP_CONCAT(name SEPARATOR ', ') AS temp
			FROM
				oc_customer c, oc_xcustom_value_description cvd
			WHERE
				cvd.option_value_id IN (52, 56)
			AND customer_id = @a

		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
		// OLD
		// (SELECT (SELECT name FROM oc_xcustom_value_description cvd WHERE (cvd.option_value_id = cco.value) LIMIT 0, 1 ) AS temp FROM oc_xcustom_customer_option cco WHERE name = '你從什麼途徑得知思詩樂' AND customer_id = c.customer_id LIMIT 0, 1) AS KnowUsForm

		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

		SET @a = '34';
		SELECT
			GROUP_CONCAT(
				(SELECT cvd.name FROM oc_xcustom_value_description cvd WHERE option_value_id = cco.option_value_id)
			SEPARATOR ', ')  AS temp
		FROM oc_xcustom_customer_option cco
		WHERE
			cco.name LIKE '%出生日期%' AND value <> ''
			AND customer_id = @a
*/

		if( $field_style == 'select' ){
			$str = ", (SELECT (SELECT name FROM " . DB_PREFIX . "xcustom_value_description cvd WHERE (cvd.option_value_id = cco.value) LIMIT 0, 1 ) AS temp FROM " . DB_PREFIX . "xcustom_customer_option cco WHERE name = '". $option_title ."' AND customer_id = c.customer_id LIMIT 0, 1) AS  ".$csv_title." ";

		}elseif( $field_style == 'checkbox' ){
			$str = ", (SELECT GROUP_CONCAT((SELECT cvd.name FROM " . DB_PREFIX . "xcustom_value_description cvd WHERE option_value_id = cco.option_value_id) SEPARATOR ', ')  AS temp FROM " . DB_PREFIX . "xcustom_customer_option cco WHERE cco.name LIKE '%".$option_title."%' AND value <> '' AND customer_id = c.customer_id LIMIT 0, 1) AS  ".$csv_title." ";

		}else{	// text
			$str = ", (SELECT value AS temp FROM " . DB_PREFIX . "xcustom_customer_option cco WHERE cco.name LIKE '%".$option_title."%' AND value <> '' AND customer_id = c.customer_id LIMIT 0, 1) AS  ".$csv_title." ";
		}

		return $str;
	}// !getOptionSql()

	public function getCustomersCsv( $date_from, $date_to ) {
		// Fields declear
		$fields_query = array(
						// $csv_title , $option_title
						'Title' 		=> '稱謂',
						'MyAge'			=> '年齡',
						'SentNewbieGift'=> '已贈送禮品（迎新）',
						'SentBBGift'	=> '已贈送禮品（預產期）',
						'BirthMonth' 	=> '出生日期 / 預產期 (e.g. 2015-12)',	// Main
						'GoonSize' 		=> 'GOO.N 紙尿片試用裝尺碼',
						'KnowUsForm'	=> '你從什麼途徑得知思詩樂',
						'AlwaysBuyPlace'=> '你最常購買嬰兒產品',
						'HospitalClass' => '出生醫院 - 類別',
						'PublicHospitalName1' => '出生公立醫院 - 名稱',
						'PrivateHospitalName2' => '出生私家醫院 - 名稱',
						'BabyNickName' => '別名',
						'BabySex'		=> '性別',
					);

		// Baby date
		if (isset($this->request->get['filter_bb_from'])){
			// Months handle
			$start    = (new DateTime($date_from))->modify('first day of this month');
			$end      = (new DateTime($date_to))->modify('+1 month');
			$interval = DateInterval::createFromDateString('1 month');
			$period   = new DatePeriod($start, $interval, $end);
			$months   = array();

			foreach ($period as $dt)
			    $months[] = "'".$dt->format("Y-m")."'";
	//		foreach ($months as $m)	echo $m.', ';	// DEBUG
			$period = " value= " . implode(" OR value=", $months);
			$period = " AND c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "xcustom_customer_option WHERE customer_id = c.customer_id AND name LIKE '%出生日期%' AND ".$period.")";
			$order_by = ' ORDER BY BirthMonth ASC ';
		}else{
			// Member
			$period = " AND (date_added BETWEEN '$date_from' AND '$date_to')";
			$order_by = ' ORDER BY date_added ASC ';
		}

		// SQL code
		$sql  = "SELECT *, ";
		$sql .= "CONCAT(c.firstname, ' ', c.lastname) AS name";
		$sql .= ", cgd.name AS customer_group ";

		// Query - Address(included all of shipping)
		$sql .= " ,(SELECT GROUP_CONCAT(address_1 SEPARATOR '\\n\\r') FROM " . DB_PREFIX . "address ad WHERE ad.customer_id = c.customer_id) AS address ";

		// Query - Fields
		foreach ($fields_query as $csv_title => $web_title)
			$sql .= $this->getOptionSql($web_title, trim($csv_title));

		$sql .= "FROM " . DB_PREFIX . "customer c ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		$sql .= $period;
		$sql .= $order_by;
//		$sql .= ' LIMIT 0, 50';	// DEBUG

		$query = $this->db->query($sql);
//		echo '<p>'.$sql.'</p>';	// DEBUG
		return $query->rows;
	}

	public function getCustomers($data = array()) {

		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// My Script
		if (!empty($data['filter_telephone'])) {
			$tel = $this->db->escape($data['filter_telephone']);

			$implode[] = "c.telephone LIKE '%" . $tel . "%' OR c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "xcustom_customer_option WHERE customer_id = c.customer_id AND value LIKE '%".$tel."%')  ";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode ) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.approved',
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
 //		echo 'SQL:['.$sql.']';	// DEBUG
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function approve($customer_id) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '1' WHERE customer_id = '" . (int)$customer_id . "'");

			$this->language->load('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $store_name;

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data= array(
				'address_id'     => $address_query->row['address_id'],
				'customer_id'    => $address_query->row['customer_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'company_id'     => $address_query->row['company_id'],
				'tax_id'         => $address_query->row['tax_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
			foreach($this->getCustomOptions(2) as $option){
				if($option['type']!='checkbox'){
				$option_data=$this->getCustomerAddressOptions((int)$address_query->row['customer_id'],  $option['option_id'], null, (int)$address_id);
				$address_data= array_merge($address_data,array($option['identifier'] => $option_data));
				}else{
					foreach ($option['option_value'] as $option_value){
						$option_data=$this->getCustomerAddressOptions((int)$address_query->row['customer_id'], $option['option_id'], $option_value['option_value_id'], (int)$address_id);
						$address_data= array_merge($address_data,array($option['identifier'].$option_value['option_value_id'] => $option_data));
					}
				}
			}
			return $address_data;
		}
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getTotalCustomers($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		// My Script
		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCustomersAwaitingApproval() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCustomerId($customer_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");

		return $query->row['total'];
	}

	public function addHistory($customer_id, $comment) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
	}

	public function getHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalHistories($customer_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

			$this->language->load('mail/customer');

			if ($customer_info['store_id']) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getTransactions($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalTransactions($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

			$this->language->load('mail/customer');

			if ($order_id) {
				$this->load->model('sale/order');

				$order_info = $this->model_sale_order->getOrder($order_id);

				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			// $mail->send();
		}
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getIpsByCustomerId($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function getTotalCustomersByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function addBanIp($ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function removeBanIp($ip) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function getTotalBanIpsByIp($ip) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

//--------------------------------------------Xcustomer
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


	public function addCustomerOptionData($data,$customer_id,$address_id,$section){
		foreach ($this->getCustomOptions($section) as $option){
			if($option['type']!='checkbox'){
			if($section==1)
				$value=$this->db->escape($data['option'.$option['option_id']]);
			else
				$value=$this->db->escape($data[$option['identifier']]);
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_customer_option
				set customer_id = ".(int)$customer_id." ,
				address_id = ".(int)$address_id." , "."
				option_id = ".(int)$option['option_id']." ,
				option_value_id = ".(int)$option['option_id']." ,
				name = '".$this->db->escape($option['name'])."' ,
				value = '".$value."' ,
				type = '".$option['type']."'
			");
			}
		if($option['type']=='checkbox'){
			foreach ($option['option_value'] as $option_value) {
			if($section==1)
				$value=isset($data['optionV'.$option['option_id'].'C'.$option_value['option_value_id']])?$this->db->escape($data['optionV'.$option['option_id'].'C'.$option_value['option_value_id']]):"";
			else
				$value=isset($data[$option['identifier'].$option_value['option_value_id']])?$this->db->escape($data[$option['identifier'].$option_value['option_value_id']]):"";
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_customer_option
				set customer_id = ".(int)$customer_id." ,
				address_id = ".(int)$address_id." , "."
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

public function getCustomCheckbox($customer_id, $field_name ) {
	$query = $this->db->query("SELECT value FROM " . DB_PREFIX . "xcustom_customer_option where customer_id = $customer_id AND name = '$field_name'");

	// Old customer have no customer-field in DB
	if( empty($query->row["value"]) )
		return 'No';
	else
		return 'Yes';
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
				);
			}
      	}
		return $product_option_data;
	}
}
?>