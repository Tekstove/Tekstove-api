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
use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\Lyric\LyricVote as ChildLyricVote;
use Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery as ChildLyricVoteQuery;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricVoteTableMap;

/**
 * Base class that represents a query for the 'lyric_vote' table.
 *
 *
 *
 * @method     ChildLyricVoteQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLyricVoteQuery orderByLyricId($order = Criteria::ASC) Order by the lyric_id column
 * @method     ChildLyricVoteQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method     ChildLyricVoteQuery groupById() Group by the id column
 * @method     ChildLyricVoteQuery groupByLyricId() Group by the lyric_id column
 * @method     ChildLyricVoteQuery groupByUserId() Group by the user_id column
 *
 * @method     ChildLyricVoteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLyricVoteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLyricVoteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLyricVoteQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLyricVoteQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLyricVoteQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLyricVoteQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildLyricVoteQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildLyricVoteQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildLyricVoteQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildLyricVoteQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildLyricVoteQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildLyricVoteQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildLyricVoteQuery leftJoinLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricVoteQuery rightJoinLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lyric relation
 * @method     ChildLyricVoteQuery innerJoinLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the Lyric relation
 *
 * @method     ChildLyricVoteQuery joinWithLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Lyric relation
 *
 * @method     ChildLyricVoteQuery leftJoinWithLyric() Adds a LEFT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricVoteQuery rightJoinWithLyric() Adds a RIGHT JOIN clause and with to the query using the Lyric relation
 * @method     ChildLyricVoteQuery innerJoinWithLyric() Adds a INNER JOIN clause and with to the query using the Lyric relation
 *
 * @method     \Tekstove\ApiBundle\Model\UserQuery|\Tekstove\ApiBundle\Model\LyricQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLyricVote findOne(ConnectionInterface $con = null) Return the first ChildLyricVote matching the query
 * @method     ChildLyricVote findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLyricVote matching the query, or a new ChildLyricVote object populated from the query conditions when no match is found
 *
 * @method     ChildLyricVote findOneById(int $id) Return the first ChildLyricVote filtered by the id column
 * @method     ChildLyricVote findOneByLyricId(int $lyric_id) Return the first ChildLyricVote filtered by the lyric_id column
 * @method     ChildLyricVote findOneByUserId(int $user_id) Return the first ChildLyricVote filtered by the user_id column *

 * @method     ChildLyricVote requirePk($key, ConnectionInterface $con = null) Return the ChildLyricVote by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricVote requireOne(ConnectionInterface $con = null) Return the first ChildLyricVote matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricVote requireOneById(int $id) Return the first ChildLyricVote filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricVote requireOneByLyricId(int $lyric_id) Return the first ChildLyricVote filtered by the lyric_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricVote requireOneByUserId(int $user_id) Return the first ChildLyricVote filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricVote[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLyricVote objects based on current ModelCriteria
 * @method     ChildLyricVote[]|ObjectCollection findById(int $id) Return ChildLyricVote objects filtered by the id column
 * @method     ChildLyricVote[]|ObjectCollection findByLyricId(int $lyric_id) Return ChildLyricVote objects filtered by the lyric_id column
 * @method     ChildLyricVote[]|ObjectCollection findByUserId(int $user_id) Return ChildLyricVote objects filtered by the user_id column
 * @method     ChildLyricVote[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LyricVoteQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Lyric\Base\LyricVoteQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricVote', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLyricVoteQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLyricVoteQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLyricVoteQuery) {
            return $criteria;
        }
        $query = new ChildLyricVoteQuery();
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
     * @return ChildLyricVote|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LyricVoteTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LyricVoteTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
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
     * @return ChildLyricVote A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, lyric_id, user_id FROM lyric_vote WHERE id = :p0';
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
            /** @var ChildLyricVote $obj */
            $obj = new ChildLyricVote();
            $obj->hydrate($row);
            LyricVoteTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLyricVote|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LyricVoteTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LyricVoteTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricVoteTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByLyricId($lyricId = null, $comparison = null)
    {
        if (is_array($lyricId)) {
            $useMinMax = false;
            if (isset($lyricId['min'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_LYRIC_ID, $lyricId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lyricId['max'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_LYRIC_ID, $lyricId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricVoteTableMap::COL_LYRIC_ID, $lyricId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(LyricVoteTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricVoteTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\User object
     *
     * @param \Tekstove\ApiBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            return $this
                ->addUsingAlias(LyricVoteTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricVoteTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Tekstove\ApiBundle\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Tekstove\ApiBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric|ObjectCollection $lyric The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricVoteQuery The current query, for fluid interface
     */
    public function filterByLyric($lyric, $comparison = null)
    {
        if ($lyric instanceof \Tekstove\ApiBundle\Model\Lyric) {
            return $this
                ->addUsingAlias(LyricVoteTableMap::COL_LYRIC_ID, $lyric->getId(), $comparison);
        } elseif ($lyric instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricVoteTableMap::COL_LYRIC_ID, $lyric->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
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
     * @param   ChildLyricVote $lyricVote Object to remove from the list of results
     *
     * @return $this|ChildLyricVoteQuery The current query, for fluid interface
     */
    public function prune($lyricVote = null)
    {
        if ($lyricVote) {
            $this->addUsingAlias(LyricVoteTableMap::COL_ID, $lyricVote->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lyric_vote table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricVoteTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LyricVoteTableMap::clearInstancePool();
            LyricVoteTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LyricVoteTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LyricVoteTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LyricVoteTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LyricVoteTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LyricVoteQuery
