```
                            88                                     
                            88                                     
                            88                                     
                            88  ,adPPYba,  ,adPPYb,d8  ,adPPYba,   
                            88 a8P_____88 a8"    `Y88 a8"     "8a  
                            88 8PP""""""" 8b       88 8b       d8  
                            88 "8b,   ,aa "8a,   ,d88 "8a,   ,a8"  
                            88  `"Ybbd8"'  `"YbbdP"Y8  `"YbbdP"'   
                                           aa,    ,88              
                                            "Y8bbdP"        
                            -------------------------------------
                                  Light - Easy - Good - Open 
```                
IDK LEGO BUNDLE V 0.1 alpha (do not use in production)

Build your pages simply by adding configurable components.
Add a filter, add a list, add a form then go

 ===> For Symfony 3.X

TODO 

CURRENT v alpha

- Log history [ ]
- Pagination [X]
- Field-route [X]
- Onglet component [ ]
- Default LayoutBase [X]
- Custom Bulk Action [ ]
- Right Bar [ ]
- Sub-Filter and Xhr filter [ ]
- Upload File [X]
- EntityAction and ListAction auto [ ]
- Rupteur [X]
- Group [ ]
- Form auto [X]
- Call template (ViewParams)[X]
- Url object (LegoPath) [X]
- Header and Menu object [X]
- Multi component (CustomComponent or Multi ActionComponent) [ ]
- Skeleton [X]
- Reload line with item action [ ]
- Move component [X]
- Dashboard [ ]
- Macro [ ]
- Widget Systeme [ ]
- Check double bindRequest in subComponent [ ]
- ExportField [X]
- Doc [ ]

v beta

- work without fosuser (optional) [ ]
- Gestion ROLE [ ]

Symfony 4

-require translator
```yaml
default_locale: fr
    translator:
       fallbacks: ['fr']
```
-require doctrine
-require templating
-require generator

framework.yml:
```yaml
freamworks:
    templating:
        engines: ['twig']
```
"doctrine/doctrine-bundle": "^1.6",
"doctrine/orm": "^2.5",
"symfony/templating":"^4.0.4",
"friendsofsymfony/user-bundle": "~2.0"


Next (November 2017):

Check all type Form, (Sub-)Filter, Group, Custom action, itemAction and bulkAction

Optimisation :

Pager,  Filter, Action, tbody imbrique, Upload file+


Your config.yml

```yaml
idk_lego:
    skin: skin-yellow
    layout: ::lego.html.twig
    service_menu_class: AppBundle\Service\Menu
    service_header_class: AppBundle\Service\Header
    service_footer_class: AppBundle\Service\Footer
```

Exemple Menu

```php
<?php

namespace AppBundle\Service;

use Idk\LegoBundle\Service\Menu as Base;
use Idk\LegoBundle\Lib\LayoutItem\MenuItem;

class Menu extends Base
{
    public function getItems(){
        return [
            new MenuItem('Titre', ['type' => MenuItem::TYPE_HEADER]),
            new MenuItem('Dashboard', ['icon' => 'dashboard', 'route' => 'homepage']),
            new MenuItem('Configurateur', ['icon'=>'cogs', 'route' => 'app_configlego_index'])
        ];
    }
}
```

Exemple configurator

```php
<?php
namespace AppBundle\Configurator;

use AppBundle\Entity\Config;
use Idk\LegoBundle\Configurator\AbstractDoctrineORMConfigurator;
use Idk\LegoBundle\Component as CPNT;
/**
 * The LEGO configurator for Config
 */
class ConfigConfigurator extends AbstractDoctrineORMConfigurator
{

    const ENTITY_CLASS_NAME = Config::class;
    const TITLE = 'Gestion des configs';

    public function buildIndex()
    {
        $this->addIndexComponent(CPNT\Action::class, ['actions' => [CPNT\Action::ADD]]);
        $this->addIndexComponent(CPNT\Filter::class, []);
        $this->addIndexComponent(CPNT\ListItems::class, [
            'entity_actions' => [CPNT\ListItems::ENTITY_ACTION_EDIT, CPNT\ListItems::ENTITY_ACTION_DELETE],
            'bulk_actions' => [CPNT\ListItems::BULK_ACTION_DELETE]
        ]);

        $this->addAddComponent(CPNT\Action::class, ['actions' => [CPNT\Action::BACK]]);
        $this->addAddComponent(CPNT\Form::class, []);

        $this->addEditComponent(CPNT\Action::class, ['actions' => [CPNT\Action::BACK]]);
        $this->addEditComponent(CPNT\Form::class, []);

        $this->addShowComponent(CPNT\Action::class, ['actions' => [CPNT\Action::BACK]]);
        $this->addShowComponent(CPNT\Item::class, []);
    }

    public function getControllerPath()
    {
        return 'app_configlego';
    }
}

```

The Entity for exemple
```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Idk\LegoBundle\Annotation\Entity as Lego;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Jeu
 *
 * @ORM\Table(name="jeu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JeuRepository")
 */
class Jeu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Lego\Field(path="show", twig="jeu_{{ view.value }}")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Lego\Field(label="Nom", edit_in_place=true, path={"route":"app_jeulego_show", "params"={"id":"id"}})
     * @Lego\Filter\StringFilter()
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPlayer", type="integer")
     * @Assert\NotBlank()
     * @Lego\Field(label="Nombre de joueur")
     * @Lego\Filter\NumberRangeFilter(label="Nombre de joueur")
     */
    private $nbPlayer;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     * @Assert\NotBlank()
     * @Lego\Field(label="Age")
     * @Lego\Filter\NumberRangeFilter()
     */
    private $age;

    /**
     * @var Editeur
     *
     * @ORM\ManyToOne(targetEntity="Editeur")
     * @Lego\Field(label="Editeur",  path={"route":"app_editeurlego_show", "params"={"id":"editeur.id"}})
     * @ORM\JoinColumn(name="editeur_id", referencedColumnName="id")
     */
    private $editeur;
    
    /* ... */
 ?>
 ```
