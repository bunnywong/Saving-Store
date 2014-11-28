<?php
class ControllerCommonKulerSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url'))
		{
			$this->url->addRewrite($this);
		}
		
		// Decode URL
		if (isset($this->request->get['_route_']))
		{
			$parts = explode('/', $this->request->get['_route_']);

			$this->load->model('module/kbm');
			$blog_virtual = $this->model_module_kbm->getSetting('category', 'virtual_directory_name');
			$article_url_suffix = $this->model_module_kbm->getSetting('article', 'url_suffix');

			foreach ($parts as $part)
			{
				if (isset($this->request->get['kbm_home']) && $this->request->get['kbm_home'] && $article_url_suffix)
				{
					$part = str_replace($article_url_suffix, '', $part);
				}

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

				// Check home page of Kuler Blog Manager
				if ($part == $blog_virtual)
				{
					$this->request->get['kbm_home'] = true;
				}
				
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id')
					{
						$this->request->get['product_id'] = $url[1];
					}
					
					if ($url[0] == 'category_id')
					{
						if (!isset($this->request->get['path']))
						{
							$this->request->get['path'] = $url[1];
						}
						else
						{
							$this->request->get['path'] .= '_' . $url[1];
						}
					}	
					
					if ($url[0] == 'manufacturer_id')
					{
						$this->request->get['manufacturer_id'] = $url[1];
					}
					
					if ($url[0] == 'information_id')
					{
						$this->request->get['information_id'] = $url[1];
					}

					// Kuler Blog Manager
					if ($url[0] == 'kbm_category_id')
					{
						if (!isset($this->request->get['kbm_path']))
						{
							$this->request->get['kbm_path'] = $url[1];
						} else
						{
							$this->request->get['kbm_path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'kbm_article_id')
					{
						$this->request->get['kbm_article_id'] = $url[1];
					}
				} else {
					$this->request->get['route'] = 'error/not_found';	
				}
			}
			
			if (isset($this->request->get['kbm_path']) || isset($this->request->get['kbm_article_id']))
			{
				$this->request->get['kbm_home'] = false;
			}

			if (isset($this->request->get['product_id']))
			{
				$this->request->get['route'] = 'product/product';
			}
			elseif (isset($this->request->get['path']))
			{
				$this->request->get['route'] = 'product/category';
			}
			elseif (isset($this->request->get['manufacturer_id']))
			{
				$this->request->get['route'] = 'product/manufacturer/info';
			}
			else if (isset($this->request->get['kbm_home']) && $this->request->get['kbm_home'])
			{
				$this->request->get['route'] = 'module/kbm';
			}
			else if (isset($this->request->get['kbm_article_id']))
			{
				$this->request->get['article_id'] = $this->request->get['kbm_article_id'];
				$this->request->get['path'] = isset($this->request->get['kbm_path']) ? $this->request->get['kbm_path'] : '';
				$this->request->get['route'] = 'module/kbm/article';
			}
			else if (isset($this->request->get['kbm_path']))
			{
				$this->request->get['kbm_path'] = $this->request->get['kbm_path'];
				$this->request->get['route'] = 'module/kbm/category';
			}
			elseif (isset($this->request->get['information_id']))
			{
				$this->request->get['route'] = 'information/information';
			}

			if (isset($this->request->get['route']))
			{
				return $this->forward($this->request->get['route']);
			}
		}
	}
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
	
		$url = ''; 
		
		$data = array();
		
		parse_str($url_info['query'], $data);

		$this->load->model('module/kbm');
		$blog_virtual_enabled = $this->model_module_kbm->getSetting('category', 'virtual_directory');
		$article_url_suffix = $this->model_module_kbm->getSetting('article', 'url_suffix');

		$kbm_path = true;

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
				
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				}
				else if ($blog_virtual_enabled && $data['route'] == 'module/kbm' || $data['route'] == 'module/kbm/index')
				{
					$url = '/' . $this->model_module_kbm->getSetting('category', 'virtual_directory_name') . '/';
				}
				else if ($blog_virtual_enabled && ($data['route'] == 'module/kbm/category' || $data['route'] == 'module/kbm/article') && $key == 'kbm_path')
				{
					$virtual_exclude_categories = $this->model_module_kbm->getSetting('category', 'virtual_exclude_category');

					$categories = explode('_', $value);

					foreach ($categories as $category)
					{
						if (in_array($category, $virtual_exclude_categories))
						{
							$kbm_path = false;
							$url = '';
							break;
						}

						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'kbm_category_id=" . (int)$category . "'");

						if ($query->num_rows)
						{
							if (empty($url))
							{
								$url = '/' . $this->model_module_kbm->getSetting('category', 'virtual_directory_name');
							}

							$url .= '/' . $query->row['keyword'];
						}
					}

					if ($kbm_path)
					{
						unset($data[$key]);
					}
				}
				else if ($blog_virtual_enabled && $data['route'] == 'module/kbm/article')
				{
					if ($kbm_path)
					{
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape('kbm_article_id=' . (int)$value) . "'");

						if ($query->num_rows)
						{
							if (empty($url))
							{
								$url = '/' . $this->model_module_kbm->getSetting('category', 'virtual_directory_name');
							}

							$url .= '/' . $query->row['keyword'];

							if ($article_url_suffix)
							{
								$url .= $article_url_suffix;
							}

							unset($data[$key]);
						}
					}
				}
				elseif ($key == 'path') {
					$categories = explode('_', $value);
					
					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
				
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}

					unset($data[$key]);
				}
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

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}	
}
?>