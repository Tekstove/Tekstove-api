<?php

namespace Tekstove\ApiBundle\Repo;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

/**
 * Generate Model criteria/criterion from given data
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class CriteriaGenerator
{
    /**
     * Generate model criterion based on array data.
     * Criterion is not added to the query conditions
     *
     * @param array $data
     * @param ModelCriteria $modelCriteria
     *
     * @return string created query criterion
     * @throws \Exception
     */
    public function generateCompositeCriterion(array $data, ModelCriteria $modelCriteria)
    {
        $operator = $data['operator'];
        $conditionsCollection = $data['value'];

        $tableMap = $modelCriteria->getTableMap();

        $criterionsCollectionNames = [];

        foreach ($conditionsCollection as $conditionData) {
            $conditionName = strtoupper($conditionData['operator']);
            switch ($conditionName) {
                case Criteria::EQUAL:
                case Criteria::GREATER_THAN:
                case Criteria::GREATER_EQUAL:
                case Criteria::LESS_THAN:
                case Criteria::LESS_EQUAL:
                case 'IN':
                case 'LIKE':
                case 'NOT_NULL':
                    $sqlField = $this->getSqlFieldNameFromPhpName($conditionData['field'], $tableMap);

                    $conditionsToAddSpaces = [
                        'IN',
                        'LIKE',
                    ];

                    if (in_array($conditionName, $conditionsToAddSpaces)) {
                        $conditionName = " {$conditionName} ";
                    }

                    if ($conditionName === 'NOT_NULL') {
                        $criterion = $modelCriteria->getNewCriterion(
                            $sqlField,
                            null,
                            Criteria::ISNOTNULL
                        );
                    } else {
                        $criterion = $modelCriteria->getNewCriterion(
                            $sqlField,
                            $conditionData['value'],
                            $conditionName
                        );
                    }

                    $criterionName = uniqid();
                    $criterionsCollectionNames[] = $criterionName;
                    $modelCriteria->addCond($criterionName, $criterion);

                    break;
                case 'RANGE':
                    $value = $conditionData['value'];
                    if (!isset($value['min']) && !isset($value['max'])) {
                        throw new \Exception("Please set `min` or `max` for RANGE filter");
                    }

                    $sqlField = $this->getSqlFieldNameFromPhpName($conditionData['field'], $tableMap);

                    $conditionDataEmulation = [
                        'operator' => 'AND',
                        'value' => [],
                    ];

                    if (isset($value['min'])) {
                        $conditionDataEmulation['value'][] = [
                            'field' => $sqlField,
                            'value' => $value['min'],
                            'operator' => Criteria::GREATER_EQUAL,
                        ];
                    }

                    if (isset($value['max'])) {
                        $conditionDataEmulation['value'][] = [
                            'field' => $sqlField,
                            'value' => $value['max'],
                            'operator' => Criteria::LESS_EQUAL,
                        ];
                    }

                    $criterionsCollectionNames[] = $this->generateCompositeCriterion($conditionDataEmulation, $modelCriteria);
                    break;
                case 'OR':
                case 'AND':
                    $criterionsCollectionNames[] = $this->generateCompositeCriterion($conditionData, $modelCriteria);
                    break;
                default:
                    throw new \Exception("Unknown operator `{$conditionName}`");
            }
        }

        $compositeCriterionName = 'composite_criterion_' . uniqid();
        $modelCriteria->combine(
            $criterionsCollectionNames,
            $operator,
            $compositeCriterionName
        );

        return $compositeCriterionName;
    }

    /**
     * Propel have mapper from php property to SQL field.
     * php properties are camelCase
     * mapping is CamelCase
     * This getter the desribed issue
     *
     * @param string $name
     * @param TableMap $tableMap
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getSqlFieldNameFromPhpName($name, TableMap $tableMap)
    {
        // propel generate property names with upper 1st letter
        if ($tableMap->hasColumnByPhpName($name)) {
            $sqlField = $tableMap->getColumnByPhpName($name);
        } elseif ($tableMap->hasColumnByPhpName(ucfirst($name))) {
            $sqlField = $tableMap->getColumnByPhpName(ucfirst($name));
        } else {
            throw new \Exception("Unknown field {$name}");
        }

        return $sqlField;
    }
}
