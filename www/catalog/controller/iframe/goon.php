<?php
class ControllerIframeGoon extends Controller {
  public function index() {
    // set title of the page
    $this->document->setTitle("My Custom Page");

    // define template file
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/iframe/goon.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/iframe/goon.tpl';
    } else {
      $this->template = 'default/template/iframe/goon.tpl';
    }

    // define children templates
    $this->children = array(
      'common/column_left',
      'common/column_right',
      'common/content_top',
      'common/content_bottom',
      'common/footer',
      'common/header'
    );

    $this->data['base'] = $this->config->get('config_url');

    // call the "View" to render the output
    $this->response->setOutput($this->render());

  }
}
?>