<?php

namespace Tekstove\ApiBundle\Propel\Runtime\ActiveQuery\Criterion;

use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Description of FullTextCriterion
 *
 * @author potaka
 */
class FullTextCriterion  extends AbstractCriterion
{
    /** flag to ignore case in comparison */
    protected $ignoreStringCase = false;

    /**
     * Create a new instance.
     *
     * @param Criteria $outer      The outer class (this is an "inner" class).
     * @param string   $column     ignored
     * @param string   $value      The condition to be added to the query string
     * @param string   $comparison One of Criteria::LIKE and Criteria::NOT_LIKE
     */
    public function __construct(Criteria $outer, $column, $value, $comparison = 'IN NATURAL LANGUAGE MODE')
    {
        return parent::__construct($outer, $column, $value, $comparison);
    }

    /**
     * Appends a Prepared Statement representation of the Criterion onto the buffer
     *
     * @param string &$sb    The string that will receive the Prepared Statement
     * @param array  $params A list to which Prepared Statement parameters will be appended
     */
    protected function appendPsForUniqueClauseTo(&$sb, array &$params)
    {
        $field = (null === $this->table) ? $this->column : $this->table . '.' . $this->column;

        $params[] = [
            'table' => $this->realtable,
            'column' => $this->column,
            'value' => $this->value,
        ];

        $sb .= " MATCH (" . $field . ") AGAINST( :p" . count($params) . " " . $this->comparison . ") ";
    }
}
