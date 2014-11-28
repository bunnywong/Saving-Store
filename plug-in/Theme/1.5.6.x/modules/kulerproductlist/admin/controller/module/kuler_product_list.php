<?php
/**
 * Class ControllerModuleKulerProductList
 * @property Config $config
 * @property Url $url
 * @property Request $request
 * @property Session $session
 * @property Document $document
 */
class ControllerModuleKulerProductList extends Controller
{
    /* @var ModelModuleKulerProductList $model */
    private $model;
    private $errors = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('module/kuler_product_list');
        $this->model = $this->model_module_kuler_product_list;

        $this->data['token'] = $this->session->data['token'];
        $this->data['__'] = $this->getLanguages();
    }

    public function index()
    {
        $this->data['breadcrumbs'] = $this->getPathways();
        $this->data['stores'] = $this->getStores();
        $this->data['selected_store_id'] = $this->getSelectedStore();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $this->save();
        }

        $this->getErrors();
        $this->data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
        unset($this->session->data['success']);

        $this->getResources();

        $this->data['action'] = $this->helperLink('module/kuler_product_list');
        $this->data['cancel'] = $this->helperLink('extension/module');

        $this->data['config_language_id'] = $this->config->get('config_language_id');

        $this->data['languages']                = $this->getLanguageOptions();
        $this->data['product_order_options']    = $this->getProductOrderOptions();

        $this->data['layouts'] = $this->getLayoutOptions();
        $this->data['positions'] = $this->getPositionOptions();
        $this->data['default_module'] = $this->getDefaultModule();

        $modules = array();
        if (isset($this->request->post['modules']))
        {
            $modules = $this->request->post['modules'];
        }
        else {
            $this->load->model('setting/setting');

            if ($kuler_product_list = $this->model_setting_setting->getSetting('kuler_product_list', $this->data['selected_store_id']))
            {
                if (isset($kuler_product_list['kuler_product_list_module']))
                {
                    $modules = $kuler_product_list['kuler_product_list_module'];

                    foreach ($modules as &$module)
                    {
                        $module = array_merge($this->data['default_module'], $module);
                    }
                }
            }
        }

        $this->data['modules'] = $this->prepareModules($modules);

        $this->data['category_auto_complete_url'] = $this->helperLink('catalog/category/autocomplete', array('store_id' => $this->data['selected_store_id']));
        $this->data['product_auto_complete_url'] = $this->helperLink('module/kuler_product_list/product_autocomplete', array('store_id' => $this->data['selected_store_id']));

        $this->document->setTitle($this->data['__']['heading_title']);

        $this->template = 'module/kuler_product_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    public function product_autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']))
        {
            $this->load->model('module/kuler_slides');

            $filter_name = $this->request->get['filter_name'];

            $data = array(
                'filter_name'  => $filter_name,
                'store_id'     => $this->request->get['store_id'],
            );

            $results = $this->model_module_kuler_slides->getProducts(array(
                    'filter_name' => $filter_name,
                    'store_id' => $this->request->get['store_id']
                ), array(
                    'start' => 0,
                    'limit' => 20
                )
            );

            foreach ($results as $result)
            {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function prepareModules($modules)
    {
        if (is_array($modules))
        {
            foreach ($modules as &$module)
            {
                $module['title'] = $this->translate($module['title']);
                $module['main_title'] = $module['title'][$this->config->get('config_language_id')];

                $module['products'] = isset($module['products']) ? html_entity_decode($module['products'], ENT_QUOTES, 'UTF-8') : '';
                $module['categories'] = isset($module['categories']) ? html_entity_decode($module['categories'], ENT_QUOTES, 'UTF-8') : '';
            }
        }

        return $modules;
    }

    private function getStores()
    {
        $this->load->model('setting/store');

        // Get stores
        $rows = $this->model_setting_store->getStores();

        $stores = array(
            0 => $this->config->get('config_name') . $this->language->get('text_default')
        );

        foreach ($rows as $row)
        {
            $stores[$row['store_id']] = $row['name'];
        }

        return $stores;
    }

    /**
     * Get selected store id from post or get
     */
    private function getSelectedStore()
    {
        $selected_store_id = 0;
        if (isset($this->request->post['store_id']))
        {
            $selected_store_id = $this->request->post['store_id'];
        }
        else if (isset($this->request->get['store_id']))
        {
            $selected_store_id = $this->request->get['store_id'];
        }

        return $selected_store_id;
    }

    private function getLanguages()
    {
        $__ = $this->language->load('module/kuler_product_list');

        return $__;
    }

    private function getPathways() {
        $breadcrumbs = array();

        $breadcrumbs[] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $breadcrumbs[] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        $breadcrumbs[] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/kuler_product_list', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        return $breadcrumbs;
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/kuler_product_list'))
        {
            $this->errors['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['modules']))
        {
            foreach ($this->request->post['modules'] as $module_index => $module)
            {
                if (!$module['image_width'] || !$module['image_height'])
                {
                    $this->errors['image_dimension'] = array(
                        $module_index => $this->data['__']['error_image_dimension']
                    );

                    break;
                }
            }
        }

        return !$this->errors ? true : false;
    }

    private function getErrors()
    {
        $this->data['error_warning'] = '';

        $this->data['error_product_image_dimension'] = isset($this->errors['image_dimension']) ? $this->errors['image_dimension'] : array();

        if ($this->errors)
        {
            $this->data['error_warning'] = $this->data['__']['error_warning'];
        }

        if (isset($this->errors['warning']))
        {
            $this->data['error_warning'] = $this->errors['warning'];
        }
    }

    private function getResources()
    {
        $this->document->addStyle('view/kulercore/css/kulercore.css');
        $this->document->addScript('view/kulercore/js/handlebars.js');
    }

    private function getDefaultModule()
    {
        return array(
            'title' => '',
            'show_title' => 1,
            'layout_id' => 1,
            'position' => 'content_top',
            'status' => 1,
            'sort_order' => '',

            'list_type' => 'all',
            'product_order' => 'latest',
            'product_total_limit' => '',

            // Product
            'image_width' => 155,
            'image_height' => 155,
            'name' => 1,
            'price' => 1,
            'rating' => 1,
            'description' => 1,
            'add' => 1,
            'wishlist' => 1,
            'compare' => 1,
            'description_text' => 100,

            'product_options' => 1
        );
    }

    private function save()
    {
        $this->load->model('setting/setting');

        $this->request->post['modules'] = isset($this->request->post['modules']) ? $this->request->post['modules'] : array();

        $data = array(
            'kuler_product_list_module' => $this->request->post['modules']
        );

        $this->model_setting_setting->editSetting('kuler_product_list', $data, $this->request->post['store_id']);

        $this->session->data['success'] = $this->data['__']['text_success'];

        if (isset($this->request->post['op']) && $this->request->post['op'] == 'close')
        {
            $this->redirect($this->helperLink('extension/module'));
        }
        else
        {
            $this->redirect($this->helperLink('module/kuler_product_list', array('store_id' => $this->request->post['store_id'])));
        }
    }

    private function getProductOrderOptions()
    {
        return array(
            'latest' => $this->data['__']['text_latest'],
            'last_updated' => $this->data['__']['text_last_updated'],
            'ascending' => $this->data['__']['text_ascending'],
            'descending' => $this->data['__']['text_descending']
        );
    }

    private function getLanguageOptions()
    {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $config_language = $this->config->get('config_language');

        $results = array();
        $default_language = $languages[$config_language];
        unset($languages[$config_language]);

        $results[$config_language] = $default_language;
        $results = array_merge($results, $languages);

        return $results;
    }

    private function getLayoutOptions()
    {
        $this->load->model('design/layout');
        $result = $this->model_design_layout->getLayouts();
        return $result;
    }

    private function getPositionOptions()
    {
        return array(
            'content_top' => $this->data['__']['text_content_top'],
            'content_bottom' => $this->data['__']['text_content_bottom'],
            'column_left' => $this->data['__']['text_column_left'],
            'column_right' => $this->data['__']['text_column_right']
        );
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kuler_product_list', $store_id);
        }
    }

    private function helperLink($route, array $params = array())
    {
        $params['token'] = $this->data['token'];

        return $this->url->link($route, http_build_query($params), 'SSL');
    }

    private function translate($texts)
    {
        $languages = $this->getLanguageOptions();

        if (is_string($texts))
        {
            $text = $texts;
            $texts = array();

            foreach ($languages as $language)
            {
                $texts[$language['language_id']] = $text;
            }
        }
        else if (is_array($texts))
        {
            $first = current($texts);

            foreach ($languages as $language)
            {
                if (is_string($first))
                {
                    if (empty($texts[$language['language_id']]))
                    {
                        $texts[$language['language_id']] = $first;
                    }
                }
                else if (is_array($first))
                {
                    if (!isset($texts[$language['language_id']]))
                    {
                        $texts[$language['language_id']] = array();
                    }

                    foreach ($first as $key => $val)
                    {
                        if (empty($texts[$language['language_id']][$key]))
                        {
                            $texts[$language['language_id']][$key] = $val;
                        }
                    }
                }
            }
        }

        return $texts;
    }
}