<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;

use Opencart\System\Engine\Controller;

class Chatbot extends Controller {
    public function index() {
        // Load language file for the chatbot module
        $this->load->language('extension/opencart/module/chatbot');

        // Set the page title in the browser
        $this->document->setTitle($this->language->get('heading_title'));

        // Initialize breadcrumbs for navigation
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/opencart/module/chatbot', 'user_token=' . $this->session->data['user_token'])
        ];

        // Handle form data
        $data['save'] = $this->url->link('extension/opencart/module/chatbot.save', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        // Get the current status from the configuration
        $data['module_chatbot_status'] = $this->config->get('module_chatbot_status');

        // Load common components (header, footer, column left)
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Load the view file for the chatbot settings
        $this->response->setOutput($this->load->view('extension/opencart/module/chatbot', $data));
    }

    public function save() {
        $this->load->language('extension/opencart/module/chatbot');

        $json = [];

        // Check if the user has permission to modify the module
        if (!$this->user->hasPermission('modify', 'extension/opencart/module/chatbot')) {
            $json['error'] = $this->language->get('error_permission');
        }

        // Save the module settings if no errors
        if (!$json) {
            $this->load->model('setting/setting');

            // Save the settings of the chatbot module
            $this->model_setting_setting->editSetting('module_chatbot', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        // Return the response as JSON
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
