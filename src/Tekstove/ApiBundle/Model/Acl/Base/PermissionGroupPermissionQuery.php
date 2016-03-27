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
use Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission as ChildPermissionGroupPermission;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupPermissionQuery as ChildPermissionGroupPermissionQuery;
use Tekstove\ApiBundle\Model\Acl\Map\PermissionGroupPermissionTableMap;

/**
 * Base class that represents a query for the 'permission_group_permission' table.
 *
 *
 *
 * @method     ChildPermissionGroupPermissionQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildPermissionGroupPermissionQuery orderByPermissionId($order = Criteria::ASC) Order by the permission_id column
 *
 * @method     ChildPermissionGroupPermissionQuery groupByGroupId() Group by the group_id column
 * @method     ChildPermissionGroupPermissionQuery groupByPermissionId() Group by the permission_id column
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionGroupPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionGroupPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPermissionGroupPermissionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPermissionGroupPermissionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinPermissionGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinPermissionGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinPermissionGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupPermissionQuery joinWithPermissionGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWithPermissionGroup() Adds a LEFT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinWithPermissionGroup() Adds a RIGHT JOIN clause and with to the query using the PermissionGroup relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinWithPermissionGroup() Adds a INNER JOIN clause and with to the query using the PermissionGroup relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the Permission relation
 *
 * @method     ChildPermissionGroupPermissionQuery joinWithPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Permission relation
 *
 * @method     ChildPermissionGroupPermissionQuery leftJoinWithPermission() Adds a LEFT JOIN clause and with to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery rightJoinWithPermission() Adds a RIGHT JOIN clause and with to the query using the Permission relation
 * @method     ChildPermissionGroupPermissionQuery innerJoinWithPermission() Adds a INNER JOIN clause and with to the query using the Permission relation
 *
 * @method     \Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery|\Tekstove\ApiBundle\Model\Acl\PermissionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPermissionGroupPermission findOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query
 * @method     ChildPermissionGroupPermission findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query, or a new ChildPermissionGroupPermission object populated from the query conditions when no match is found
 *
 * @method     ChildPermissionGroupPermission findOneByGroupId(int $group_id) Return the first ChildPermissionGroupPermission filtered by the group_id column
 * @method     ChildPermissionGroupPermission findOneByPermissionId(int $permission_id) Return the first ChildPermissionGroupPermission filtered by the permission_id column *

 * @method     ChildPermissionGroupPermission requirePk($key, ConnectionInterface $con = null) Return the ChildPermissionGroupPermission by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupPermission requireOne(ConnectionInterface $con = null) Return the first ChildPermissionGroupPermission matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupPermission requireOneByGroupId(int $group_id) Return the first ChildPermissionGroupPermission filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroupPermission requireOneByPermissionId(int $permission_id) Return the first ChildPermissionGroupPermission filtered by the permission_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroupPermission[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPermissionGroupPermission objects based on current ModelCriteria
 * @method     ChildPermissionGroupPermission[]|ObjectCollection findByGroupId(int $group_id) Return ChildPermissionGroupPermission objects filtered by the group_id column
 * @method     ChildPermissionGroupPermission[]|ObjectCollection findByPermissionId(int $permission_id) Return ChildPermissionGroupPermission objects filtered by the permission_id column
 * @method     ChildPermissionGroupPermission[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PermissionGroupPermissionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Acl\Base\PermissionGroupPermissionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Acl\\PermissionGroupPermission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionGroupPermissionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionGroupPermissionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPermissionGroupPermissionQuery) {
            return $criteria;
        }
        $query = new ChildPermissionGroupPermissionQuery();
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
     * @param array[$group_id, $permission_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPermissionGroupPermission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionGroupPermissionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PermissionGroupPermissionTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildPermissionGroupPermission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT group_id, permission_id FROM permission_group_permission WHERE group_id = :p0 AND permission_id = :p1';
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
            /** @var ChildPermissionGroupPermission $obj */
            $obj = new ChildPermissionGroupPermission();
            $obj->hydrate($row);
            PermissionGroupPermissionTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildPermissionGroupPermission|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PermissionGroupPermissionTableMap::COL_GROUP_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $groupId, $comparison);
    }

    /**
     * Filter the query on the permission_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPermissionId(1234); // WHERE permission_id = 1234
     * $query->filterByPermissionId(array(12, 34)); // WHERE permission_id IN (12, 34)
     * $query->filterByPermissionId(array('min' => 12)); // WHERE permission_id > 12
     * </code>
     *
     * @see       filterByPermission()
     *
     * @param     mixed $permissionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermissionId($permissionId = null, $comparison = null)
    {
        if (is_array($permissionId)) {
            $useMinMax = false;
            if (isset($permissionId['min'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $permissionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($permissionId['max'])) {
                $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $permissionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $permissionId, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\PermissionGroup object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\PermissionGroup|ObjectCollection $permissionGroup The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermissionGroup($permissionGroup, $comparison = null)
    {
        if ($permissionGroup instanceof \Tekstove\ApiBundle\Model\Acl\PermissionGroup) {
            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $permissionGroup->getId(), $comparison);
        } elseif ($permissionGroup instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_GROUP_ID, $permissionGroup->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\Permission object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\Permission|ObjectCollection $permission The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function filterByPermission($permission, $comparison = null)
    {
        if ($permission instanceof \Tekstove\ApiBundle\Model\Acl\Permission) {
            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $permission->getId(), $comparison);
        } elseif ($permission instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PermissionGroupPermissionTableMap::COL_PERMISSION_ID, $permission->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPermission() only accepts arguments of type \Tekstove\ApiBundle\Model\Acl\Permission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Permission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function joinPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Permission');

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
            $this->addJoinObject($join, 'Permission');
        }

        return $this;
    }

    /**
     * Use the Permission relation Permission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Acl\PermissionQuery A secondary query class using the current class as primary query
     */
    public function usePermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Permission', '\Tekstove\ApiBundle\Model\Acl\PermissionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPermissionGroupPermission $permissionGroupPermission Object to remove from the list of results
     *
     * @return $this|ChildPermissionGroupPermissionQuery The current query, for fluid interface
     */
    public function prune($permissionGroupPermission = null)
    {
        if ($permissionGroupPermission) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PermissionGroupPermissionTableMap::COL_GROUP_ID), $permissionGroupPermission->getGroupId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PermissionGroupPermissionTableMap::COL_PERMISSION_ID), $permissionGroupPermission->getPermissionId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

} // PermissionGroupPermissionQuery
