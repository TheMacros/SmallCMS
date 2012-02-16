<?
class Form_Search extends Zend_Form{
    public function init(){
        // устанавливаем метод GET для нашей формы
        $this->setMethod('get');
        
        // устанавливаем контроллер и экшен который будет обрабатывать форму
        $this->setAction('/index/search/');
        
        // устанавливаем class="navbar-search pull-right" 
        $this->setAttrib('class', 'navbar-search pull-right');
        
        // устанавливаем декораторы FormElements и tag = form (об этом ниже)
        $this->setDecorators(array('FormElements', 'tag' => 'form'));
        
        // создаем текстовый элемент с именем "search"
        $search = $this->createElement('text', 'search');
        
        // устанавливаем декоратор ViewHelper
        $search->setDecorators(array('ViewHelper'))
                
                // устанавливаем class="search-query"
                ->setAttrib('class', 'search-query')
                
                // утсанавливаем placeholder="Search"
                ->setAttrib('placeholder', 'Search');
        
        // добавляем элемент к форме
        $this->addElement($search);
    }
}