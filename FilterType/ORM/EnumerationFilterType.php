<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\FilterType\ORM;

use Symfony\Component\HttpFoundation\Request;

/**
 * EnumerationFilterType
 */
class EnumerationFilterType extends AbstractORMFilterType
{
    /**
     * @param Request $request  The request
     * @param array   &$data    The data
     * @param string  $uniqueId The unique identifier
     */
    public function bindRequest(array &$data, $uniqueId)
    {
        $data['comparator'] = $this->getValueSession('filter_comparator_' . $uniqueId);
        $data['value']      = $this->getValueSession('filter_value_' . $uniqueId);
        return ($data['value'] != null);
    }

    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId,$alias,$col)
    {
        if (isset($data['value']) && isset($data['comparator'])) {
            switch ($data['comparator']) {
                case 'in':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->in($alias . $col, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, $data['value'], \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
                    break;
                case 'notin':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->notIn($alias . $col, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('var_' . $uniqueId, $data['value'], \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return '@IdkLego/FilterType/enumerationFilter.html.twig';
    }
}
