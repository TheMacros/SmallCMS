<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction(){
        
    }
    
    public function searchAction(){
        // получаем параметр запроса 'search'
        $query = $this->_getParam('search');
        
        if (strlen(trim($query)) == 0){
            $this->view->message = 'Search query is empty.<br/> Please enter some words';
        }else{
            $this->view->message = "<em><b>$query</b></em>" . ' found ' . rand(1, 10) . ' times';
        }
    }
}

