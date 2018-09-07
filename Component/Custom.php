<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Component;


use Idk\LegoBundle\Annotation\Entity\Field;
use Idk\LegoBundle\Form\Type\AutoCompletionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class Custom extends Component{

    protected function init(){
        return;
    }

    protected function requiredOptions(){
        return ['src'];
    }

    public function isTemplate(){
        return $this->getOption('template', false);
    }

    public function getSrc(){
        return $this->getOption('src');
    }

    public function getTemplate($name = 'index'){
        return '@IdkLego/Component/CustomComponent/'.$name.'.html.twig';
    }

    public function getTemplateParameters()
    {
        if($this->getRequest()->get('id')){
            $entity = $this->getConfigurator()->getRepository()->find($this->getRequest()->get('id'));
        }else{
            $entity = null;
        }
        return ['entity' => $entity, 'component' => $this];
    }


}
