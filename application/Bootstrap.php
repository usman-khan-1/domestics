<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_acl = null;
    protected function _initAutoload() {
        
        
        $modelLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH . '/modules/default'));
        
        if(Zend_Auth::getInstance()->hasIdentity()){
            Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);
        }  else {
            Zend_Registry::set('role', 'guests');
        }
        
        $this->_acl = new Model_ShopAcl;

        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Plugin_AccessCheck($this->_acl));
        return $modelLoader;
    }

    function _initViewHelper() {
        
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $view->doctype('HTML4_STRICT');

        $view->headMeta()->appendHttpEquiv('content-type', 'text/html;cahrset=utf-8')
                ->appendName('description', 'Using Zend view Helper');

        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Zend Tutorial');
        

        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        
        $navContainer = new Zend_Navigation($navContainerConfig);
        
        $view->navigation($navContainer)->setAcl($this->_acl)->setRole(Zend_Registry::get('role'));
    }

}

