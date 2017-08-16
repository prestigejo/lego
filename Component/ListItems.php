<?php

namespace Idk\LegoBundle\Component;


use Idk\LegoBundle\Annotation\Entity\Field;

class ListItems extends Component{

    private $fields = [];

    protected function init(){
        return;
    }

    protected function requiredOptions(){
        return [];
    }

    public function add($name, $options){
        $field = new Field($options);
        $field->setName($name);
        $this->fields[$name] = $field;
    }

    public function getFields(){
        return array_merge($this->fields, $this->get('lego.service.meta_entity_manager')->generateFields($this->getConfigurator()->getEntityName(), $this->getOption('fields')));
    }

    public function getTemplate($name = 'index'){
        return 'IdkLegoBundle:Component\\ListItemsComponent:'.$name.'.html.twig';
    }

    public function getTemplateParameters(){
        return ['component' => $this];
    }

    public function hasBulkActions(){
        return $this->getOption('bulk',false);
    }

}
