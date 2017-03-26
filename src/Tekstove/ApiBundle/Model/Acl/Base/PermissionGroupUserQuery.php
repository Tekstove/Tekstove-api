<?php

namespace Tekstove\ApiBundle\Model\Acl\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUser as ChildPermissionGroupUser;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery as ChildPermissionGroupUserQuery;
use Tekstove\ApiBundle\Model\Acl\Map\PermissionGroupUserTableMap;

/**
 * Base class that represents a query for the 'permission_group_user' table.
 *
 *
 *
 * @method     ChildPermissionGroupUserQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildPermissionGroupUserQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 *
 * @method     ChildPermissionGroupUserQuery groupByUserId() Group by the user_id column
 * @method     ChildPermissionGroupUserQuery groupByGroupId() Group by the group_id column
 *
 * @method     ChildPermissionGroupUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionGroupUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionGroupUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionGroupUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPermissionGroupUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPermissionGroupUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPermissionGroupUserQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildPermissionGroupUserQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildPermissionGroupUserQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildPermissionGroupUserQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildPermissionGroupUserQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildPermissionGroupUserQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildPermissionGroupUserQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildPermissionGroupUserQuery leftJoinPermissionGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupUserQuery rightJoinPermissionGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupUserQuery innerJoinPermissionGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupUserQuery joinWithPermissionGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupUserQuery leftJoinWithPermissionGroup() Adds a LEFT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupUserQuery rightJoinWithPermissionGroup() Adds a RIGHT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupUserQuery innerJoinWithPermissionGroup() Adds a INNER JOIN clause and with to the query using the PermissionGroup relation
 *
 * @method     \Tekstove\ApiBundle\Model\UserQuery|\Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPermissionGroupUser findOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupUser matching the query
 * @method     ChildPermissionGroupUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPermissionGroupUser matching the query, or a new ChildPermissionGroupUser object populated from the query conditions when no match is found
 *
 * @method     ChildPermissionGroupUser findOneByUserId(int $user_id) Return the first ChildPermissionGroupUser filtered by the user_id column
 * @method     ChildPermissionGroupUser findOneByGroupId(int $group_id) Return the first ChildPermissionGroupUser filtered by the group_id column *

 * @method     ChildPermissionGroupUser requirePk($key, ConnectionInterface $con = null) Return the ChildPermissionGroupUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupUser requireOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupUser requireOneByUserId(int $user_id) Return the first ChildPermissionGroupUser filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupUser requireOneByGroupId(int $group_id) Return the first ChildPermissionGroupUser filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPermissionGroupUser objects based on current ModelCriteria
 * @method     ChildPermissionGroupUser[]|ObjectCollection findByUserId(int $user_id) Return ChildPermissionGroupUser objects filtered by the user_id column
 * @method     ChildPermissionGroupUser[]|ObjectCollection findByGroupId(int $group_id) Return ChildPermissionGroupUser objects filtered by the group_id column
 * @method     ChildPermissionGroupUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PermissionGroupUserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Acl\Base\PermissionGroupUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Acl\\PermissionGroupUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionGroupUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionGroupUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPermissionGroupUserQuery) {
            return $criteria;
        }
        $query = new ChildPermissionGroupUserQuery();
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
     * @param array[$user_id, $group_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPermissionGroupUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionGroupUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PermissionGroupUserTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPermissionGroupUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `user_id`, `group_id` FROM `permission_group_user` WHERE `user_id` = :p0 AND `group_id` = :p1';
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
            /** @var ChildPermissionGroupUser $obj */
            $obj = new ChildPermissionGroupUser();
            $obj->hydrate($row);
            PermissionGroupUserTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPermissionGroupUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PermissionGroupUserTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PermissionGroupUserTableMap::COL_GROUP_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupId(1234); // WHERE group_id = 1234
     * $query->filterByGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByPermissionGroup()
     *
     * @param     mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $groupId, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\User object
     *
     * @param \Tekstove\ApiBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            return $this
                ->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupUserTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Tekstove\ApiBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\PermissionGroup object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\PermissionGroup|ObjectCollection $permissionGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function filterByPermissionGroup($permissionGroup, $comparison = null)
    {
        if ($permissionGroup instanceof \Tekstove\ApiBundle\Model\Acl\PermissionGroup) {
            return $this
                ->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $permissionGroup->getId(), $comparison);
        } elseif ($permissionGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupUserTableMap::COL_GROUP_ID, $permissionGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPermissionGroup() only accepts arguments of type \Tekstove\ApiBundle\Model\Acl\PermissionGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function joinPermissionGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionGroup');

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
            $this->addJoinObject($join, 'PermissionGroup');
        }

        return $this;
    }

    /**
     * Use the PermissionGroup relation PermissionGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery A secondary query class using the current class as primary query
     */
    public function usePermissionGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionGroup', '\Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPermissionGroupUser $permissionGroupUser Object to remove from the list of results
     *
     * @return $this|ChildPermissionGroupUserQuery The current query, for fluid interface
     */
    public function prune($permissionGroupUser = null)
    {
        if ($permissionGroupUser) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PermissionGroupUserTableMap::COL_USER_ID), $permissionGroupUser->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PermissionGroupUserTableMap::COL_GROUP_ID), $permissionGroupUser->getGroupId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the permission_group_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionGroupUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PermissionGroupUserTableMap::clearInstancePool();
            PermissionGroupUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PermissionGroupUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PermissionGroupUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PermissionGroupUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PermissionGroupUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PermissionGroupUserQuery
