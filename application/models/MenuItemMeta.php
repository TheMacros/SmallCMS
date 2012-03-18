<?php
class Model_MenuItemMeta extends Zend_Db_Table_Abstract{
    protected $_name = 'menu_item_meta';
    
    public function addMeta($itemId, $key, $value){
        $row = $this->createRow();
        $row->item_id = $itemId;
        $row->key = $key;
        $row->value = $value;
        return $row->save();
    }
    
    public function removeMeta($id){
        if ($row = $this->find($id)->current()){
            return $row->delete();
        }
    }
    
    public function updateMeta($id, $key, $value){
        if ($row = $this->find($id)->current()){
            $row->key = $key;
            $row->value = $value;
            return $row->save();
        }
    }
    
    public function getMeta($itemId, $key){
        $select = $this->select();
        $select->where('item_id = ?', $itemId)
                ->where('key = ?', $key);
        return $this->fetchRow($select);
    }
}
