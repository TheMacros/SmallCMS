<?php
class Form_Login extends Zend_Form{
    public function init(){
        $isAjax = false;
        // указываем атрибут Action для формы 
        $this->setAction( Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user-login'));
        // елси атрибут ajax указывался при создании формы
        // мы записываем true в переменную $isAjax
        if (isset($this->_attribs['ajax'])){
            if ($this->_attribs['ajax'] == true)
                $isAjax = true;
            // удаляем артибут ajax
            $this->removeAttrib('ajax');
        }
        // указываем декораторы
        $decors = array(
            'ViewHelper',   // декоратор элемента урпавления
            'Errors',       // декоратор для возможных ошибок
                // элемент input будет окружен в теги <li class="element">
                array(array('data'=>'HtmlTag'),array('tag'=>'li', 'class' => 'element')), 
                // указываем что для полей ввода будут использоваться так же поля label
                array('label'), 
                // элемент label будет окружен в теги <li class="element-label">    
                array(array('row'=>'HtmlTag'),array('tag'=>'li', 'class' => 'element-label')) 
        );
        // указываем атрибут class="login well" для формы
        $this->setAttrib('class', 'login well');
        // создем поля
        $email = $this->createElement('text', 'email');
        $email->setLabel('Email:')
                ->setRequired()
                ->addFilter('StripTags')
                ->setAttrib('placeholder', 'Enter your email')
                ->setDecorators($decors);
        $this->addElement($email);
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password:')
                ->setRequired()
                ->setAttrib('placeholder', 'Enter your password')
                ->setDecorators($decors);
        $this->addElement($password);
        
        // здесь меняем класс элемента li окружающего кнопку Login
        // если переменная $isAjax равна true
        // ставим class="form-actions clear submit"
        // если нет - class="form-actions no-ajax clear submit"
        // это нужно чтобы задать нужную ширину в css
        $decors = array(
            'ViewHelper',
            'Errors',
                array(array('data'=>'HtmlTag'),
                array('tag'=>'li', 'class' => $isAjax? 'form-actions clear submit' : 'form-actions no-ajax clear submit')),
        );
        
        $submit = $this->addElement('button', 'loginsubmit', array(
            'label' => 'Login', 
            'class' => 'btn btn-primary', 
            'type' => 'submit',
            'decorators' => $decors));
        // если это форма с аякс аутентификацией то создаем кнопку Cancel 
        // которая будет закрывать окно логина
        if ($isAjax){
            $decors = array(
                'ViewHelper',
                'Errors',
                    array(array('data'=>'HtmlTag'),
                    array('tag'=>'li', 'class' => 'form-actions cancel')),
            );
            $this->addElement('button', 'logincancel', array(
                'label'=>'Cancel', 
                'class'=>'btn',
                'decorators' => $decors));
        }
        
        $this->addDecorators(array(
                        'FormElements',
                        array(array('data'=>'HtmlTag', 'tag'=>'ul'),
                        array('tag'=>'ul','class'=>'login-form'))));
        
        $this->addDecorators(array('tag'=>'form'));
    }
}
