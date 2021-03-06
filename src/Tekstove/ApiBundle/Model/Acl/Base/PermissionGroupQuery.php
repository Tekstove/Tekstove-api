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
use Tekstove\ApiBundle\Model\Acl\PermissionGroup as ChildPermissionGroup;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery as ChildPermissionGroupQuery;
use Tekstove\ApiBundle\Model\Acl\Map\PermissionGroupTableMap;

/**
 * Base class that represents a query for the 'permission_group' table.
 *
 *
 *
 * @method     ChildPermissionGroupQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPermissionGroupQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPermissionGroupQuery orderByImage($order = Criteria::ASC) Order by the image column
 *
 * @method     ChildPermissionGroupQuery groupById() Group by the id column
 * @method     ChildPermissionGroupQuery groupByName() Group by the name column
 * @method     ChildPermissionGroupQuery groupByImage() Group by the image column
 *
 * @method     ChildPermissionGroupQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPermissionGroupQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPermissionGroupQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPermissionGroupQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPermissionGroupQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPermissionGroupQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPermissionGroupQuery leftJoinPermissionGroupPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroupPermission relation
 * @method     ChildPermissionGroupQuery rightJoinPermissionGroupPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroupPermission relation
 * @method     ChildPermissionGroupQuery innerJoinPermissionGroupPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroupPermission relation
 *
 * @method     ChildPermissionGroupQuery joinWithPermissionGroupPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroupPermission relation
 *
 * @method     ChildPermissionGroupQuery leftJoinWithPermissionGroupPermission() Adds a LEFT JOIN clause and with to the query using the PermissionGroupPermission relation
 * @method     ChildPermissionGroupQuery rightJoinWithPermissionGroupPermission() Adds a RIGHT JOIN clause and with to the query using the PermissionGroupPermission relation
 * @method     ChildPermissionGroupQuery innerJoinWithPermissionGroupPermission() Adds a INNER JOIN clause and with to the query using the PermissionGroupPermission relation
 *
 * @method     ChildPermissionGroupQuery leftJoinPermissionGroupUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroupUser relation
 * @method     ChildPermissionGroupQuery rightJoinPermissionGroupUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroupUser relation
 * @method     ChildPermissionGroupQuery innerJoinPermissionGroupUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroupUser relation
 *
 * @method     ChildPermissionGroupQuery joinWithPermissionGroupUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroupUser relation
 *
 * @method     ChildPermissionGroupQuery leftJoinWithPermissionGroupUser() Adds a LEFT JOIN clause and with to the query using the PermissionGroupUser relation
 * @method     ChildPermissionGroupQuery rightJoinWithPermissionGroupUser() Adds a RIGHT JOIN clause and with to the query using the PermissionGroupUser relation
 * @method     ChildPermissionGroupQuery innerJoinWithPermissionGroupUser() Adds a INNER JOIN clause and with to the query using the PermissionGroupUser relation
 *
 * @method     \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermissionQuery|\Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPermissionGroup findOne(ConnectionInterface $con = null) Return the first ChildPermissionGroup matching the query
 * @method     ChildPermissionGroup findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPermissionGroup matching the query, or a new ChildPermissionGroup object populated from the query conditions when no match is found
 *
 * @method     ChildPermissionGroup findOneById(int $id) Return the first ChildPermissionGroup filtered by the id column
 * @method     ChildPermissionGroup findOneByName(string $name) Return the first ChildPermissionGroup filtered by the name column
 * @method     ChildPermissionGroup findOneByImage(string $image) Return the first ChildPermissionGroup filtered by the image column *

 * @method     ChildPermissionGroup requirePk($key, ConnectionInterface $con = null) Return the ChildPermissionGroup by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroup requireOne(ConnectionInterface $con = null) Return the first ChildPermissionGroup matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroup requireOneById(int $id) Return the first ChildPermissionGroup filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroup requireOneByName(string $name) Return the first ChildPermissionGroup filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPermissionGroup requireOneByImage(string $image) Return the first ChildPermissionGroup filtered by the image column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPermissionGroup[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPermissionGroup objects based on current ModelCriteria
 * @method     ChildPermissionGroup[]|ObjectCollection findById(int $id) Return ChildPermissionGroup objects filtered by the id column
 * @method     ChildPermissionGroup[]|ObjectCollection findByName(string $name) Return ChildPermissionGroup objects filtered by the name column
 * @method     ChildPermissionGroup[]|ObjectCollection findByImage(string $image) Return ChildPermissionGroup objects filtered by the image column
 * @method     ChildPermissionGroup[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PermissionGroupQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Acl\Base\PermissionGroupQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Acl\\PermissionGroup', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPermissionGroupQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPermissionGroupQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPermissionGroupQuery) {
            return $criteria;
        }
        $query = new ChildPermissionGroupQuery();
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
     * @return ChildPermissionGroup|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PermissionGroupTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PermissionGroupTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPermissionGroup A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name`, `image` FROM `permission_group` WHERE `id` = :p0';
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
            /** @var ChildPermissionGroup $obj */
            $obj = new ChildPermissionGroup();
            $obj->hydrate($row);
            PermissionGroupTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPermissionGroup|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the image column
     *
     * Example usage:
     * <code>
     * $query->filterByImage('fooValue');   // WHERE image = 'fooValue'
     * $query->filterByImage('%fooValue%', Criteria::LIKE); // WHERE image LIKE '%fooValue%'
     * </code>
     *
     * @param     string $image The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByImage($image = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($image)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PermissionGroupTableMap::COL_IMAGE, $image, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission|ObjectCollection $permissionGroupPermission the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByPermissionGroupPermission($permissionGroupPermission, $comparison = null)
    {
        if ($permissionGroupPermission instanceof \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission) {
            return $this
                ->addUsingAlias(PermissionGroupTableMap::COL_ID, $permissionGroupPermission->getGroupId(), $comparison);
        } elseif ($permissionGroupPermission instanceof ObjectCollection) {
            return $this
                ->usePermissionGroupPermissionQuery()
                ->filterByPrimaryKeys($permissionGroupPermission->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPermissionGroupPermission() only accepts arguments of type \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionGroupPermission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function joinPermissionGroupPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionGroupPermission');

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
            $this->addJoinObject($join, 'PermissionGroupPermission');
        }

        return $this;
    }

    /**
     * Use the PermissionGroupPermission relation PermissionGroupPermission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Acl\PermissionGroupPermissionQuery A secondary query class using the current class as primary query
     */
    public function usePermissionGroupPermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionGroupPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionGroupPermission', '\Tekstove\ApiBundle\Model\Acl\PermissionGroupPermissionQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser|ObjectCollection $permissionGroupUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function filterByPermissionGroupUser($permissionGroupUser, $comparison = null)
    {
        if ($permissionGroupUser instanceof \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser) {
            return $this
                ->addUsingAlias(PermissionGroupTableMap::COL_ID, $permissionGroupUser->getGroupId(), $comparison);
        } elseif ($permissionGroupUser instanceof ObjectCollection) {
            return $this
                ->usePermissionGroupUserQuery()
                ->filterByPrimaryKeys($permissionGroupUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPermissionGroupUser() only accepts arguments of type \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PermissionGroupUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function joinPermissionGroupUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PermissionGroupUser');

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
            $this->addJoinObject($join, 'PermissionGroupUser');
        }

        return $this;
    }

    /**
     * Use the PermissionGroupUser relation PermissionGroupUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery A secondary query class using the current class as primary query
     */
    public function usePermissionGroupUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPermissionGroupUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PermissionGroupUser', '\Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPermissionGroup $permissionGroup Object to remove from the list of results
     *
     * @return $this|ChildPermissionGroupQuery The current query, for fluid interface
     */
    public function prune($permissionGroup = null)
    {
        if ($permissionGroup) {
            $this->addUsingAlias(PermissionGroupTableMap::COL_ID, $permissionGroup->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // PermissionGroupQuery
