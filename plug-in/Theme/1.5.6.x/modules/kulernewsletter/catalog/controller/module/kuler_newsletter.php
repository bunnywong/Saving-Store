<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

require_once(DIR_APPLICATION . 'model/module/kuler_newsletter/kuler_newsletter.php');

class ControllerModuleKulerNewsletter extends Controller {
    protected $error = '';
    protected $success = '';
    public function index($setting) {
        $this->language->load('module/kuler_newsletter');

        $setting['module_title'] = $this->translate($setting['module_title'], $this->config->get('config_language_id'));
        $setting['pre_text'] = $this->translate($setting['pre_text'], $this->config->get('config_language_id'));
        $setting['email_text'] = $this->translate($setting['email_text'], $this->config->get('config_language_id'));

    	$this->data['module_title'] = isset($setting['module_title']) ? $setting['module_title'] : $this->language->get('heading_title');
		$this->data['show_title'] = isset($setting['show_title']) ? $setting['show_title'] : 1;
		$this->data['setting'] = $setting;
        
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_newsletter.phtml')) {
			$this->template = $this->config->get('config_template') . '/template/module/kuler_newsletter.phtml';
		} else {
			$this->template = 'default/template/module/kuler_newsletter.phtml';
		}
        
        $this->data['text_error'] = $this->language->get('error_email');

        if (isset($setting['subscription_success_message']))
        {
            $this->data['text_subscription_success_message'] = $this->translate($setting['subscription_success_message'], $this->config->get('config_language_id'));
        }
        else
        {
            $this->data['text_subscription_success_message'] = $this->language->get('text_subscription_success_message');
        }

        $this->data['subscribe_url'] = $this->url->link('module/kuler_newsletter/subscribe');

        $this->data['shortcode'] = $setting['shortcode'];

        $this->render();
	}

    public function subscribe()
    {
        $json = array(
            'status' => 1
        );

	    $this->language->load('module/kuler_newsletter');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
            $short_code = $this->request->post['shortcode'];

            $modules = $this->config->get('kuler_newsletter_module');

            $setting = array();

            foreach ($modules as $module)
            {
                if ($module['shortcode'] == $short_code)
                {
                    $setting = $module;
                }
            }

            if (isset($setting['type']) && method_exists($this, $setting['type'] . 'Action'))
            {
                if (isset($setting[$setting['type']]))
                {
                    $setting = $setting + $setting[$setting['type']];
                }
                
                $this->{$setting['type'] . 'Action'}($setting);
            }
        }
        
        if ($this->error)
        {
            $json['status'] = 0;
            $json['message'] = $this->error;
        }
        
        $this->response->setOutput(json_encode($json));
    }
    
    protected function aweberAction($setting) {
        
    }
    
    protected function icontactAction($setting) {
        if(empty($setting['key']) || empty($setting['username']) || empty($setting['password'])) {
            $this->error = $this->language->get('error_setting'); 
            return false;
        }
        
        // Give the API your information
        iContactApi::getInstance()->setConfig(array(
            'appId' => $setting['key'],
            'apiUsername' => $setting['username'],
            'apiPassword' => $setting['password'],
        ));

        // Store the singleton
        $oiContact = iContactApi::getInstance();
        
        // Try to make the call(s)
        try {
            // Create a contact
            $result = $oiContact->addContact($this->request->post['subscribe']);
            $result = $oiContact->subscribeContactToList($result->contactId, $setting['list_id'], 'normal');
            if($result) {
                $this->success = $this->language->get('success');
            } else {
                $this->error = $this->language->get('error_setting'); 
            }
        } catch (Exception $oException) {
            $this->error = $this->language->get('error_unknow'); 
        }
    }
    
    protected function mailchimpAction($setting) {
        // Check mailchimp setting
        if(empty($setting['key'])) {
            $this->error = $this->language->get('error_setting'); 
            return false;
        }
        
        $api = new MCAPI($setting['key']);
        
        // Check mailchimp init
        if(empty($api)) {
            $this->error = $this->language->get('error_mailchimp'); 
            return false;
        }
        
        // Check mailchimp list_id
        if(empty($setting['list_id'])) {
            $this->error = $this->language->get('error_mailchimp_list'); 
        } else {
            $mail = $this->request->post['subscribe'];
            $merge = array(
                'FNAME' => 'Email',
                'LNAME' => ' :' . $mail,
            );

            $retval = $api->listSubscribe($setting['list_id'], $mail, $merge);

            if ($api->errorCode) {
	            switch ($api->errorCode)
	            {
		            case 214:
			            $this->error = sprintf($this->language->get('error_mail_existing'), $mail);
			            break;
		            default:
			            $this->error = $api->errorCode . ' - ' . $api->errorMessage;
	            }

            } else {
                $this->success = $this->language->get('success');
            }
        }
    }
    
    protected function validate() {
        $mail = isset($this->request->post['subscribe']) ? $this->request->post['subscribe'] : '';
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            $this->error = $this->language->get('error_email'); 
            return false;
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
?>