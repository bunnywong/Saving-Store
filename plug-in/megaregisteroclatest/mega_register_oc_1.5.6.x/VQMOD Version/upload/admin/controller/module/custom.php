<?php
class ControllerModuleCustom extends Controller {
	private $error = array();  
 
	public function index() {
		$this->language->load('module/custom');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/custom');
		$this->model_module_custom->install();
		
		$this->getList();
	}

	public function insert() {
		$this->language->load('module/custom');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/custom');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_custom->addOption($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('module/custom');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('module/custom');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_module_custom->editOption($this->request->get['option_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('module/custom');

		$this->document->setTitle($this->language->get('heading_title'));
 		
		$this->load->model('module/custom');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_module_custom->deleteOption($option_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'od.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
			
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('module/custom/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('module/custom/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		 
		$this->data['options'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$option_total = $this->model_module_custom->getTotalOptions();
		
		$results = $this->model_module_custom->getOptions($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('module/custom/update', 'token=' . $this->session->data['token'] . '&option_id=' . $result['option_id'] . $url, 'SSL')
			);

			$this->data['options'][] = array(
				'option_id'  => $result['option_id'],
				'name'       => $result['name'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['option_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');	

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->link('module/custom', 'token=' . $this->session->data['token'] . '&sort=od.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('module/custom', 'token=' . $this->session->data['token'] . '&sort=o.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $option_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'module/custom_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_choose'] = $this->language->get('text_choose');
		$this->data['text_select'] = $this->language->get('text_select1');
		$this->data['text_radio'] = $this->language->get('text_radio');
		$this->data['text_checkbox'] = $this->language->get('text_checkbox');
		
		$this->data['text_input'] = $this->language->get('text_input');
		$this->data['text_text'] = $this->language->get('text_text');
		$this->data['text_textarea'] = $this->language->get('text_textarea');
		$this->data['text_file'] = $this->language->get('text_file');
		$this->data['text_date'] = $this->language->get('text_date');
		$this->data['text_datetime'] = $this->language->get('text_datetime');
		$this->data['text_time'] = $this->language->get('text_time');
		
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');	
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_option_value'] = $this->language->get('entry_option_value');
		
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_numeric'] = $this->language->get('entry_numeric');
		$this->data['entry_min_length'] = $this->language->get('entry_min_length');
		$this->data['entry_max_length'] = $this->language->get('entry_max_length');
		$this->data['entry_invoice'] = $this->language->get('entry_invoice');
		$this->data['entry_error_message'] = $this->language->get('entry_error_message');
		$this->data['entry_mask'] = $this->language->get('entry_mask');
		$this->data['entry_identifier'] = $this->language->get('entry_identifier');
		$this->data['entry_option_identifier'] = $this->language->get('entry_option_identifier');
		$this->data['entry_section'] = $this->language->get('entry_section');
		$this->data['entry_tips'] = $this->language->get('entry_tips');
		$this->data['entry_more_attributes'] = $this->language->get('entry_more_attributes');
		$this->data['entry_visibility'] = $this->language->get('entry_visibility');
		$this->data['entry_email_vis'] = $this->language->get('entry_email_vis');
		$this->data['entry_order_info_vis'] = $this->language->get('entry_order_info_vis');
		$this->data['entry_address_list_vis'] = $this->language->get('entry_address_list_vis');
		$this->data['entry_isenable'] = $this->language->get('entry_isenable');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}
		
		if (isset($this->error['identifier'])) {
			$this->data['error_identifier'] = $this->error['identifier'];
		} else {
			$this->data['error_identifier'] = "";
		}

		if (isset($this->error['error'])) {
			$this->data['error_msg'] = $this->error['error'];
		} else {
			$this->data['error_msg'] = array();
		}	
				
 		if (isset($this->error['option_value'])) {
			$this->data['error_option_value'] = $this->error['option_value'];
		} else {
			$this->data['error_option_value'] = array();
		}	

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['option_id'])) {
			$this->data['action'] = $this->url->link('module/custom/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else { 
			$this->data['action'] = $this->url->link('module/custom/update', 'token=' . $this->session->data['token'] . '&option_id=' . $this->request->get['option_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('module/custom', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_module_custom->getOption($this->request->get['option_id']);
    	}
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['option_msg'])) {
			$this->data['option_msg'] = $this->request->post['option_msg'];
		} elseif (isset($this->request->get['option_id'])) {
			$this->data['option_msg'] = $this->model_module_custom->getOptionErrors($this->request->get['option_id']);
		} else {
			$this->data['option_msg'] = array();
		}
		
		if (isset($this->request->post['tips_msg'])) {
			$this->data['tips_msg'] = $this->request->post['tips_msg'];
		} elseif (isset($this->request->get['option_id'])) {
			$this->data['tips_msg'] = $this->model_module_custom->getTipsMsgs($this->request->get['option_id']);
		} else {
			$this->data['tips_msg'] = array();
		}
		

		if (isset($this->request->post['option_description'])) {
			$this->data['option_description'] = $this->request->post['option_description'];
		} elseif (isset($this->request->get['option_id'])) {
			$this->data['option_description'] = $this->model_module_custom->getOptionDescriptions($this->request->get['option_id']);
		} else {
			$this->data['option_description'] = array();
		}

		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (!empty($option_info)) {
			$this->data['type'] = $option_info['type'];
		} else {
			$this->data['type'] = '';
		}
		
		if (isset($this->request->post['mask'])) {
			$this->data['mask'] = $this->request->post['mask'];
		} elseif (!empty($option_info)) {
			$this->data['mask'] = $option_info['mask'];
		} else {
			$this->data['mask'] = '';
		}
		
		if (isset($this->request->post['section'])) {
			$this->data['section'] = $this->request->post['section'];
		} elseif (!empty($option_info)) {
			$this->data['section'] = $option_info['section'];
		} else {
			$this->data['section'] = '';
		}
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($option_info)) {
			$this->data['sort_order'] = $option_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['identifier'])) {
			$this->data['identifier'] = $this->request->post['identifier'];
		} elseif (!empty($option_info)) {
			$this->data['identifier'] = $option_info['identifier'];
		} else {
			$this->data['identifier'] = '';
		}
		
		if (isset($this->request->post['required'])) {
			$this->data['required'] = $this->request->post['required'];
		} elseif (!empty($option_info)) {
			$this->data['required'] = $option_info['required'];
		} else {
			$this->data['required'] = '';
		}
		
		if (isset($this->request->post['isnumeric'])) {
			$this->data['isnumeric'] = $this->request->post['isnumeric'];
		} elseif (!empty($option_info)) {
			$this->data['isnumeric'] = $option_info['isnumeric'];
		} else {
			$this->data['isnumeric'] = '';
		}
		
		if (isset($this->request->post['min'])) {
			$this->data['min'] = $this->request->post['min'];
		} elseif (!empty($option_info)) {
			$this->data['min'] = $option_info['minimum'];
		} else {
			$this->data['min'] = '';
		}
		
		if (isset($this->request->post['max'])) {
			$this->data['max'] = $this->request->post['max'];
		} elseif (!empty($option_info)) {
			$this->data['max'] = $option_info['maximum'];
		} else {
			$this->data['max'] = '';
		}
		
		if (isset($this->request->post['invoice'])) {
			$this->data['invoice'] = $this->request->post['invoice'];
		} elseif (!empty($option_info)) {
			$this->data['invoice'] = $option_info['invoice'];
		} else {
			$this->data['invoice'] = '';
		}
		
		if (isset($this->request->post['email_display'])) {
			$this->data['email_display'] = $this->request->post['email_display'];
		} elseif (!empty($option_info)) {
			$this->data['email_display'] = $option_info['email_display'];
		} else {
			$this->data['email_display'] = '';
		}
		
		if (isset($this->request->post['order_display'])) {
			$this->data['order_display'] = $this->request->post['order_display'];
		} elseif (!empty($option_info)) {
			$this->data['order_display'] = $option_info['order_display'];
		} else {
			$this->data['order_display'] = '';
		}
		
		if (isset($this->request->post['list_display'])) {
			$this->data['list_display'] = $this->request->post['list_display'];
		} elseif (!empty($option_info)) {
			$this->data['list_display'] = $option_info['list_display'];
		} else {
			$this->data['list_display'] = '';
		}
		
		if (isset($this->request->post['isenable'])) {
			$this->data['isenable'] = $this->request->post['isenable'];
		} elseif (!empty($option_info)) {
			$this->data['isenable'] = $option_info['isenable'];
		} else {
			$this->data['isenable'] = '';
		}
		
		if (isset($this->request->post['option_value'])) {
			$option_values = $this->request->post['option_value'];
		} elseif (isset($this->request->get['option_id'])) {
			$option_values = $this->model_module_custom->getOptionValueDescriptions($this->request->get['option_id']);
		} else {
			$option_values = array();
		}
		
		
		
		$this->data['option_values'] = array();
		 
		foreach ($option_values as $option_value) {
		
			
			$this->data['option_values'][] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value['option_value_description'],
				
				
				'sort_order'               => $option_value['sort_order']
			);
		}

		

		$this->template = 'module/custom_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'module/custom')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['option_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		
		if ((utf8_strlen($this->request->post['identifier']) < 1)) {
				$this->error['identifier'] = $this->language->get('error_identifier');
			}
		
		foreach ( $this->request->post['option_msg'] as $language_id => $value) {
			if (isset($this->request->post['required']) && (utf8_strlen($value['error']) < 1) || (utf8_strlen($value['error']) > 240)) {
				$this->error['error'][$language_id] = $this->language->get('error_msg');
			}
		}

		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox') && !isset($this->request->post['option_value'])) {
			$this->error['warning'] = $this->language->get('error_type');
		}

		if (isset($this->request->post['option_value'])) {
			foreach ($this->request->post['option_value'] as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
						$this->error['option_value'][$option_value_id][$language_id] = $this->language->get('error_option_value'); 
					}					
				}
			}	
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'module/custom')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
}
?>