<?php
class ControllerModuleSignup extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->language->load('module/signup');

		$this->document->setTitle($this->language->get('heading_title'));
                 $this->load->model('module/signup');
                $this->model_module_signup->install();
		
		
               
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_module_signup->editSetting( $this->request->post);
                        
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('module/signup', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		$this->data['token'] = $this->session->data['token'];
		$this->data['button_save'] = "Save & Stay";
		$this->data['button_cancel'] = "Back to Modules";

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/signup', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/signup', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
				
		$this->data['modules'] = array();
		
		if (isset($this->request->post['signup_module'])) {
			$this->data['modules'] = $this->request->post['signup_module'];
		} elseif ($this->config->get('signup_module')) { 
			$this->data['modules'] = $this->config->get('signup_module');
		}		
		
		$this->load->model('module/signup');
		
		$this->data['isModActive'] = $this->model_module_signup->isActiveMod();
                $this->data['modData']  = $this->model_module_signup->getModData();

		$this->template = 'module/signup.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/signup')) {
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