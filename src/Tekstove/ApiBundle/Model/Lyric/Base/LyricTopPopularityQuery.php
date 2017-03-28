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
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\Lyric\LyricTopPopularity as ChildLyricTopPopularity;
use Tekstove\ApiBundle\Model\Lyric\LyricTopPopularityQuery as ChildLyricTopPopularityQuery;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricTopPopularityTableMap;

/**
 * Base class that represents a query for the 'lyric_top_popularity' table.
 *
 *
 *
 * @method     ChildLyricTopPopularityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLyricTopPopularityQuery orderByLyricId($order = Criteria::ASC) Order by the lyric_id column
 * @method     ChildLyricTopPopularityQuery orderByPopularity($order = Criteria::ASC) Order by the popularity column
 * @method     ChildLyricTopPopularityQuery orderByDate($order = Criteria::ASC) Order by the date column
 *
 * @method     ChildLyricTopPopularityQuery groupById() Group by the id column
 * @method     ChildLyricTopPopularityQuery groupByLyricId() Group by the lyric_id column
 * @method     ChildLyricTopPopularityQuery groupByPopularity() Group by the popularity column
 * @method     ChildLyricTopPopularityQuery groupByDate() Group by the date column
 *
 * @method     ChildLyricTopPopularityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLyricTopPopularityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLyricTopPopularityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLyricTopPopularityQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLyricTopPopularityQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLyricTopPopularityQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLyricTopPopularityQuery leftJoinLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricTopPopularityQuery rightJoinLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricTopPopularityQuery innerJoinLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the Lyric relation
 *
 * @method     ChildLyricTopPopularityQuery joinWithLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Lyric relation
 *
 * @method     ChildLyricTopPopularityQuery leftJoinWithLyric() Adds a LEFT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricTopPopularityQuery rightJoinWithLyric() Adds a RIGHT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricTopPopularityQuery innerJoinWithLyric() Adds a INNER JOIN clause and with to the query using the Lyric relation
 *
 * @method     \Tekstove\ApiBundle\Model\LyricQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLyricTopPopularity findOne(ConnectionInterface $con = null) Return the first ChildLyricTopPopularity matching the query
 * @method     ChildLyricTopPopularity findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLyricTopPopularity matching the query, or a new ChildLyricTopPopularity object populated from the query conditions when no match is found
 *
 * @method     ChildLyricTopPopularity findOneById(int $id) Return the first ChildLyricTopPopularity filtered by the id column
 * @method     ChildLyricTopPopularity findOneByLyricId(int $lyric_id) Return the first ChildLyricTopPopularity filtered by the lyric_id column
 * @method     ChildLyricTopPopularity findOneByPopularity(int $popularity) Return the first ChildLyricTopPopularity filtered by the popularity column
 * @method     ChildLyricTopPopularity findOneByDate(string $date) Return the first ChildLyricTopPopularity filtered by the date column *

 * @method     ChildLyricTopPopularity requirePk($key, ConnectionInterface $con = null) Return the ChildLyricTopPopularity by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricTopPopularity requireOne(ConnectionInterface $con = null) Return the first ChildLyricTopPopularity matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricTopPopularity requireOneById(int $id) Return the first ChildLyricTopPopularity filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricTopPopularity requireOneByLyricId(int $lyric_id) Return the first ChildLyricTopPopularity filtered by the lyric_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricTopPopularity requireOneByPopularity(int $popularity) Return the first ChildLyricTopPopularity filtered by the popularity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricTopPopularity requireOneByDate(string $date) Return the first ChildLyricTopPopularity filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricTopPopularity[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLyricTopPopularity objects based on current ModelCriteria
 * @method     ChildLyricTopPopularity[]|ObjectCollection findById(int $id) Return ChildLyricTopPopularity objects filtered by the id column
 * @method     ChildLyricTopPopularity[]|ObjectCollection findByLyricId(int $lyric_id) Return ChildLyricTopPopularity objects filtered by the lyric_id column
 * @method     ChildLyricTopPopularity[]|ObjectCollection findByPopularity(int $popularity) Return ChildLyricTopPopularity objects filtered by the popularity column
 * @method     ChildLyricTopPopularity[]|ObjectCollection findByDate(string $date) Return ChildLyricTopPopularity objects filtered by the date column
 * @method     ChildLyricTopPopularity[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LyricTopPopularityQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Lyric\Base\LyricTopPopularityQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricTopPopularity', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLyricTopPopularityQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLyricTopPopularityQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLyricTopPopularityQuery) {
            return $criteria;
        }
        $query = new ChildLyricTopPopularityQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildLyricTopPopularity|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LyricTopPopularityTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LyricTopPopularityTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLyricTopPopularity A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `lyric_id`, `popularity`, `date` FROM `lyric_top_popularity` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildLyricTopPopularity $obj */
            $obj = new ChildLyricTopPopularity();
            $obj->hydrate($row);
            LyricTopPopularityTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLyricTopPopularity|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
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
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByLyricId($lyricId = null, $comparison = null)
    {
        if (is_array($lyricId)) {
            $useMinMax = false;
            if (isset($lyricId['min'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_LYRIC_ID, $lyricId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lyricId['max'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_LYRIC_ID, $lyricId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_LYRIC_ID, $lyricId, $comparison);
    }

    /**
     * Filter the query on the popularity column
     *
     * Example usage:
     * <code>
     * $query->filterByPopularity(1234); // WHERE popularity = 1234
     * $query->filterByPopularity(array(12, 34)); // WHERE popularity IN (12, 34)
     * $query->filterByPopularity(array('min' => 12)); // WHERE popularity > 12
     * </code>
     *
     * @param     mixed $popularity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByPopularity($popularity = null, $comparison = null)
    {
        if (is_array($popularity)) {
            $useMinMax = false;
            if (isset($popularity['min'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_POPULARITY, $popularity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($popularity['max'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_POPULARITY, $popularity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_POPULARITY, $popularity, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(LyricTopPopularityTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTopPopularityTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric|ObjectCollection $lyric The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function filterByLyric($lyric, $comparison = null)
    {
        if ($lyric instanceof \Tekstove\ApiBundle\Model\Lyric) {
            return $this
                ->addUsingAlias(LyricTopPopularityTableMap::COL_LYRIC_ID, $lyric->getId(), $comparison);
        } elseif ($lyric instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricTopPopularityTableMap::COL_LYRIC_ID, $lyric->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function joinLyric($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useLyricQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLyric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Lyric', '\Tekstove\ApiBundle\Model\LyricQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLyricTopPopularity $lyricTopPopularity Object to remove from the list of results
     *
     * @return $this|ChildLyricTopPopularityQuery The current query, for fluid interface
     */
    public function prune($lyricTopPopularity = null)
    {
        if ($lyricTopPopularity) {
            $this->addUsingAlias(LyricTopPopularityTableMap::COL_ID, $lyricTopPopularity->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lyric_top_popularity table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTopPopularityTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LyricTopPopularityTableMap::clearInstancePool();
            LyricTopPopularityTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTopPopularityTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LyricTopPopularityTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LyricTopPopularityTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LyricTopPopularityTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LyricTopPopularityQuery
