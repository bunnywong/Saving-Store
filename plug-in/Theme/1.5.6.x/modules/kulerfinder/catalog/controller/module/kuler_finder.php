<?php  
class ControllerModuleKulerFinder extends Controller {
    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->language->load('module/kuler_finder');
    }

	protected function index($setting) {
        // Prepare setting
        $setting['search_field_text'] = $this->translate($setting['search_field_text'], $this->config->get('config_language_id'));

        if (empty($setting['search_result_limit']))
        {
            $setting['search_result_limit'] = 3;
        }

		if (!isset($setting['manufacturer_filter']))
		{
			$setting['manufacturer_filter'] = 0;
		}

		if (!isset($setting['product_description_filter']))
		{
			$setting['product_description_filter'] = 0;
		}

		if(isset($setting['category']) && $setting['category']) {
			// 3 Level Category Search
			$this->language->load('product/search');
			$this->load->model('catalog/category');

			// Load languages
			$this->data['text_search'] = $this->language->get('text_search');
			$this->data['text_keyword'] = $this->language->get('text_keyword');
			$this->data['text_category'] = $this->language->get('text_category');
			$this->data['text_sub_category'] = $this->language->get('text_sub_category');

			if (isset($this->request->get['category_id'])) {
				$category_id = $this->request->get['category_id'];
			} else {
				if(isset($this->request->get['path'])) {
					$part = explode('_', $this->request->get['path']);
					$category_id = array_pop($part);
				} else {
					$category_id = 0;
				}
			}
			
			$this->data['category_id'] = $category_id;
			$this->data['categories'] = array();

			$categories_1 = $this->model_catalog_category->getCategories(0);

			foreach ($categories_1 as $category_1) {
				$level_2_data = array();

				$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

				foreach ($categories_2 as $category_2) {
					$level_3_data = array();

					$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

					foreach ($categories_3 as $category_3) {
						$level_3_data[] = array(
							'category_id' => $category_3['category_id'],
							'name'        => $category_3['name'],
						);
					}

					$level_2_data[] = array(
						'category_id' => $category_2['category_id'],	
						'name'        => $category_2['name'],
						'children'    => $level_3_data
					);					
				}

				$this->data['categories'][] = array(
					'category_id' => $category_1['category_id'],
					'name'        => $category_1['name'],
					'children'    => $level_2_data
				);
			}
		}

		// Prepare manufacture
		if ($setting['manufacturer_filter'])
		{
			$this->load->model('catalog/manufacturer');
			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			$manufacturer_options = array(
				0 => $this->language->get('text_all_manufacturers')
			);
			foreach ($manufacturers as $manufacturer)
			{
				$manufacturer_options[$manufacturer['manufacturer_id']] = $manufacturer['name'];
			}

			$this->data['manufacturer_options'] = $manufacturer_options;
		}
		
		// Get currency information
		$this->data['currency'] = array(
			'left' => $this->currency->getSymbolLeft(),
			'right' => $this->currency->getSymbolRight(),
		);
		
		// Remove some setting value
		unset($setting['module_title'], $setting['layout_id'], $setting['position'], $setting['status'], $setting['sort_order']);
		
		$this->data['setting'] = $setting;

        $this->data['text_no_results_found'] = $this->language->get('text_no_results_found');
        $this->data['text_load_more'] = $this->language->get('text_load_more');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_finder.phtml')) {
			$this->template = $this->config->get('config_template') . '/template/module/kuler_finder.phtml';
		} else {
			$this->template = 'default/template/module/kuler_finder.phtml';
		}
		
		$this->render();
	}

    public function search()
    {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model']) || isset($this->request->get['filter_category_id'])) {
            $setting = $this->request->get['setting'];

            $page = isset($this->request->request['page']) ? $this->request->request['page'] : 1;
            $limit = $setting['search_result_limit'];
            $start = ($page - 1) * $limit;

            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            if (isset($this->request->get['filter_category_id'])) {
                $filter_category_id = $this->request->get['filter_category_id'];
            } else {
                $filter_category_id = '';
            }

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
	            $filter_tag = $filter_name;
            } else {
                $filter_name = $filter_tag = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

	        if (isset($this->request->get['filter_manufacturer_id']))
	        {
		        $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
	        }
	        else
	        {
		        $filter_manufacturer_id = '';
	        }

            $data = array(
                'filter_category_id'    => $filter_category_id,
	            'filter_sub_category'   => true,
                'filter_name'           => $filter_name,
	            'filter_tag'            => $filter_tag,
	            'filter_description'    => $setting['product_description_filter'],
	            'filter_manufacturer_id'=> $filter_manufacturer_id,
                'filter_model'          => $filter_model,
                'start'                 => $start,
                'limit'                 => $limit
            );

            // Get products
            $results = $this->model_catalog_product->getProducts($data);
            $json['products'] = array();
            foreach ($results as $result)
            {
                $product_data = $this->prepareProduct($result, $setting);
                $product_data['html'] = $this->loadChromeListTemplate($setting, $product_data);

                $json['products'][] = $product_data;
            }

            // Count products
            $productsCount = $this->model_catalog_product->getTotalProducts($data);
            $json['more'] = (($page - 1) * $limit + count($results)) < $productsCount ? 1 : 0;

            // Prepare response
            $json['status'] = 1;
        }

        $this->response->setOutput(json_encode($json));
    }

    private function loadChromeListTemplate($setting, $product)
    {
        $button_cart = $this->language->get('button_cart');
        $button_wishlist = $this->language->get('button_wishlist');
        $button_compare = $this->language->get('button_compare');

        ob_start();

        require(DIR_TEMPLATE . $this->config->get('config_template') . '/includes/module_chrome_list.tpl');

        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    protected function prepareProduct(array $product, $setting)
    {
        $image = $product['image'] ? $this->model_tool_image->resize($product['image'], $setting['image_width'], $setting['image_height']) : false;

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))
        {
            $product['price'] = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
        }
        else
        {
            $product['price'] = false;
        }

        $special = (float)$product['special'] ? $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'))) : false;
        $rating = $this->config->get('config_review_status') ? $product['rating'] : false;

        $product_categories = $this->model_catalog_product->getCategories($product['product_id']);
        $first_category_id = !empty($product_categories) ? $product_categories[0]['category_id'] : 0;

        $product_data = array(
            'product_id' => $product['product_id'],
            'thumb'      => $image,
            'image' => $product['image'],
            'name'       => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
            'description'	 => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
            'price'      => $product['price'],
            'special' => $special,
            'rating'	 => $rating,
            'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
            'href'       => $this->url->link('product/product', 'path=' . $this->getRecursivePath($first_category_id) . '&product_id=' . $product['product_id']),
        );

        return $product_data;
    }

    protected function getRecursivePath($category_id)
    {
        static $categories;

        if (empty($categories))
        {
            $this->load->model('catalog/category');

            /* @var $category_model ModelCatalogCategory */
            $category_model = $this->model_catalog_category;

            $raw_categories = $category_model->getCategories();

            $categories = array();
            foreach ($raw_categories as $raw_category)
            {
                $categories[$raw_category['category_id']] = $raw_category['parent_id'];
            }
        }

        if (!isset($categories[$category_id]))
        {
            return '';
        }

        $path = $category_id;
        $parent_id = $categories[$category_id];

        while (true)
        {
            if (!$parent_id)
            {
                break;
            }

            $path = $parent_id . '_' . $path;
            $parent_id = $categories[$parent_id];
        }

        return $path;
    }

    private function translate($texts, $language_id)
    {
        if (is_array($texts))
        {
            $first = current($texts);

            if (is_string($first))
            {
                $texts = empty($texts[$language_id]) ? $first : $texts[$language_id];
            }
            else if (is_array($texts))
            {
                if (!isset($texts[$language_id]))
                {
                    $texts[$language_id] = array();
                }

                foreach ($first as $key => $value)
                {
                    if (empty($texts[$language_id][$key]))
                    {
                        $texts[$language_id][$key] = $value;
                    }
                }
            }
        }

        return $texts;
    }
}
?>