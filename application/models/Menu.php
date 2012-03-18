<?php
class Model_Menu extends Zend_Db_Table_Abstract{
    // таблица в базе с именем menu
    protected $_name = 'menu';
    // класс таблицы в которой находятся связанные данные
    protected $_dependentTables = array('Model_MenuItem');
    // карта связей нужна нам в частности для каскадного удаления данных 
    // например если мы удаляем меню, то и пункты этого меню тоже нужно удалить из
    // таблицы menu_item
    protected $_referenceMap = array(
        'Menu'  => array(
            'columns'       => array('parent_id'),
            'refTableClass' => 'Model_Menu',
            'refColumns'    => array('id'),
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        )
    );
    
    // добавление меню
    public function addMenu($title, $position, $status, $module, $description = ''){
        $menu = array(
            'title'         => $title,
            'position'      => $position,
            'status'        => $status,
            'description'   => $description,
            'module'        => $module
        );
        return $this->insert($menu);
    }
    // обновление меню
    public function updateMenu($id, $title, $position, $status, $description){
        if ($row = $this->find($id)->current()){
            $row->title = $title;
            $row->position = $position;
            $row->status = $status;
            $row->description = $description;
            return $row->save();
        }
    }
    // изменение статуса меню
    // например deleted enabled или disabled
    public function changeMenuStatus($id, $status){
        return $this->update(array(
            'status' => $status
        ), 'id = ' . $id);
    }
    
    // получаем список всех меню и всех их пунктов
    // этот метод пригодится нам для админки
    public function getMenus(){
        $select = $this->select();
        // сортируем по названию меню
        $select->order('title');
        // берем все записи из таблицы menu
        $menus =  $this->fetchAll($select);
        $mdlItems = new Model_MenuItem();
        $menus = $menus->toArray();
        foreach ($menus as $k => $m){
            // получаем все пункты конкретного меню
            $items = $mdlItems->getItemsByMenu($m['id']);
            if ($items)
                // если пункты есть добавляем их в массив с ключем subitems
                $menus[$k]['subitems'] = $items;
        }
        return $menus;
    }
    // удаляем меню. Вообще говоря мы будем использовать status = deleted
    // для удаления меню, чтобы потом его можно было восстановить
    public function deleteMenu($item_id){
        if ($row = $this->find($item_id)->current()){
            return $row->delete();
        }
    }
    
    // получаем меню по позиции и модулю где оно должно отображаться
    // например getMenuByPosition('left', 'admin') вернет меню для левой панели 
    // в админке
    public function getMenuByPosition($pos, $module){
        $select = $this->select();
        $select->where('position = ?', $pos)
                ->where('module = ?', $module)
                ->limit(1);
        return $this->fetchRow($select);
    }
    
}