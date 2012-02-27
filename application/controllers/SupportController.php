<?php
class SupportController extends Zend_Controller_Action {
    public function submitBugAction(){
        // создаем форму
        $frmBug = new Form_Bug();
        // удаляем поле status из формы
        $frmBug->removeElement('status');
        // проверяем это POST запрос или нет
        if ($this->_request->isPost()){
            // проверяем валидны ли данные в форме
            if ($frmBug->isValid($this->_request->getParams())){
                // создаем экземпляр класса Model_Bug
                $mdlBugs = new Model_Bug();
                // вызываем метод который вставляет запись в БД
                $mdlBugs->insertBug($frmBug->getValues());
                // передаем в layout переменную message c произвольным текстом
                $this->_helper->layout()->message = 'Thanks for submitting a ' . $this->_request->getParam('type') .'!';
                // а так же класс сообщения (в данном случае sucess)
                $this->_helper->layout()->message_class = 'alert-success';
                // делаем форвард на главную страницу
                return $this->_forward('index','index');
            }else{
                $this->view->form = $frmBug;
            }
        }
        $this->view->form = $frmBug;
    }
    
    public function bugListAction(){
        $mdlBugs = new Model_Bug();
        $this->view->bugs = $mdlBugs->getBugs();
    }
}