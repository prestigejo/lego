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


use Idk\LegoBundle\Lib\Actions\ListAction;
use Symfony\Component\HttpFoundation\Request;

class Action extends Component{

    private $actions = [];

    const ADD = 'add';
    const EDIT= 'edit';
    const BACK = 'back';
    const LOGS = 'logs';
    const EXPORT_CSV = 'export_csv';
    const EXPORT_XLSX = 'export_xlsx';
    const SORT_COMPONENTS_RESET = 'sort_components_reset';

    static public function SCREEN($label, $suffixRoute){
        return [$label, 'default', ['suffix_route'=>$suffixRoute]];
    }

    static public function EXPORT(Component $component,string $format = 'csv', $label = null){
        if(!$label){
            switch(strtolower($format)){
                case 'xlsx': $label = 'lego.action.export_xlsx'; break;
                case 'csv': $label =  'lego.action.export_csv'; break;
            }
        }
        return [$label, 'export', ['suffix_route'=>'index','format'=>$format,'cid'=>$component->getId()]];
    }

    protected function init(){
        return;
    }

    protected function requiredOptions(){
        return [];
    }


    public function add($action){
        $actions = $this->getOption('actions', []);
        $actions[] = $action;
        $this->setOption('actions',$actions);
    }

    public function bindRequest(Request $request)
    {
        foreach($this->getOption('actions', []) as $action){
            if($action instanceOf ListAction) {
                $this->actions[] = $action;
            }elseif(is_array($action)){
                $this->actions[] = new ListAction($action[0], ['route'=>$this->getConfigurator()->getPathRoute($action[1]), 'params'=>$this->getConfigurator()->getPathParameters($action[2])]);
            }else{
                if($action == self::ADD){
                    $action = new ListAction('lego.action.add', ['route'=>$this->getConfigurator()->getPathRoute('add'), 'params'=>$this->getConfigurator()->getPathParameters()]);
                }elseif($action == self::EDIT){
                    $action = new ListAction('lego.action.edit', ['route'=>$this->getConfigurator()->getPathRoute('edit'), 'params'=>$this->getConfigurator()->getPathParameters(['id'=>$request->get('id')])]);
                }elseif($action == self::BACK){
                    if($request->headers->get('referer')){
                        $action = new ListAction('lego.action.back', ['url'=>$request->headers->get('referer')]);
                    }else{
                        $action = new ListAction('lego.action.back', ['route'=>$this->getConfigurator()->getPathRoute('index'), 'params'=>$this->getConfigurator()->getPathParameters()]);
                    }
                }elseif($action == self::LOGS){
                    $action = new ListAction('lego.action.logs', ['route'=>$this->getConfigurator()->getPathRoute('logs'), 'params' => $this->getConfigurator()->getPathParameters()]);
                }elseif($action == self::EXPORT_CSV) {
                    $action = new ListAction('lego.action.export_csv', ['route' => $this->getConfigurator()->getPathRoute('export'), 'params'=>$this->getConfigurator()->getPathParameters(['format'=>'csv', 'suffix_route'=>$this->getConfigurator()->getCurrentComponentSuffixRoute()])]);
                }elseif($action == self::EXPORT_XLSX) {
                    $action = new ListAction('lego.action.export_xlsx', ['route' => $this->getConfigurator()->getPathRoute('export'), 'params'=>$this->getConfigurator()->getPathParameters(['format'=>'xlsx', 'suffix_route'=>$this->getConfigurator()->getCurrentComponentSuffixRoute()])]);
                }elseif($action == self::SORT_COMPONENTS_RESET) {
                    $action = new ListAction('lego.action.reset_sort_components', ['route' => $this->getConfigurator()->getPathRoute('sortcomponentsreset'), 'params'=>$this->getConfigurator()->getPathParameters(['suffix_route'=>$this->getConfigurator()->getCurrentComponentSuffixRoute()])]);
                }
                $this->actions[] = $action;
            }
        }
    }

    public function getActions(){
        return $this->actions;
    }

    public function getTemplate($name = 'index'){
        return '@IdkLego/Component/ActionComponent/'.$name.'.html.twig';
    }

    public function getTemplateParameters(){
        return [];
    }


}
