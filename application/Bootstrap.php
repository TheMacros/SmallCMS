<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
    
    protected function _initAutoLoad(){
        // получаем экземпляр класса Zend_Loader_Autoloader
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        
        // регистрируем пространство имен SmallCMS_
        $autoLoader->registerNamespace('SmallCMS_');
        
        // указываем в загрузчике ресурсов нужные нам параметры
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH,
            'namespace'     => '',
            // тут перечисляются ресурсы, такие как формы, модули и так далее...
            'resourceTypes' => array(
                'form' => array(
                    // тут мы указываем, что автолоадер будет искать классы с именами
                    // которые начинаются на Form_ в папке application/forms/
                    'path'      => 'forms/', 
                    'namespace' => 'Form_',
                ), 
                'model' => array(
                    // указываем, что автолоадер будет искать классы с именами
                    // которые начинаются на Model_ в папке application/models/
                    'path'      => 'models/',
                    'namespace' => 'Model_'
                )
            ),
        ));
        return $autoLoader;
    }
    
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
        $view->headScript()->appendFile('/js/index.js');
        
        // подключаем пока единственный css файл
        $view->headLink()->appendStylesheet('/css/bootstrap.css');
        $view->headLink()->appendStylesheet('/css/index.css');
        
        return $view;
    }
}