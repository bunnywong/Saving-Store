<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ModelKulerCp extends Model {
    private $table_name = '';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->table_name = DB_PREFIX . 'kulercp_resources';
    }

    public function getSettingByStoreId($store_id)
    {
        $config = array();

        $store_id = intval($store_id);
        $query = $this->db->query("
            SELECT *
            FROM " . DB_PREFIX . "setting
            WHERE store_id = 0
                OR store_id = $store_id
            ORDER BY store_id ASC
        ");

        foreach ($query->rows as $setting)
        {
            if (!$setting['serialized'])
            {
                $config[$setting['key']] = $setting['value'];
            }
            else
            {
                $config[$setting['key']] = unserialize($setting['value']);
            }
        }

        // Get config language id
        $languages = array();

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");

        foreach ($query->rows as $language) {
            if ($language['code'] == $config['config_language'])
            {
                $config['config_language_id'] = $language['language_id'];
                break;
            }
        }

        return $config;
    }

	public function editSetting($group, $key, $value, $store_id = 0) {
		$serialized = 0;
		
		if(is_array($value) || is_object($value)) {
			$value = serialize($value);
			$serialized = 1;
		}
        $store_id = intval($store_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "setting
                          WHERE `group` = '{$group}' AND `key` = '{$key}' AND `store_id` = $store_id");
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting
                          SET `group` = '{$group}', `key` = '{$key}', `serialized` = '{$serialized}', `value` = '" . $this->db->escape($value) . "', `store_id` = $store_id");
	}
    
	public function editSettingValue($group = '', $key = '', $value = '', $store_id = 0) {
		if (!is_array($value)) {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . " WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape(serialize($value)) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "' AND serialized = '1'");
		}
    }

	public function getCurrentLanguage() {
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $language) {
			if ($language['code'] == $this->session->data['language']) {
				return $language;
			}
		}
	}

    public function updateTable()
    {
        $table_data = array();

        $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        foreach ($query->rows as $result) {
            if ($result['Tables_in_' . DB_DATABASE] == 'resources')
            {
                $this->db->query('ALTER TABLE  `resources` ADD COLUMN  `store_id` INT( 11 ) DEFAULT 0');
                $this->db->query("ALTER TABLE `resources` DROP COLUMN `id`, DROP INDEX `type`, ADD PRIMARY KEY (`type`, `store_id`)");
                $this->db->query("RENAME TABLE `resources` TO `{$this->table_name}`");

                return true;
            }
        }

        return false;
    }
 
	public function createTable() {
		// Create table
		$query = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
			`store_id` int(11) DEFAULT 0,
			`type` varchar(10) NOT NULL DEFAULT '',
			`name` varchar(45) NOT NULL DEFAULT '',
			`router` varchar(1024) NOT NULL DEFAULT '',
			`version` int(10) unsigned NOT NULL,
			`files` longtext NOT NULL,
			`cache` longblob NOT NULL,
			`param` longtext NOT NULL,
			`status` int(11) DEFAULT NULL,
			PRIMARY KEY (`store_id`, `type`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		
		return $this->db->query($query);
	}

    public function dropTable()
    {
        $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        foreach ($query->rows as $result) {
            if ($result['Tables_in_' . DB_DATABASE] == 'resources')
            {
                $this->db->query('DROP TABLE `resources`');
            }
            else if ($result['Tables_in_' . DB_DATABASE] == $this->table_name)
            {
                $this->db->query("DROP TABLE `{$this->table_name}`");
            }
        }
    }
	
	public function insertFields($fields, $store_id) {
		$table = $this->table_name;

		$insertFields = array();

		if (empty($fields)) {
			return false;
		}

		foreach ($fields as $name => $value) {
			if (strpos($name, '_') === 0) {
				continue;
			}
			$insertFields[] = '`' . $name . '` = \'' . $this->db->escape($value) . '\'';
		}

        $store_id = intval($store_id);
        $insertFields[] = '`store_id` = ' . $store_id;
		
		$insertQuery = implode(',', $insertFields);
		$query = ' INSERT INTO ' . $table . ' SET ';
		$query.= $insertQuery;
		$query.= ' ON DUPLICATE KEY UPDATE ';
		$query.= $insertQuery;

		$this->db->query($query);

		return $this->db->getLastId();
	}
	
	public function getCollections() {
		$query = $this->db->query("SELECT * FROM {$this->table_name} ORDER BY type DESC");
		$collection = array();
		if ($query->rows) {
			foreach ($query->rows as $row) {
				$row['router'] = $row['router'] ?  $row['router'] : '*';
				$row['files'] = unserialize($row['files']);
				$collection[] = $row;
			}
		}
		
		return $collection;
	}
	
	public function deleteResource($value, $field = 'id') {
		return $this->db->query("DELETE FROM {$this->table_name} WHERE `{$field}`='" . $this->db->escape($value) . "'");
	}
	
	public function getResource($rid) {
		$query = $this->db->query("SELECT * FROM {$this->table_name} WHERE id=" . $rid);
		return $query->row;
	}
    
    public function getResourceByType($type, $store_id) {
        $store_id = intval($store_id);

		$query = $this->db->query("SELECT * FROM {$this->table_name} WHERE type='{$type}' AND `store_id` = $store_id");
		return $query->row;
	}
	
	public function updateResource($router, $param, $content, $status, $rid) {
		$this->db->query("UPDATE {$this->table_name} SET router='" . $this->db->escape($router) . "', param='" . $this->db->escape($param) . "', cache='" . $this->db->escape($content) . "', version=version+1, status=$status WHERE id=$rid");
	}
   
    /**
     * @category Sitemap helper function
     */
    public function getSitemapCategories($parent_id = 0) {
        $query = $this->db->query("
            SELECT *
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_description cd
                ON (c.category_id = cd.category_id)
            INNER JOIN " . DB_PREFIX . "category_to_store c2s
                ON (c.category_id = c2s.category_id)
            WHERE c.parent_id = '" . (int) $parent_id . "'
                AND cd.language_id = '" . intval($this->config->get('config_language_id')) . "'
                AND c2s.store_id = '" . intval($this->config->get('config_store_id')) . "'
                AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)
        ");

        return $query->rows;
    }
	
    public function getAllProducts($data = array()) {
        $store_id = intval($this->config->get('config_store_id'));

        $sql = "
            SELECT p.*
            FROM " . DB_PREFIX . "product p
            LEFT JOIN " . DB_PREFIX . "product_description pd
                ON (p.product_id = pd.product_id)
            INNER JOIN ". DB_PREFIX ."product_to_store pts
                ON (p.product_id = pts.product_id AND pts.store_id = $store_id)
            WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        if (isset($data['filter_name']) && !is_null($data['filter_name']))
        {
            $sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_model']) && !is_null($data['filter_model']))
        {
            $sql .= " AND LCASE(p.model) LIKE '%" . $this->db->escape(strtolower($data['filter_model'])) . "%'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity']))
        {
            $sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status']))
        {
            $sql .= " AND p.status = '" . (int) $data['filter_status'] . "'";
        }
        
        if (isset($data['sort']))
        {
			$sql .= " ORDER BY " . $data['sort'];	
            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }
        }
		
		if (isset($data['limit'])) {
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['limit'];
		}	

        $query = $this->db->query($sql);

        return $query->rows;
    }
        
    public function rewrite($link) {
        if ($this->config->get('config_seo_url')) {
            $url_data = parse_url(str_replace('&amp;', '&', $link));

            $url = '';

            $data = array();

            parse_str($url_data['query'], $data);

            foreach ($data as $key => $value) {
                if (($key == 'product_id') || ($key == 'manufacturer_id') || ($key == 'information_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");

                    if ($query->num_rows) {
                        $url .= '/' . $query->row['keyword'];

                        unset($data[$key]);
                    }
                } elseif ($key == 'path') {
                    $categories = explode('_', $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                        if ($query->num_rows) {
                            $url .= '/' . $query->row['keyword'];
                        }
                    }

                    unset($data[$key]);
                }
            }

            if ($url) {
                unset($data['route']);

                $query = '';

                if ($data) {
                    foreach ($data as $key => $value) {
                        $query .= '&' . $key . '=' . $value;
                    }

                    if ($query) {
                        $query = '?' . trim($query, '&');
                    }
                }

                return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
            } else {
                return $link;
            }
        } else {
            return $link;
        }
    }

    public function exportBeforeInstallSampleData()
    {
        $output = '';

        $user_table = DB_PREFIX . 'user';

        $tables = array(
            DB_PREFIX . 'user' => '',
            DB_PREFIX . 'setting' => "`key` IN ('config_url', 'config_encryption')"
        );

        foreach ($tables as $table => $condition)
        {
            $sql = "SELECT * FROM `" . $table . "`";

            if ($condition)
            {
                $sql .= ' WHERE ' . $condition;
            }

            $query = $this->db->query($sql);

            foreach ($query->rows as $result) {
                $fields = '';

                foreach (array_keys($result) as $value) {
                    $fields .= '`' . $value . '`, ';
                }

                $values = '';

                foreach (array_values($result) as $value)
                {
                    $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                    $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                    $value = str_replace('\\', '\\\\',	$value);
                    $value = str_replace('\'', '\\\'',	$value);
                    $value = str_replace('\\\n', '\n',	$value);
                    $value = str_replace('\\\r', '\r',	$value);
                    $value = str_replace('\\\t', '\t',	$value);

                    $values .= '\'' . $value . '\', ';
                }

                $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
            }

            $output .= "\n\n";
        }

        return $output;
    }

    public function restoreAfterInstallSampleData()
    {
        $db = $this->db;

        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_url'");
        $db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");

        $db->query("TRUNCATE TABLE `" . DB_PREFIX . "user`");
    }

	/**
	 * Get css files in stylsheet
	 * @param string $folder name of folder contains css files, must end of with '/'
	 * @return array
	 */
	public function customCssGetFiles($folder = '')
	{
		$files = glob($this->customCssGetCssDirectoryPath() . $folder . '/*.css');
		$results = array();

		if (is_array($files))
		{
			foreach ($files as $file)
			{
				$results[] = basename($file);
			}
		}

		return $results;
	}

	public function customCssRemoveFile($file)
	{
		$file_path = $this->customCssSolveFilePath($file);

		if (file_exists($file_path))
		{
			@unlink($file_path);
		}
	}

	public function customCssSolveFilePath($file_name)
	{
		return $this->customCssGetCssDirectoryPath() . $file_name;
	}

	public function customCssGetCssDirectoryPath()
	{
		return DIR_APPLICATION . '../catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/';
	}

	public function customCssGetCssFolder($group)
	{
		switch ($group)
		{
			case 'default':
				$folder = '';
				break;
			case 'preset':
				$folder = 'presets/';
				break;
			case 'custom':
			default:
				$folder = 'custom/';
		}

		return $folder;
	}

	public function presetIsFile($filename)
	{
		return file_exists(DIR_SYSTEM . '../catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/presets/' . $filename . '.css');
	}
}

class router {
	public static function linkToAbsolute($baseUrl, $relativeUrl) {
		// If relative URL has a scheme, clean path and return.
		$r = self::linkSplit($relativeUrl);
		if ($r === FALSE)
			return FALSE;
		if (!empty($r['scheme'])) {
			if (!empty($r['path']) && $r['path'][0] == '/')
				$r['path'] = self::linkRemoveDot($r['path']);
			return self::linkJoin($r);
		}

		// Make sure the base URL is absolute.
		$b = self::linkSplit($baseUrl);
		if ($b === FALSE || empty($b['scheme']) || empty($b['host']))
			return FALSE;
		$r['scheme'] = $b['scheme'];

		// If relative URL has an authority, clean path and return.
		if (isset($r['host'])) {
			if (!empty($r['path']))
				$r['path'] = self::linkRemoveDot($r['path']);
			return self::linkJoin($r);
		}
		unset($r['port']);
		unset($r['user']);
		unset($r['pass']);

		// Copy base authority.
		$r['host'] = $b['host'];
		if (isset($b['port']))
			$r['port'] = $b['port'];
		if (isset($b['user']))
			$r['user'] = $b['user'];
		if (isset($b['pass']))
			$r['pass'] = $b['pass'];

		// If relative URL has no path, use base path
		if (empty($r['path'])) {
			if (!empty($b['path']))
				$r['path'] = $b['path'];
			if (!isset($r['query']) && isset($b['query']))
				$r['query'] = $b['query'];
			return self::linkJoin($r);
		}

		// If relative URL path doesn't start with /, merge with base path
		if ($r['path'][0] != '/') {
			$base = mb_strrchr($b['path'], '/', TRUE, 'UTF-8');
			if ($base === FALSE)
				$base = '';
			$r['path'] = $base . '/' . $r['path'];
		}
		$r['path'] = self::linkRemoveDot($r['path']);
		return self::linkJoin($r);
	}
	
	public static function linkRemoveDot($path) {
		// multi-byte character explode
		$inSegs = preg_split('!/!u', $path);
		$outSegs = array();
		foreach ($inSegs as $seg) {
			if ($seg == '' || $seg == '.')
				continue;
			if ($seg == '..')
				array_pop($outSegs);
			else
				array_push($outSegs, $seg);
		}
		$outPath = implode('/', $outSegs);
		if ($path[0] == '/')
			$outPath = '/' . $outPath;
		// compare last multi-byte character against '/'
		if ($outPath != '/' &&
				(mb_strlen($path) - 1) == mb_strrpos($path, '/', 'UTF-8'))
			$outPath .= '/';
		return $outPath;
	}
	
	public static function linkSplit($url, $decode = TRUE) {
		// Character sets from RFC3986.
		$xunressub = 'a-zA-Z\d\-._~\!$&\'()*+,;=';
		$xpchar = $xunressub . ':@%';

		// Scheme from RFC3986.
		$xscheme = '([a-zA-Z][a-zA-Z\d+-.]*)';

		// User info (user + password) from RFC3986.
		$xuserinfo = '(([' . $xunressub . '%]*)' .
				'(:([' . $xunressub . ':%]*))?)';

		// IPv4 from RFC3986 (without digit constraints).
		$xipv4 = '(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})';

		// IPv6 from RFC2732 (without digit and grouping constraints).
		$xipv6 = '(\[([a-fA-F\d.:]+)\])';

		// Host name from RFC1035.  Technically, must start with a letter.
		// Relax that restriction to better parse URL structure, then
		// leave host name validation to application.
		$xhost_name = '([a-zA-Z\d-.%]+)';

		// Authority from RFC3986.  Skip IP future.
		$xhost = '(' . $xhost_name . '|' . $xipv4 . '|' . $xipv6 . ')';
		$xport = '(\d*)';
		$xauthority = '((' . $xuserinfo . '@)?' . $xhost .
				'?(:' . $xport . ')?)';

		// Path from RFC3986.  Blend absolute & relative for efficiency.
		$xslash_seg = '(/[' . $xpchar . ']*)';
		$xpath_authabs = '((//' . $xauthority . ')((/[' . $xpchar . ']*)*))';
		$xpath_rel = '([' . $xpchar . ']+' . $xslash_seg . '*)';
		$xpath_abs = '(/(' . $xpath_rel . ')?)';
		$xapath = '(' . $xpath_authabs . '|' . $xpath_abs .
				'|' . $xpath_rel . ')';

		// Query and fragment from RFC3986.
		$xqueryfrag = '([' . $xpchar . '/?' . ']*)';

		// URL.
		$xurl = '^(' . $xscheme . ':)?' . $xapath . '?' .
				'(\?' . $xqueryfrag . ')?(#' . $xqueryfrag . ')?$';


		// Split the URL into components.
		if (!preg_match('!' . $xurl . '!', $url, $m))
			return FALSE;

		if (!empty($m[2]))
			$parts['scheme'] = strtolower($m[2]);

		if (!empty($m[7])) {
			if (isset($m[9]))
				$parts['user'] = $m[9];
			else
				$parts['user'] = '';
		}
		if (!empty($m[10]))
			$parts['pass'] = $m[11];

		if (!empty($m[13]))
			$h = $parts['host'] = $m[13];
		else if (!empty($m[14]))
			$parts['host'] = $m[14];
		else if (!empty($m[16]))
			$parts['host'] = $m[16];
		else if (!empty($m[5]))
			$parts['host'] = '';
		if (!empty($m[17]))
			$parts['port'] = $m[18];

		if (!empty($m[19]))
			$parts['path'] = $m[19];
		else if (!empty($m[21]))
			$parts['path'] = $m[21];
		else if (!empty($m[25]))
			$parts['path'] = $m[25];

		if (!empty($m[27]))
			$parts['query'] = $m[28];
		if (!empty($m[29]))
			$parts['fragment'] = $m[30];

		if (!$decode)
			return $parts;
		if (!empty($parts['user']))
			$parts['user'] = rawurldecode($parts['user']);
		if (!empty($parts['pass']))
			$parts['pass'] = rawurldecode($parts['pass']);
		if (!empty($parts['path']))
			$parts['path'] = rawurldecode($parts['path']);
		if (isset($h))
			$parts['host'] = rawurldecode($parts['host']);
		if (!empty($parts['query']))
			$parts['query'] = rawurldecode($parts['query']);
		if (!empty($parts['fragment']))
			$parts['fragment'] = rawurldecode($parts['fragment']);
		return $parts;
	}
	
	public static function linkJoin($parts, $encode = TRUE) {
		if ($encode) {
			if (isset($parts['user']))
				$parts['user'] = rawurlencode($parts['user']);
			if (isset($parts['pass']))
				$parts['pass'] = rawurlencode($parts['pass']);
			if (isset($parts['host']) &&
					!preg_match('!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host']))
				$parts['host'] = rawurlencode($parts['host']);
			if (!empty($parts['path']))
				$parts['path'] = preg_replace('!%2F!ui', '/', rawurlencode($parts['path']));
			if (isset($parts['query']))
				$parts['query'] = rawurlencode($parts['query']);
			if (isset($parts['fragment']))
				$parts['fragment'] = rawurlencode($parts['fragment']);
		}

		$url = '';
		if (!empty($parts['scheme']))
			$url .= $parts['scheme'] . ':';
		if (isset($parts['host'])) {
			$url .= '//';
			if (isset($parts['user'])) {
				$url .= $parts['user'];
				if (isset($parts['pass']))
					$url .= ':' . $parts['pass'];
				$url .= '@';
			}
			if (preg_match('!^[\da-f]*:[\da-f.:]+$!ui', $parts['host']))
				$url .= '[' . $parts['host'] . ']'; // IPv6
			else
				$url .= $parts['host'];   // IPv4 or name
			if (isset($parts['port']))
				$url .= ':' . $parts['port'];
			if (!empty($parts['path']) && $parts['path'][0] != '/')
				$url .= '/';
		}
		if (!empty($parts['path']))
			$url .= $parts['path'];
		if (isset($parts['query']))
			$url .= '?' . $parts['query'];
		if (isset($parts['fragment']))
			$url .= '#' . $parts['fragment'];
		return $url;
	}	
}