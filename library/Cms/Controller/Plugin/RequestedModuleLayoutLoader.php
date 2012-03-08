<?php
class Cms_Controller_Plugin_RequestedModuleLayoutLoader extends Zend_Controller_Plugin_Abstract{
    public function preDispatch( Zend_Controller_Request_Abstract $request){
        // получаем конфигурацию нашего приложения
        $config = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getOptions();
        // получаем имя запрашиваемого модуля
        $moduleName = $request->getModuleName();
        // если это модуль 'admin'
        if ($moduleName == 'admin'){
            $auth = Zend_Auth::getInstance();
            // если никто не залогинился или это не админ, редиректим на форму логина
            if (!$auth->hasIdentity() || $auth->getIdentity()->role != 'admin'){
                $redirector = new Zend_Controller_Action_Helper_Redirector();
                $redirector->direct('login', 'user', 'default');
            }
            // также здесь мы можем подключать специфические для разных модулей 
            // файлы css и яваскрипт
            // в частности для резинового дизайна нам желательно подключить
            // файл bootstrap-responsive.css
            $view = new Zend_View();
            $view->headLink()->appendStylesheet('/css/bootstrap-responsive.css');
        }
        // если конфиг содержит параметр module_name.resources.layout.layout
        if (isset($config[$moduleName]['resources']['layout']['layout'])) {
            $layoutScript = $config[$moduleName]['resources']['layout']['layout'];
            // то устанавливаем указанный там layout
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        }
        // если конфиг содержит параметр module_name.resources.layout.layoutPath
        if (isset($config[$moduleName]['resources']['layout']['layoutPath'])) {
            $layoutPath = $config[$moduleName]['resources']['layout']['layoutPath'];
            // то устанавливаем указанный там путь к layout файлу
            Zend_Layout::getMvcInstance()->setLayoutPath($layoutPath);
        }
    }
}

?>
