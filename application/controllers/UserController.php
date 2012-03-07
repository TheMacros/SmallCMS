<?php
class UserController extends Zend_Controller_Action{
    // эту переменную мы будем использовать вместо $this->_request->isXmlHttpRequest();
    private $isAjax;
    
    public function init(){
        $this->isAjax = $this->_request->isXmlHttpRequest();
        if ($this->isAjax)
            // если это аякс запрос то отключаем layout
            $this->_helper->layout()->disableLayout();
    }
    
    public function loginAction(){
        // создаем форму логина
        $frmLogin = new Form_Login();
        if ($this->isAjax)
            // если аякс отключаем скрипт вида
            // таким образом мы будем использовать один action для 
            // аякс запросов и для прямого обращения к этому экшену
            $this->_helper->ViewRenderer->setNoRender();
        // если это POST запрос
        if ($this->_request->isPost()){
            // если данные формы корректны
            if ($frmLogin->isValid($this->_getAllParams())){
                // получаем данные формы
                $email = $frmLogin->getElement('email')->getValue();
                $password = $frmLogin->getElement('password')->getValue();
                // задаем тип адаптера
                $db = Zend_Db_Table::getDefaultAdapter();
                // создаем объект адаптера аутентификации
                // первый параметр - объект Zend_Db_Adapter_Abstract
                // второй - имя таблицы в базе данных где находятся данные об аутентификации
                // третий - identityColumn - поле логина
                // четвертый - credentialColumn - поле пароля
                $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users', 'email', 'password');
                // задаем параметры аутентификации - логин и пароль
                $authAdapter->setIdentity($email);
                $authAdapter->setCredential(md5($password));
                // пробуем аутентифицировать пользователя
                $result = $authAdapter->authenticate();
                // если есть пользователь с такими данными и аутентификация прошла успешно
                if($result->isValid()){
                    // сохраняем данные об аутентификации в сессии
                    $storage = Zend_Auth::getInstance()->getStorage();
                    $storage->write($authAdapter->getResultRowObject(
                            // здесь указываем какие поля из таблицы users мы сохраним в сессии
                            // эти поля пригодятся нам в layout front.phtml
                            // например для вывода имени текущего пользователя
                        array('first_name', 'last_name', 'role', 'email', 'id')));
                    // если это аякс запрос возвращаем строку в формате json
                    // {"status": "ok"}
                    if ($this->isAjax)
                        return $this->_helper->json(array('status' => 'ok'));
                    // если это не аякс делаем редирект на главную страницу
                    $this->_redirect($this->view->baseUrl());
                // если аутентификация не прошла
                }else{
                    // если это аякс запрос возвращаем строку в формате json
                    // {"status": "fail", "message": "Incorect email or password"}
                    if ($this->isAjax)
                        return $this->_helper->json(array('status' => 'fail', 'message' => 'Incorect email or password'));
                    // если не аякс - передаем в layout переменную message и message_class
                    $this->view->layout()->message = 'Incorect email or password';
                    $this->view->layout()->message_class = 'alert-error';
                }
            // если данные формы не корректы (поля пустые)
            }else{
                // если аякс запрос возвращаем строку 
                // {"status": "fail", "message": "Fields cannot be empty"}
                if ($this->isAjax)
                    return $this->_helper->json(array('status' => 'fail', 'message' => 'Fields cannot be empty'));
            }
        }
        // передаем в переменную скрипта вида нашу форму
        $this->view->form = $frmLogin;
    }
    
    public function logoutAction(){
        // запрещаем использовать скрипт вида
        $this->_helper->ViewRenderer->setNoRender();
        // запрещаем использовать layout
        $this->_helper->layout()->disableLayout();
        // получаем экземпляр класса Zend_Auth
        $auth = Zend_Auth::getInstance();
        // елси кто-то залогинен разлогиниваем
        if ($auth->hasIdentity())
            $auth->clearIdentity ();
        // делаем редирект на главную страницу
        $this->_redirect($this->view->baseUrl());
    }
}