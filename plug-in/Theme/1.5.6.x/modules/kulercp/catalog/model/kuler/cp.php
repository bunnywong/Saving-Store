<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ModelKulerCp extends Model {
    private $table_name = '';
    private $resources = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->table_name = DB_PREFIX . 'kulercp_resources';
        $this->updateTable();
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
	
    public function getResources($type = 'script', $store_id) {
        // Get all resources
        if(empty($this->resources)) {
            $store_id = intval($store_id);

            $query = $this->db->query("SELECT type, name, version FROM {$this->table_name} WHERE status=1 AND store_id = " . $store_id);
            foreach ($query->rows as $item) {
                $this->resources[$item['type']] = (object)$item;
            }
        }
        // Return resources by type
        if(isset($this->resources[$type])) {
            return $this->resources[$type];
        } else {
            return array();
        }
    }
    
    public function getCompressStyles() {
        $server = $this->getBaseCatalog();
        $style = $this->getResources('style', $this->config->get('config_store_id'));
        $link  = '<link type="text/css" rel="stylesheet" href="' . $server . 'index.php?route=common/kulercp&cache=' . $style->name . '.css&v=' . $style->version . '" />' . "\n";
        return $link;
    }
    
    public function getCompressScripts() {
        $server = $this->getBaseCatalog();
        $item = $this->getResources('script', $this->config->get('config_store_id'));
        $script = '<script type="text/javascript" src="' . $server . 'index.php?route=common/kulercp&cache=' . $item->name . '.js&v=' . $item->version . '"></script>' . "\n";
        return $script;
    }
    
	public function getOptimalHeader($field, $status = null) {
		$query = $this->db->query("SELECT $field FROM {$this->table_name} WHERE 1 " . ($status ? " AND status={$status}" : "") . " ORDER BY type DESC");
		$respond = '';
		foreach ($query->rows as $item) {
			$item = (object) $item;

            $server = $this->getBaseCatalog();

			if ($item->type == 'script') {
				$respond .= '<script type="text/javascript" src="' . $server . 'index.php?route=common/kulercp&cache=' . $item->name . '.js&v=' . $item->version . '"></script>' . "\n";
			} else {
				$respond .= '<link type="text/css" rel="stylesheet" href="' . $server . 'index.php?route=common/kulercp&cache=' . $item->name . '.css&v=' . $item->version . '" />' . "\n";
			}
		}

		return $respond;
	}
	
	public function getCache($type, $name, $store_id) {
        $store_id = intval($store_id);

		$query = $this->db->query("SELECT cache FROM {$this->table_name} WHERE type='" . $this->db->escape($type) . "' AND name='" . $this->db->escape($name) . "' AND store_id = $store_id");

		$result = '';

		if($query->row) {
			$result = $query->row['cache'];
		}
		
		return $result;
	}

    private function updateTable()
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

    private function getBaseCatalog()
    {

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
        {
            $base = $this->config->get('config_ssl') ? $this->config->get('config_ssl') : HTTPS_SERVER;
        }
        else
        {
            $base = $this->config->get('config_url') ? $this->config->get('config_url') : HTTP_SERVER;
        }

        return $base;
    }

	public function colorSchemeCompile(array $colors)
	{
		$template = new Template();
		$template->data = $colors;

		return $template->fetch(sprintf('%s/includes/custom.php', $this->config->get('config_template')));
	}
}

