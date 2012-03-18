<?php
class Model_MenuItem extends Zend_Db_Table_Abstract{
    // таблица в базе с именем menu_item
    protected $_name = 'menu_item';
    // карта связе для каскадного удаления записей
    protected $_referenceMap = array(
        'Menu'  => array(
            'columns'       => array('menu_id'),
            'refTableClass' => 'Model_Menu',
            'refColumns'    => array('id'),
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        )
    );
    // добавляем пункт меню
    public function addItem($menu_id, $title, $link, $icon = null, $parent_id = 0){
        $row = $this->createRow();
        $row->menu_id = $menu_id;
        $row->title = $title;
        $row->link = $link;
        $row->icon = $icon;
        $row->parent_id = $parent_id;
        $row->save();
        return $row;
    }
    // обновляем пункт меню
    public function updateItem($id, $menu_id, $title, $link, $icon, $parent_id, $position){
        if ($row = $this->find($id)->current()){
            $row->menu_id = $menu_id;
            $row->title = $title;
            $row->link = $link;
            $row->icon = $icon;
            $row->parent_id = $parent_id;
            $row->position = $position;
            return $row->save();
        }
    }
    // получаем пункты меню с id = $menuId
    public function getItemsByMenu($menuId){
        $select = $this->select();
        // получем все пукты меню где menu_id = $menuId
        $select->where("menu_id = ?", $menuId);
        // сортировака по position
        $select->order('position');
        $items = $this->fetchAll($select);
        // елси пункты есть вызываем рекурсивную функцию 
        // getSubItems которая делает пункты вложенными если нужно
        if (!empty($items)){
            $res = $this->getSubItems(0, $items);
        }
        return $res;
    }
    // делаем вложение пунктов
    private function getSubItems($parent_id, $items){
        // получаем все пункты с заданным parent_id
        $its = $this->getItemsByParentId($parent_id, $items);
        if (isset($its)){
            // обходим все пункты меню 
            foreach ($its as $item){
                // вызываем опять getSubItems чтобы проверить есть ли у данного 
                // пункта меню подпункты
                $sub = $this->getSubItems($item->id, $items);
                // если есть добавляем их в массив с ключом 'subitems'
                // также добавляем класс has-submenu - это может пригодится нам в верстке
                $r = $item->toArray();
                if ($sub){
                    $r['addClass'] = 'has-submenu';
                    $r['subitems'] = $sub;
                }else{
                    // если дочерних пунктов нет, то добавляем класс no-submenu
                    $r['addClass'] = 'no-submenu';
                }
                $res[] = $r;
            }
        }
        if (isset($res))
            return $res;
    }
    // фильтруем список $items и оставляем в 
    // нем только элементы с заданным parent_id
    private function getItemsByParentId($parent_id, $items){
        foreach ($items as $i){
            if ($i->parent_id == $parent_id)
                $res[] = $i;
        }
        if (isset($res))
            return $res;
    }
    // удалеяем пункт меню
    public function deleteItem($item_id){
        return $this->delete("id = $item_id");
    }
}