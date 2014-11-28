<?php

/**
 * Class ModelModuleKulerProductList
 * @property ModelCatalogProduct $model_catalog_product
 * @property ModelToolImage $model_tool_image
 */
class ModelModuleKulerProductList extends Model
{
    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
    }

    public function getProducts(array $options)
    {
        if ($this->customer->isLogged())
        {
            $customer_group_id = $this->customer->getCustomerGroupId();
        }
        else
        {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $customer_group_id = intval($customer_group_id);

        $join_options = $this->prepareProductJoinOptions($options);
        $where = $this->prepareProductConditions($options);

        $order_by = isset($options['sort']) ? $options['sort'] : 'p.date_added DESC';

        $limit_clause = '';
        if (isset($options['start']))
        {
            $limit_clause .= 'LIMIT ' . intval($options['start']) . ', ' . intval($options['limit']);
        }

        $query = $this->db->query("
            SELECT p.product_id,
                (
                    SELECT AVG(rating) AS total
                    FROM " . DB_PREFIX . "review r1
                    WHERE r1.product_id = p.product_id
                        AND r1.status = '1' GROUP BY r1.product_id
                ) AS rating,
                (
                    SELECT price
                    FROM " . DB_PREFIX . "product_discount pd2
                    WHERE pd2.product_id = p.product_id
                        AND pd2.customer_group_id = $customer_group_id
                        AND pd2.quantity = '1'
                        AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
                        AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
                    ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1
                ) AS discount,
                (
                    SELECT price
                    FROM " . DB_PREFIX . "product_special ps
                    WHERE ps.product_id = p.product_id
                        AND ps.customer_group_id = $customer_group_id
                        AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
                        AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
                    ORDER BY ps.priority ASC, ps.price ASC
                    LIMIT 1
                ) AS special
                {$join_options['selected_fields']}
            FROM ". DB_PREFIX ."product p
            LEFT JOIN ". DB_PREFIX ."product_description pd
                ON (p.product_id = pd.product_id)
            LEFT JOIN ". DB_PREFIX ."product_to_store p2s
                ON (p.product_id = p2s.product_id)
            {$join_options['join_tables']}
            WHERE $where
            ORDER BY $order_by
            $limit_clause
        ");

        $products = array();
        foreach ($query->rows as $row)
        {
            $products[$row['product_id']] = $this->model_catalog_product->getProduct($row['product_id']);
        }

        return $products;
    }

    public function prepareProductConditions(array $options)
    {
        $sql_conditions = array(
            'pd.language_id = ' . intval($this->config->get('config_language_id')),
            'status = 1',
            'p.date_available <= NOW()',
            'p2s.store_id = ' . intval($this->config->get('config_store_id'))
        );

        $product_where = array();

        if (!empty($options['filter_category_id']))
        {
            $category_ids = array();

            foreach ($options['filter_category_id'] as $category_id)
            {
                $category_ids[] = intval($category_id);
            }

            $product_where[] = 'cp.path_id IN (' . implode(',', $category_ids) . ')';
        }

        if (!empty($options['filter_product_id']))
        {
            $product_ids = array();

            foreach ($options['filter_product_id'] as $product_id)
            {
                $product_ids[] = intval($product_id);
            }

            $product_where[] = 'p.product_id IN (' . implode(',', $product_ids) . ')';
        }

        if ($product_where)
        {
            $sql_conditions[] = '(' . implode(' OR ', $product_where) . ')';
        }

        return $sql_conditions ? implode(' AND ', $sql_conditions) : '1 = 1';
    }

    public function prepareProductJoinOptions(array $options)
    {
        $join_tables = '';
        $selected_fields = '';

        if (!empty($options['filter_category_id']))
        {
            $join_tables = '
                LEFT JOIN '. DB_PREFIX .'product_to_category p2c
                    ON (p.product_id = p2c.product_id)
                INNER JOIN '. DB_PREFIX .'category_path cp
                    ON (p2c.category_id = cp.path_id)
            ';
        }

        return array(
            'join_tables'       => $join_tables,
            'selected_fields'   => $selected_fields
        );
    }

    public function countProducts(array $options)
    {
        if ($this->customer->isLogged())
        {
            $customer_group_id = $this->customer->getCustomerGroupId();
        }
        else
        {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $customer_group_id = intval($customer_group_id);

        $join_options = $this->prepareProductJoinOptions($options);
        $where = $this->prepareProductConditions($options);

        $order_by = isset($options['sort']) ? $options['sort'] : 'p.date_added DESC';

        $query = $this->db->query("
            SELECT COUNT(p.product_id) AS product_total
            FROM ". DB_PREFIX ."product p
            LEFT JOIN ". DB_PREFIX ."product_description pd
                ON (p.product_id = pd.product_id)
            LEFT JOIN ". DB_PREFIX ."product_to_store p2s
                ON (p.product_id = p2s.product_id)
            {$join_options['join_tables']}
            WHERE $where
        ");

        return isset($query->row['product_total']) ? intval($query->row['product_total']) : 0;
    }

    public function getProductById($productId)
    {
        return $this->model_catalog_product->getProduct($productId);
    }

    public function getOptionsByProduct(array $product)
    {
        $options = $this->model_catalog_product->getProductOptions($product['product_id']);
        $results = array();

        foreach ($options as $option)
        {
            if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image')
            {
                $option_value_data = array();

                foreach ($option['option_value'] as $option_value)
                {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0))
                    {
                        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price'])
                        {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product['tax_class_id'], $this->config->get('config_tax')));
                        }
                        else
                        {
                            $price = false;
                        }

                        $option_value_data[] = array(
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id'         => $option_value['option_value_id'],
                            'name'                    => $option_value['name'],
                            'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
                            'price'                   => $price,
                            'price_prefix'            => $option_value['price_prefix']
                        );
                    }
                }

                $results[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id'         => $option['option_id'],
                    'name'              => $option['name'],
                    'type'              => $option['type'],
                    'option_value'      => $option_value_data,
                    'required'          => $option['required']
                );
            }
            else if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time')
            {
                $results[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_id'         => $option['option_id'],
                    'name'              => $option['name'],
                    'type'              => $option['type'],
                    'option_value'      => $option['option_value'],
                    'required'          => $option['required']
                );
            }
        }

        return $results;
    }

    public function prepareProducts(array $products, array $options)
    {
        foreach ($products as &$product)
        {
            $product = $this->prepareProduct($product, $options);
        }

        return $products;
    }

    public function prepareProduct(array $product, array $options)
    {
        if ($product['image'])
        {
            $image = $this->model_tool_image->resize($product['image'], $options['image_width'], $options['image_height']);
        }
        else
        {
            $image = false;
        }

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))
        {
            $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
        }
        else
        {
            $price = false;
        }

        if ((float)$product['special'])
        {
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
        }
        else
        {
            $special = false;
        }

        if ($this->config->get('config_review_status'))
        {
            $rating = $product['rating'];
        }
        else
        {
            $rating = false;
        }

        $product_categories = $this->model_catalog_product->getCategories($product['product_id']);
        $first_category_id = !empty($product_categories) ? $product_categories[0]['category_id'] : 0;

        $result = array(
            'product_id' => $product['product_id'],
            'image'      => $product['image'],
            'thumb'   	 => $image,
            'name'    	 => $product['name'],
            'description' => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $options['description_text']) . '..',
            'price'   	 => $price,
            'special' 	 => $special,
            'rating'     => $rating,
            'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
            'href'    	 => $this->url->link('product/product', 'path=' . $this->getRecursivePath($first_category_id) .'&product_id=' . $product['product_id']),
            'options'    => isset($product['options']) ? $product['options'] : array()
        );

        return $result;
    }

    public function getRecursivePath($category_id, $cats = array())
    {
        static $categories;

        if (empty($categories))
        {
            if (!empty($cats))
            {
                $raw_categories = $cats;
            }
            else
            {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

                $raw_categories = $query->rows;
            }

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
}