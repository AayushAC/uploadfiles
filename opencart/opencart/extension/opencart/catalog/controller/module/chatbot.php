<?php
class ControllerExtensionModuleChatbot extends Controller {
    public function index() {
        return $this->load->view('\extension\opencart\catalog\controller\module');
    }
}
