<?php
class Model_Bug extends Zend_Db_Table_Abstract {
    protected $_name = 'bugs';
    
    public function insertBug($data){
        $data['date'] = time();
        $date['status'] = 'open';
        // вставляем запись
        return $this->insert($data);
    }
}