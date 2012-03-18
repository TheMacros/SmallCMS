<?php
class Cms_View_Helper_Menu extends Zend_View_Helper_Abstract{
    public function Menu($position, $module){
        $mdlMenu = new Model_Menu();
        if ($menu = $mdlMenu->getMenuByPosition($position, $module)){
            $mdlMenuItem = new Model_MenuItem();
            $items = $mdlMenuItem->getItemsByMenu($menu->id);
            $view = new Zend_View();
            $view->items = $items;
            $view->position = $position;
            if ($position == 'left' || $position == 'right')
                $view->addClass = ' nav-list';
            $view->setScriptPath(dirname(__FILE__) . '/menu/');
            $html = $view->render('menu.phtml');
            
            return $html;    
        }
        
        
    }
}
