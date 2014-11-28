<?php
$paths = isset($this->request->get['path']) ? $this->request->get['path'] : '';
$paths = explode('_', $paths);

$categories = $this->model_catalog_category->getCategories(0);

$top_categories = array();

foreach ($categories as $category) {
	if ($category['top']) {
		// Level 2
		$children_data = array();

		$children = $this->model_catalog_category->getCategories($category['category_id']);

		foreach ($children as $child) {
			$data = array(
				'filter_category_id'  => $child['category_id'],
				'filter_sub_category' => true
			);

			$product_total = $this->model_catalog_product->getTotalProducts($data);

			$children_data[] = array(
				'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
				'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
				'category_id' => $child['category_id'],
				'path'  => $category['category_id'] . '_' . $child['category_id'],
				'active'   => isset($paths[1]) && $paths[1] == $child['category_id'] ? true : false
			);
		}

		// Level 1
		$top_categories[] = array(
			'category_id' => $category['category_id'],
			'name'	 => $category['name'],
			'children' => $children_data,
			'column'   => $category['column'] ? $category['column'] : 1,
			'href'	 => $this->url->link('product/category', 'path=' . $category['category_id']),
			'active'   => isset($paths[0]) && $paths[0] == $category['category_id'] ? true : false
		);
	}
}

$categories = $top_categories;

foreach ($categories as &$tcategory)
{
	foreach ($tcategory['children'] as &$sub_category)
	{
		$sub_category['children'] = getChildCategoryRecursive($this, $sub_category['category_id'], $sub_category['path'], 2, $paths);
	}
}


function getChildCategoryRecursive($controller, $category_id, $path, $depth, $paths)
{
	$categories = $controller->model_catalog_category->getCategories($category_id);

	$results = array();

	foreach ($categories as $category) {
		$data = array(
			'filter_category_id'  => $category['category_id'],
			'filter_sub_category' => true
		);

		$product_total = $controller->model_catalog_product->getTotalProducts($data);

		$new_path = $path . '_' . $category['category_id'];

		$results[] = array(
			'name'  => $category['name'] . ($controller->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
			'href'  => $controller->url->link('product/category', 'path=' . $new_path),
			'active' => isset($paths[$depth]) && $paths[$depth] == $category['category_id'] ? true : false,
			'children' => getChildCategoryRecursive($controller, $category['category_id'], $new_path, $depth + 1, $paths),
		);
	}

	return $results;
}

function renderSubMenuRecursive($categories) {
	$html = '<div class="dropdown-container"><div class="dropdown clearafter"><ul class="sublevel">';

	foreach ($categories as $category)
	{
		$parent = !empty($category['children']) ? ' parent' : '';
		$active = !empty($category['active']) ? ' active' : '';
		$html .= sprintf("<li class=\"item$parent $active\"><a href=\"%s\">%s</a>", $category['href'], $category['name']);

		if (!empty($category['children']))
		{
			$html .= '<span class="btn-expand-menu"></span>';
			$html .= renderSubMenuRecursive($category['children']);
		}

		$html .= '</li>';
	}

	$html .= '</ul></div></div>';

	return $html;
}
?>