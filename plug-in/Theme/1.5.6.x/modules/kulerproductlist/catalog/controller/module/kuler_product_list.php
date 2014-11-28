<?php
class ControllerModuleKulerProductList extends Controller
{
    const PER_PAGE = 3;

    /* @var ModelModuleKulerProductList $model */
    private $model;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('module/kuler_product_list');
        $this->model = $this->model_module_kuler_product_list;
    }

    public function index($setting)
    {
        static $module = 0;

        // Prepare setting
        $setting = array_merge(array(
            'module'            => $module,
            'display'           => 'product_list',

            'title'             => '',
            'show_title'        => 1,
            'layout_id'         => 1,
            'position'          => 'content_top',
            'status'            => 1,
            'sort_order'        => '',

            'product_order'     => 'latest',
	        'product_total_limit'=> 0,

            // Product
            'product_display'   => 'all',
            'categories'        => '',
            'products'          => '',
            'image_width'       => 155,
            'image_height'      => 155,
            'name'              => 1,
            'price'             => 1,
            'rating'            => 1,
            'description'       => 1,
            'add'               => 1,
            'wishlist'          => 1,
            'compare'           => 1,
            'description_text'  => 100,
            'product_options'   => 1
        ), $setting);

	    $setting['product_total_limit'] = intval($setting['product_total_limit']);

	    if (empty($setting['categories']))
        {
            $setting['categories'] = array();
        }
        else
        {
            $setting['categories'] = json_decode(html_entity_decode($setting['categories'], ENT_QUOTES, 'UTF-8'), true);

            $category_ids = array();

            if (is_array($setting['categories']))
            {
                foreach ($setting['categories'] as $category)
                {
                    $category_ids[] = $category['id'];
                }
            }

            $setting['categories'] = $category_ids;
        }

        if (empty($setting['products']))
        {
            $setting['products'] = array();
        }
        else
        {
            $setting['products'] = json_decode(html_entity_decode($setting['products'], ENT_QUOTES, 'UTF-8'), true);

            $product_ids = array();

            if (is_array($setting['products']))
            {
                foreach ($setting['products'] as $product)
                {
                    $product_ids[] = $product['id'];
                }
            }

            $setting['products'] = $product_ids;
        }

        $setting['title'] = $this->translate($setting['title'], $this->config->get('config_language_id'));

        if (empty($setting['description_text']) || intval($setting['description_text']) != $setting['description_text'])
        {
            $setting['description_text'] = 100;
        }

	    $setting['product_limit'] = $this->config->get('config_catalog_limit');
	    if ($setting['product_total_limit'] && $setting['product_limit'] > $setting['product_total_limit'])
	    {
			$setting['product_limit'] = $setting['product_total_limit'];
	    }

        $this->data['setting'] = $setting;
        $this->data['module_title'] = $setting['title'];
        $this->data['show_title'] = $setting['show_title'];
        $this->data['module'] = $module++;
        $this->data['products'] = $this->prepareProducts($setting, 0, $setting['product_limit']);

	    // Added product total limit
	    $product_total = $this->model->countProducts($this->prepareOptions($setting, 0, self::PER_PAGE));

	    if ($setting['product_total_limit'])
	    {
		    $product_total = $setting['product_total_limit'] < $product_total ? $setting['product_total_limit'] : $product_total;
	    }

        $product_count = $product_total - $setting['product_limit'];

        if ($product_count <= 0)
        {
            $this->data['total_page'] = 0;
        }
        else
        {
            $this->data['total_page'] = ceil($product_count / self::PER_PAGE);
        }

        $this->data['product_url'] = $this->url->link('module/kuler_product_list/products');

        if ($setting['product_options'])
        {
            $this->document->addScript('catalog/view/javascript/jquery/ajaxupload.js');
            $this->document->addScript('catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_product_list.tpl'))
        {
            $this->template = $this->config->get('config_template') . '/template/module/kuler_product_list.tpl';
        }
        else
        {
            $this->template = 'default/template/module/kuler_product_list.tpl';
        }

        $this->render();
    }

    public function products()
    {
        $current_page = $this->request->post['current_page'];
        $page = $this->request->post['page'];
        $setting = json_decode(html_entity_decode($this->request->post['setting'], ENT_QUOTES, 'UTF-8'), true);

	    if ($setting['product_total_limit'] && ($setting['product_limit'] * ($page + 1) > $setting['product_total_limit']))
	    {
		    $setting['product_limit'] = $setting['product_total_limit'] - $setting['product_limit'] * $page;
	    }

	    if ($setting['product_limit'] < 1)
	    {
		    echo '';
		    return;
	    }

	    $limit = $setting['product_limit'] + $setting['product_limit'] * ($page - $current_page);
        $start = ($current_page - 1) * $setting['product_limit'] + intval($this->config->get('config_catalog_limit'));

        $products = $this->prepareProducts($setting, $start, $limit);

        ob_start();
        foreach ($products as $product)
        {
           $this->loadChromeTemplate($setting, $product);
        }

        $html = ob_get_contents();

        ob_end_clean();

	    $this->response->setOutput($html);
    }

    private function prepareOptions(array $setting, $start, $limit)
    {
        $options = array(
            'order' => 'DESC',
            'start' => $start,
            'limit' => $limit
        );

        if ($setting['product_order'] == 'latest')
        {
            $options['sort'] = 'p.date_added';
        }
        else if ($setting['product_order'] == 'last_updated')
        {
            $options['sort'] = 'p.date_modified';
        }
        else if ($setting['product_order'] == 'ascending')
        {
            $options['sort'] = 'pd.name';
            $options['order'] = 'ASC';
        }
        else if ($setting['product_order'] == 'descending')
        {
            $options['sort'] = 'pd.name';
        }

        // Prepare for category
        if ($setting['product_display'] == 'custom')
        {
            if (!empty($setting['categories']))
            {
                $options['filter_category_id'] = array();

                foreach ($setting['categories'] as $category_id)
                {
                    $options['filter_category_id'][] = $category_id;
                }
            }

            if (!empty($setting['products']))
            {
                $options['filter_product_id'] = array();

                foreach ($setting['products'] as $product_id)
                {
                    $options['filter_product_id'][] = $product_id;
                }
            }
        }

        return $options;
    }

    private function prepareProducts(array $setting, $start, $limit)
    {
        // Prepare options
        $options = $this->prepareOptions($setting, $start, $limit);

        $products = $this->model->getProducts($options);

        // Prepare options for each product
        if ($setting['product_options'])
        {
            foreach ($products as &$product)
            {
                $product['options'] = $this->model->getOptionsByProduct($product);
            }
        }

        return $this->model->prepareProducts($products, $setting);
    }

    public function loadChromeTemplate(array $setting, array $product)
    {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/includes/module_chrome_grid.tpl'))
        {
            $button_cart = $this->language->get('button_cart');
            $button_wishlist = $this->language->get('button_wishlist');
            $button_compare = $this->language->get('button_compare');
            $text_select = $this->language->get('text_select');
            $button_upload = $this->language->get('button_upload');

            include(DIR_TEMPLATE . $this->config->get('config_template') . '/includes/module_chrome_grid.tpl');
        }
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