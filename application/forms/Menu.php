<?php
class Form_Menu extends Zend_Form{
    public function init(){
        $this->setAttrib('class', 'well span');
        $title = $this->createElement('text', 'title');
        $title->setLabel('Title:')
                ->setRequired()
                ->addFilter('StripTags')
                ->addFilter('StringTrim');
        $this->addElement($title);
        
        $description = $this->createElement('textarea', 'description');
        $description->setLabel('Description:')
                ->addFilter('StripTags')
                ->setAttrib('rows', '6')
                ->addFilter('StringTrim');
        $this->addElement($description);
        
        $status = $this->createElement('select', 'status');
        $status->setLabel('Status')
                ->addMultioptions(array(
                    'enabled'   => 'Enabled',
                    'disabled'  => 'Disabled',
                    'removed'   => 'Removed'
                ));
        $this->addElement($status);
        
        $position = $this->createElement('select', 'position');
        $position->setLabel('Position:')
                ->addMultioptions(array(
                    'top'   => 'Top',
                    'left'  => 'Left',
                    'right' => 'Right',
                    'footer'=> 'Footer'
                ));
        $this->addElement($position);
        
        $render_module = $this->createElement('select', 'render_module');
        $render_module->setLabel('Module:')
                ->addMultioptions(array(
                    'default'   => 'Default',
                    'admin'  => 'Admin'
                ));
        $this->addElement($render_module);
        
        $this->addElement('button', 'submit', array(
            'label' => 'Add menu',
            'class' => 'btn btn-primary',
            'type'  => 'submit'
        ));
    }
}