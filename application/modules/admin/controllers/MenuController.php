<?php
class Admin_MenuController extends Zend_Controller_Action{
    private $isAjax;
    
    public function init() {
        $this->isAjax = $this->_request->isXmlHttpRequest();
        if ($this->isAjax)
            $this->_helper->layout()->disableLayout();
    }
    
    public function indexAction(){
        $mdlMenu = new Model_Menu();
        $this->view->menus = $menus = $mdlMenu->getMenus();
    }
    
    public function addAction(){
        $frmMenu = new Form_Menu();
        if ($this->_request->isPost()){
            if ($frmMenu->isValid($this->_getAllParams())){
                $title = $frmMenu->getValue('title');
                $description = $frmMenu->getValue('description');
                $status = $frmMenu->getValue('status');
                $position = $frmMenu->getValue('position');
                $module = $frmMenu->getValue('render_module');
                $mdlMenu = new Model_Menu();
                if ($mdlMenu->addMenu($title, $position, $status, $module, $description)){
                    $this->_redirect('/admin/menu/');
                }
            }
        }
        $this->view->form = $frmMenu;
    }
    
    public function deleteItemAction(){
        if ($this->isAjax){
            $item_id = $this->_getParam('id');
            $mdlMenuItems = new Model_MenuItem();
            if ($mdlMenuItems->deleteItem($item_id))
                return $this->_helper->json(array('status' => 'ok'));
            else 
                return $this->_helper->json(array('status' => 'fail'));
        }
    }
    
    public function deleteAction(){
        if ($this->isAjax){
            $item_id = $this->_getParam('id');
            $mdlMenu = new Model_Menu();
            if ($mdlMenu->deleteMenu($item_id))
                return $this->_helper->json(array('status' => 'ok'));
            else 
                return $this->_helper->json(array('status' => 'fail'));
        }
    }
    
    public function addItemAction(){
        if ($this->isAjax){
            $this->_helper->viewRenderer->setNoRender();
            $filter = new Zend_Filter_StripTags();
            $menu_id = $this->_getParam('menu_id');
            $parent_id = $this->_getParam('parent_id');
            $title = $filter->filter($this->_getParam('title'));
            $link = $filter->filter($this->_getParam('link'));
            $icon = $filter->filter($this->_getParam('icon'));
            $mdlMenuItems = new Model_MenuItem();
            if ($res = $mdlMenuItems->addItem($menu_id, $title, $link, $icon, $parent_id)){
                $this->view->id = $res->id;
                $this->view->title = $res->title;
                $this->view->link = $res->link;
                $content = $this->view->render('_partials/menu-item.phtml');
                return $this->_helper->json(array('status' => 'ok', 'content' => $content));
            }
            
            return;
        }
    }
}