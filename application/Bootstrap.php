<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
    public function _initView(){
        // создаем объект view 
        $view = new Zend_View();
        
        // указываем doctype соответствующий стандарту HTML5
        $view->doctype('HTML5');
        
        // указываем заголовок нашего приложения который будет выводиться в 
        // теге <title>. Так же здесь мы указываем что сначала будет ити заголовок страницы, 
        // а потом уже название сайта, например так: Регистрация пользователя::SmallCMS
        $view->headTitle('SmallCMS')->setDefaultAttachOrder(Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
        
        // указываем тег <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        
        // подключаем javascript файлы
        $view->headScript()->appendFile('/js/jquery-1.7.1.min.js');
        $view->headScript()->appendFile('/js/jquery-ui-1.8.17.custom.min.js');
        $view->headScript()->appendFile('/js/bootstrap.min.js');
        
        // подключаем пока единственный css файл
        $view->headLink()->appendStylesheet('/css/bootstrap.css');
        
        return $view;
    }
}

