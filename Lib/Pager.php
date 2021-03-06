<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\Lib;



use Doctrine\ORM\QueryBuilder;

class Pager{

    const NBPERPAGE = 20;
    const NBBTN = 5;
    const ALL = 0;

    private $queryBuilder;
    private $page;
    private $nbPage;
    private $nbElements;
    private $nbPerPage;

    public function __construct(QueryBuilder $queryBuilder ,$page = 1,$nbPerPage = null, $unlimited = false){
        $this->nbPerPage = ($nbPerPage)? $nbPerPage:self::NBPERPAGE;
        $this->queryBuilder = clone $queryBuilder;
        $this->page = $page;
        if($this->page != self::ALL) {
            $this->queryBuilder->setFirstResult(($this->page - 1) * $this->nbPerPage);
            $this->queryBuilder->setMaxResults($this->nbPerPage);
        }
        $this->unlimited = $unlimited;
        if($unlimited){
            $this->nbElements = null;
            $this->nbPage = null;
        }else {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $this->nbElements = $queryBuilder->select('count('.$rootAlias.') as nb')->getQuery()->getSingleResult()['nb'];
            $this->nbPage = ceil($this->nbElements / $this->nbPerPage);
        }
    }

    public function getNbPerPage(){
        return $this->nbPerPage;
    }

    public function getQueryBuilder(){
        return $this->queryBuilder;
    }

    public function getEntities(){
        return $this->queryBuilder->getQuery()->getResult();
    }

    public function getNbPage(){
        return $this->nbPage;
    }

    public function getPage(){
        return $this->page;
    }

    public function isUnlimited(){
        return $this->unlimited;
    }

    public function getNbElements(){
        return $this->nbElements;
    }
    public function isPre(){
        return ($this->page > 1);
    }

    public function isNext(){
        if($this->nbPage === null) return true; //unlimited
        return ($this->page < $this->nbPage);
    }

    public function isPPre(){
        return ($this->page-1 > 1);
    }

    public function isNNext(){
        return ($this->page+1 < $this->nbPage);
    }

    public function isCur($i){
        return ($i == $this->page);
    }

    public function isShow($i){
        $nbBtn = self::NBBTN;
        if($nbBtn % 2 == 0) $nbBtn++;
        $lim = ($nbBtn-1) / 2;
        $min = $this->page - $lim;
        $max = $this->page + $lim;
        return ($i >= $min && $i <= $max);
    }

    public function buttons(){
        $nbBtn = self::NBBTN;
        if($nbBtn % 2 == 0) $nbBtn++;
        $lim = ($nbBtn-1) / 2;
        $min = $this->page - $lim;
        if($min <= 0) $min = 1;
        $max = $this->page + $lim;
        $l = $min;
        $return = [];
        while($l <= $max){
            $return[] = $l;
            $l++;
        }
        return $return;
    }

    public function getFirstPage(){
        return 1;
    }

    public function getLastPage(){
        return $this->nbPage;
    }
}
