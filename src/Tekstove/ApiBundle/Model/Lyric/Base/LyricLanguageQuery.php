<?php

namespace Tekstove\ApiBundle\Model\Lyric\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Tekstove\ApiBundle\Model\Language;
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguage as ChildLyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery as ChildLyricLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricLanguageTableMap;

/**
 * Base class that represents a query for the 'lyric_language' table.
 *
 *
 *
 * @method     ChildLyricLanguageQuery orderByLyricId($order = Criteria::ASC) Order by the lyric_id column
 * @method     ChildLyricLanguageQuery orderByLanguageId($order = Criteria::ASC) Order by the language_id column
 *
 * @method     ChildLyricLanguageQuery groupByLyricId() Group by the lyric_id column
 * @method     ChildLyricLanguageQuery groupByLanguageId() Group by the language_id column
 *
 * @method     ChildLyricLanguageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLyricLanguageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLyricLanguageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLyricLanguageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLyricLanguageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLyricLanguageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLyricLanguageQuery leftJoinLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricLanguageQuery rightJoinLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricLanguageQuery innerJoinLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the Lyric relation
 *
 * @method     ChildLyricLanguageQuery joinWithLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Lyric relation
 *
 * @method     ChildLyricLanguageQuery leftJoinWithLyric() Adds a LEFT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricLanguageQuery rightJoinWithLyric() Adds a RIGHT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricLanguageQuery innerJoinWithLyric() Adds a INNER JOIN clause and with to the query using the Lyric relation
 *
 * @method     ChildLyricLanguageQuery leftJoinLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Language relation
 * @method     ChildLyricLanguageQuery rightJoinLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Language relation
 * @method     ChildLyricLanguageQuery innerJoinLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the Language relation
 *
 * @method     ChildLyricLanguageQuery joinWithLanguage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Language relation
 *
 * @method     ChildLyricLanguageQuery leftJoinWithLanguage() Adds a LEFT JOIN clause and with to the query using the Language relation
 * @method     ChildLyricLanguageQuery rightJoinWithLanguage() Adds a RIGHT JOIN clause and with to the query using the Language relation
 * @method     ChildLyricLanguageQuery innerJoinWithLanguage() Adds a INNER JOIN clause and with to the query using the Language relation
 *
 * @method     \Tekstove\ApiBundle\Model\LyricQuery|\Tekstove\ApiBundle\Model\LanguageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLyricLanguage findOne(ConnectionInterface $con = null) Return the first ChildLyricLanguage matching the query
 * @method     ChildLyricLanguage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLyricLanguage matching the query, or a new ChildLyricLanguage object populated from the query conditions when no match is found
 *
 * @method     ChildLyricLanguage findOneByLyricId(int $lyric_id) Return the first ChildLyricLanguage filtered by the lyric_id column
 * @method     ChildLyricLanguage findOneByLanguageId(int $language_id) Return the first ChildLyricLanguage filtered by the language_id column *

 * @method     ChildLyricLanguage requirePk($key, ConnectionInterface $con = null) Return the ChildLyricLanguage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricLanguage requireOne(ConnectionInterface $con = null) Return the first ChildLyricLanguage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricLanguage requireOneByLyricId(int $lyric_id) Return the first ChildLyricLanguage filtered by the lyric_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricLanguage requireOneByLanguageId(int $language_id) Return the first ChildLyricLanguage filtered by the language_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricLanguage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLyricLanguage objects based on current ModelCriteria
 * @method     ChildLyricLanguage[]|ObjectCollection findByLyricId(int $lyric_id) Return ChildLyricLanguage objects filtered by the lyric_id column
 * @method     ChildLyricLanguage[]|ObjectCollection findByLanguageId(int $language_id) Return ChildLyricLanguage objects filtered by the language_id column
 * @method     ChildLyricLanguage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LyricLanguageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Lyric\Base\LyricLanguageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricLanguage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLyricLanguageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLyricLanguageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLyricLanguageQuery) {
            return $criteria;
        }
        $query = new ChildLyricLanguageQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$lyric_id, $language_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLyricLanguage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LyricLanguageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LyricLanguageTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricLanguage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT lyric_id, language_id FROM lyric_language WHERE lyric_id = :p0 AND language_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLyricLanguage $obj */
            $obj = new ChildLyricLanguage();
            $obj->hydrate($row);
            LyricLanguageTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildLyricLanguage|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(LyricLanguageTableMap::COL_LYRIC_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(LyricLanguageTableMap::COL_LANGUAGE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the lyric_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLyricId(1234); // WHERE lyric_id = 1234
     * $query->filterByLyricId(array(12, 34)); // WHERE lyric_id IN (12, 34)
     * $query->filterByLyricId(array('min' => 12)); // WHERE lyric_id > 12
     * </code>
     *
     * @see       filterByLyric()
     *
     * @param     mixed $lyricId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByLyricId($lyricId = null, $comparison = null)
    {
        if (is_array($lyricId)) {
            $useMinMax = false;
            if (isset($lyricId['min'])) {
                $this->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $lyricId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lyricId['max'])) {
                $this->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $lyricId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $lyricId, $comparison);
    }

    /**
     * Filter the query on the language_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLanguageId(1234); // WHERE language_id = 1234
     * $query->filterByLanguageId(array(12, 34)); // WHERE language_id IN (12, 34)
     * $query->filterByLanguageId(array('min' => 12)); // WHERE language_id > 12
     * </code>
     *
     * @see       filterByLanguage()
     *
     * @param     mixed $languageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByLanguageId($languageId = null, $comparison = null)
    {
        if (is_array($languageId)) {
            $useMinMax = false;
            if (isset($languageId['min'])) {
                $this->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $languageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($languageId['max'])) {
                $this->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $languageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $languageId, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric|ObjectCollection $lyric The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByLyric($lyric, $comparison = null)
    {
        if ($lyric instanceof \Tekstove\ApiBundle\Model\Lyric) {
            return $this
                ->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $lyric->getId(), $comparison);
        } elseif ($lyric instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricLanguageTableMap::COL_LYRIC_ID, $lyric->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLyric() only accepts arguments of type \Tekstove\ApiBundle\Model\Lyric or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Lyric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function joinLyric($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Lyric');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Lyric');
        }

        return $this;
    }

    /**
     * Use the Lyric relation Lyric object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\LyricQuery A secondary query class using the current class as primary query
     */
    public function useLyricQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLyric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lyric', '\Tekstove\ApiBundle\Model\LyricQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Language object
     *
     * @param \Tekstove\ApiBundle\Model\Language|ObjectCollection $language The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = null)
    {
        if ($language instanceof \Tekstove\ApiBundle\Model\Language) {
            return $this
                ->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $language->getId(), $comparison);
        } elseif ($language instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricLanguageTableMap::COL_LANGUAGE_ID, $language->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByLanguage() only accepts arguments of type \Tekstove\ApiBundle\Model\Language or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Language relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function joinLanguage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Language');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Language');
        }

        return $this;
    }

    /**
     * Use the Language relation Language object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\LanguageQuery A secondary query class using the current class as primary query
     */
    public function useLanguageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Language', '\Tekstove\ApiBundle\Model\LanguageQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLyricLanguage $lyricLanguage Object to remove from the list of results
     *
     * @return $this|ChildLyricLanguageQuery The current query, for fluid interface
     */
    public function prune($lyricLanguage = null)
    {
        if ($lyricLanguage) {
            $this->addCond('pruneCond0', $this->getAliasedColName(LyricLanguageTableMap::COL_LYRIC_ID), $lyricLanguage->getLyricId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(LyricLanguageTableMap::COL_LANGUAGE_ID), $lyricLanguage->getLanguageId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lyric_language table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricLanguageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LyricLanguageTableMap::clearInstancePool();
            LyricLanguageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricLanguageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LyricLanguageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LyricLanguageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LyricLanguageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LyricLanguageQuery
