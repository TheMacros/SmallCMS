<?php
class Form_Bug extends Zend_Form{
    public function init(){
        $author = $this->createElement('text', 'author');
        $author->setLabel('Author:')
                ->addFilter('StripTags')                // фильтр StripTags убирает HTML теги из введенного текста
                ->addFilter('StringTrim')               // фильтр StringTrim убирает пробелы в начале и в конце текста
                ->setAttrib('placeholder', 'Enter your name')     // устанавливаем примечание
                ->setRequired();                        // делаем поле обязательным
        $this->addElement($author);
        
        $email = $this->createElement('text', 'email');  
        $email->setLabel('Email:')                      
                ->addValidator('EmailAddress')  // валидатор EmailAddress проверяет введенный текст на совпадение с шаблоном youemail@domain.com
                ->setAttrib('placeholder', 'Enter your email')
                ->setRequired();
        $this->addElement($email);
        
        $type = $this->createElement('select', 'type');
        $type->setLabel('Type:')
                ->addMultioption('bug', 'Bug')          // addMultioption добавляет елемент <option> в Select
                ->addMultioption('sugestion', 'Sugestion'); // первый параметр это value, второй то что будет между <option> и </option>
        $this->addElement($type);
        
        $subject = $this->createElement('text', 'subject');
        $subject->setLabel('Subject:')
                ->addFilter('StripTags')
                ->setRequired();
        $this->addElement($subject);
        
        $description = $this->createElement('textarea', 'description');
        $description->setLabel('Description')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttrib('rows', '5')
                ->setRequired();
        $this->addElement($description);
        
        $priority = $this->createElement('select', 'priority');
        $priority->setLabel('Priority:')
                ->addMultioption('low', 'Low')
                ->addMultioption('medium', 'Medium')
                ->addMultioption('high', 'High');
        $this->addElement($priority);
        
        $status = $this->createElement('select', 'status');
        $status->setLabel('Status:')
                ->addMultioption('open', 'Open')
                ->addMultioption('in_process', 'In process')
                ->addMultioption('close', 'Close');
        $this->addElement($status);
        
        $this->addElement('submit', 'submit', array('label' => 'Submit', 'class' => 'btn btn-primary'));
    }
}