<?php
class ModelModuleCustom extends Model {
	
	public function addOption($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "xcustom` SET type = '" . $this->db->escape($data['type']) . 
		"', sort_order = '" . (int)$data['sort_order'] . 
		"', required = '" . (isset($data['required']) ? (int)$data['required'] : 0) . "' ,		
		 minimum = '" . (int)$data['min'] .
		"', maximum = '" . (int)$data['max'] .
		"', invoice = '" . (isset($data['invoice']) ? (int)$data['invoice'] : 0) .
		"', email_display = '" . (isset($data['email_display']) ? (int)$data['email_display'] : 0) .
		"', order_display = '" . (isset($data['order_display']) ? (int)$data['order_display'] : 0) .
		"', list_display = '" . (isset($data['list_display']) ? (int)$data['list_display'] : 0) .
		"', isenable = '" . (isset($data['isenable']) ? (int)$data['isenable'] : 0) .
		"', mask = '" .$this->db->escape($data['mask'])  .
		"', identifier = '" . $this->db->escape($data['identifier'])  .
		"', section = '" . (isset($data['section']) ? (int)$data['section'] : 0) .
		"', isnumeric = '" . (isset($data['isnumeric']) ? (int)$data['isnumeric'] : 0) . 
		"'" 
		);
		
		$option_id = $this->db->getLastId();
		
		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		foreach ($data['option_msg'] as $language_id => $value) {
			$this->db->query(" update " . DB_PREFIX . "xcustom_description SET error = '" . $this->db->escape($value['error']) . "' where option_id = '" . (int)$option_id . "' and language_id = '" . (int)$language_id . "' " );
		}
		
		foreach ($data['tips_msg'] as $language_id => $value) {
			$this->db->query(" update " . DB_PREFIX . "xcustom_description SET tips = '" . $this->db->escape($value['tips']) . "' where option_id = '" . (int)$option_id . "' and language_id = '" . (int)$language_id . "' " );
		}

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_value SET option_id = '" . (int)$option_id .  "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				
				$option_value_id = $this->db->getLastId();
				
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}
	}
	
	public function editOption($option_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "xcustom` SET type = '" . $this->db->escape($data['type']) . "', sort_order = '" . (int)$data['sort_order'] . 
		"', required = '" . (isset($data['required']) ? (int)$data['required'] : 0) . "' ,
		 minimum = '" . (int)$data['min'] .
		"', maximum = '" . (int)$data['max'] .
		"', invoice = '" . (isset($data['invoice'])?(int)$data['invoice']:0) .
		"', email_display = '" . (isset($data['email_display']) ? (int)$data['email_display'] : 0) .
		"', order_display = '" . (isset($data['order_display']) ? (int)$data['order_display'] : 0) .
		"', list_display = '" . (isset($data['list_display']) ? (int)$data['list_display'] : 0) .
		"', isenable = '" . (isset($data['isenable']) ? (int)$data['isenable'] : 0) .
		"', mask = '" .$this->db->escape($data['mask'])  .
		"', identifier = '" . $this->db->escape($data['identifier'])  .
		"', section = '" . (isset($data['section']) ? (int)$data['section'] : 0) .
		"', isnumeric = '" . (isset($data['isnumeric']) ? (int)$data['isnumeric'] : 0) . 
		"' 		
		 WHERE option_id = '" . (int)$option_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_description WHERE option_id = '" . (int)$option_id . "'");

		foreach ($data['option_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_description SET option_id = '" . (int)$option_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		foreach ($data['option_msg'] as $language_id => $value) {
			$this->db->query(" update " . DB_PREFIX . "xcustom_description SET error = '" . $this->db->escape($value['error']) . "' where option_id = '" . (int)$option_id . "' and language_id = '" . (int)$language_id . "' " );
		}
		
		foreach ($data['tips_msg'] as $language_id => $value) {
			$this->db->query(" update " . DB_PREFIX . "xcustom_description SET tips = '" . $this->db->escape($value['tips']) . "' where option_id = '" . (int)$option_id . "' and language_id = '" . (int)$language_id . "' " );
		}
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_value WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_value_description WHERE option_id = '" . (int)$option_id . "'");
		
		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_value SET option_value_id = '" . (int)$option_value['option_value_id'] . "', option_id = '" . (int)$option_id .  "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_value SET option_id = '" . (int)$option_id .  "', sort_order = '" . (int)$option_value['sort_order'] . "'");
				}
				
				$option_value_id = $this->db->getLastId();
				
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "xcustom_value_description SET option_value_id = '" . (int)$option_value_id . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_id . "', name = '" . $this->db->escape($option_value_description['name']) . "'");
				}
			}
		}
	}
	
	public function deleteOption($option_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "xcustom` WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_description WHERE option_id = '" . (int)$option_id . "'");	
		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_value WHERE option_id = '" . (int)$option_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "xcustom_value_description WHERE option_id = '" . (int)$option_id . "'");
	}
	
	public function getOption($option_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "xcustom` o LEFT JOIN " . DB_PREFIX . "xcustom_description od ON (o.option_id = od.option_id) WHERE o.option_id = '" . (int)$option_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getOptions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "xcustom` o LEFT JOIN " . DB_PREFIX . "xcustom_description od ON (o.option_id = od.option_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND od.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'od.name',
			'o.type',
			'o.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY od.name";	
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
	
	public function getOptionDescriptions($option_id) {
		$option_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_description WHERE option_id = '" . (int)$option_id . "'");
				
		foreach ($query->rows as $result) {
			$option_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $option_data;
	}
	
	public function getOptionErrors($option_id) {
		$option_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_description WHERE option_id = '" . (int)$option_id . "'");
				
		foreach ($query->rows as $result) {
			$option_data[$result['language_id']] = array('error' => $result['error']);
		}
		
		return $option_data;
	}
	
	public function getTipsMsgs($option_id) {
		$option_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_description WHERE option_id = '" . (int)$option_id . "'");
				
		foreach ($query->rows as $result) {
			$option_data[$result['language_id']] = array('tips' => $result['tips']);
		}
		
		return $option_data;
	}
	
	public function getOptionValue($option_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_value ov LEFT JOIN " . DB_PREFIX . "xcustom_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getOptionValues($option_id) {
		$option_value_data = array();
		
		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_value ov LEFT JOIN " . DB_PREFIX . "xcustom_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order ASC");
				
		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				
				'sort_order'      => $option_value['sort_order']
			);
		}
		
		return $option_value_data;
	}
	
	public function getOptionValueDescriptions($option_id) {
		$option_value_data = array();
		
		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_value WHERE option_id = '" . (int)$option_id . "'");
				
		foreach ($option_value_query->rows as $option_value) {
			$option_value_description_data = array();
			
			$option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "xcustom_value_description WHERE option_value_id = '" . (int)$option_value['option_value_id'] . "'");			
			
			foreach ($option_value_description_query->rows as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}
			
			$option_value_data[] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				
				'sort_order'               => $option_value['sort_order']
			);
		}
		
		return $option_value_data;
	}

	public function getTotalOptions() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "xcustom`"); 
		
		return $query->row['total'];
	}

public function getCustomOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM  
										" . DB_PREFIX . "xcustom o  LEFT JOIN 
										" . DB_PREFIX . "xcustom_description od ON (o.option_id = od.option_id) 
										WHERE 
  										od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY 3") ;
		
		
		
		
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
					'isenable'  => $product_option['isenable'],
				);
			} else {
				$product_option_data[] = array(					
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
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
					'isenable'  => $product_option['isenable'],
				);				
			}
      	}
		
		return $product_option_data;
	}
	
public function install(){
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