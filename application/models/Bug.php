<?php
class Model_Bug extends Zend_Db_Table_Abstract {
    protected $_name = 'bugs';
    
    public function insertBug($data){
        $data['date'] = time();
        $data['status'] = 'open';
        // вставляем запись
        return $this->insert($data);
    }
    
    public function getBugs(){
        // создаем объект Zend_Db_Table_Select 
        $select = $this->select();
        // метод order() задает сортировку
        $select->order('date DESC');
        // метод fetchAll() возвращает все записи 
        // соответствующие параметрам заданным в $select
        return $this->fetchAll($select);
    }
}