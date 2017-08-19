<?php
namespace Idk\LegoBundle\Service;


use Symfony\Component\Yaml\Yaml;


class Menu
{

    private $em;
    private $security;

    public function __construct($em, $security) {
        $this->em = $em;
        $this->security = $security;
    }

    public function search(){
        return false;
    }

    public function getTemplate(){
        return 'IdkLegoBundle:Layout:_menu.html.twig';
    }

    public function getItems(){
        return [
            ['libelle'=>'ADMIN', 'type'=>'header'],
            [
                'type' => 'body',
                'libelle' => 'Dashboard',
                'icon' => 'dashboard',
                'path' => 'homepage',
                'labels'=> [['class'=>'bg-red','libelle'=>5]],
                'children' => [['libelle'=>'index', 'path'=>'homepage', 'icon'=>'circle-o']],
            ]
        ];
    }


}
