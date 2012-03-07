<?php
header('Content-Type: text/html; charset=UTF-8');
// Объявляем константу APPLICATION_PATH - это путь к папке application
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Объявляем константу APPLICATION_ENV - prodution или development
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// добавляем папку /library к include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Создаем приложение, инициализуруем и запускаем его
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
// получаем роутер
$router = Zend_Controller_Front::getInstance()->getRouter();
// добавляем маршрут для действия /user/login
$router->addRoute(
        // имя маршрута 
        // (потом может использоваться в хелпере url() в скриптах вида)
        'user-login',
        new Zend_Controller_Router_Route(
                '/login/',                  // новый маршрут
                array(
                    'controller' => 'user', // контроллер
                    'action' => 'login'     // экшен
                )
        )
);
$router->addRoute(
        'user-logout',
        new Zend_Controller_Router_Route(
                '/logout/', 
                array(
                    'controller' => 'user', 
                    'action' => 'logout'
                )
        )
);
$application->run();