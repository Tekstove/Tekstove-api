<?php

namespace Tekstove\ApiBundle\Model\User\Base;

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
use Tekstove\ApiBundle\Model\User\Pm as ChildPm;
use Tekstove\ApiBundle\Model\User\PmQuery as ChildPmQuery;
use Tekstove\ApiBundle\Model\User\Map\PmTableMap;

/**
 * Base class that represents a query for the 'pm' table.
 *
 *
 *
 * @method     ChildPmQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPmQuery orderByUserTo($order = Criteria::ASC) Order by the user_to column
 * @method     ChildPmQuery orderByUserFrom($order = Criteria::ASC) Order by the user_from column
 * @method     ChildPmQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildPmQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildPmQuery orderByRead($order = Criteria::ASC) Order by the read column
 * @method     ChildPmQuery orderByDatetime($order = Criteria::ASC) Order by the datetime column
 *
 * @method     ChildPmQuery groupById() Group by the id column
 * @method     ChildPmQuery groupByUserTo() Group by the user_to column
 * @method     ChildPmQuery groupByUserFrom() Group by the user_from column
 * @method     ChildPmQuery groupByText() Group by the text column
 * @method     ChildPmQuery groupByTitle() Group by the title column
 * @method     ChildPmQuery groupByRead() Group by the read column
 * @method     ChildPmQuery groupByDatetime() Group by the datetime column
 *
 * @method     ChildPmQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPmQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPmQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPmQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPmQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPmQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPmQuery leftJoinUserRelatedByUserTo($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUserTo relation
 * @method     ChildPmQuery rightJoinUserRelatedByUserTo($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUserTo relation
 * @method     ChildPmQuery innerJoinUserRelatedByUserTo($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUserTo relation
 *
 * @method     ChildPmQuery joinWithUserRelatedByUserTo($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelatedByUserTo relation
 *
 * @method     ChildPmQuery leftJoinWithUserRelatedByUserTo() Adds a LEFT JOIN clause and with to the query using the UserRelatedByUserTo relation
 * @method     ChildPmQuery rightJoinWithUserRelatedByUserTo() Adds a RIGHT JOIN clause and with to the query using the UserRelatedByUserTo relation
 * @method     ChildPmQuery innerJoinWithUserRelatedByUserTo() Adds a INNER JOIN clause and with to the query using the UserRelatedByUserTo relation
 *
 * @method     ChildPmQuery leftJoinUserRelatedByUserFrom($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserRelatedByUserFrom relation
 * @method     ChildPmQuery rightJoinUserRelatedByUserFrom($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserRelatedByUserFrom relation
 * @method     ChildPmQuery innerJoinUserRelatedByUserFrom($relationAlias = null) Adds a INNER JOIN clause to the query using the UserRelatedByUserFrom relation
 *
 * @method     ChildPmQuery joinWithUserRelatedByUserFrom($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserRelatedByUserFrom relation
 *
 * @method     ChildPmQuery leftJoinWithUserRelatedByUserFrom() Adds a LEFT JOIN clause and with to the query using the UserRelatedByUserFrom relation
 * @method     ChildPmQuery rightJoinWithUserRelatedByUserFrom() Adds a RIGHT JOIN clause and with to the query using the UserRelatedByUserFrom relation
 * @method     ChildPmQuery innerJoinWithUserRelatedByUserFrom() Adds a INNER JOIN clause and with to the query using the UserRelatedByUserFrom relation
 *
 * @method     \Tekstove\ApiBundle\Model\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPm findOne(ConnectionInterface $con = null) Return the first ChildPm matching the query
 * @method     ChildPm findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPm matching the query, or a new ChildPm object populated from the query conditions when no match is found
 *
 * @method     ChildPm findOneById(int $id) Return the first ChildPm filtered by the id column
 * @method     ChildPm findOneByUserTo(int $user_to) Return the first ChildPm filtered by the user_to column
 * @method     ChildPm findOneByUserFrom(int $user_from) Return the first ChildPm filtered by the user_from column
 * @method     ChildPm findOneByText(string $text) Return the first ChildPm filtered by the text column
 * @method     ChildPm findOneByTitle(string $title) Return the first ChildPm filtered by the title column
 * @method     ChildPm findOneByRead(boolean $read) Return the first ChildPm filtered by the read column
 * @method     ChildPm findOneByDatetime(string $datetime) Return the first ChildPm filtered by the datetime column *

 * @method     ChildPm requirePk($key, ConnectionInterface $con = null) Return the ChildPm by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOne(ConnectionInterface $con = null) Return the first ChildPm matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPm requireOneById(int $id) Return the first ChildPm filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByUserTo(int $user_to) Return the first ChildPm filtered by the user_to column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByUserFrom(int $user_from) Return the first ChildPm filtered by the user_from column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByText(string $text) Return the first ChildPm filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByTitle(string $title) Return the first ChildPm filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByRead(boolean $read) Return the first ChildPm filtered by the read column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPm requireOneByDatetime(string $datetime) Return the first ChildPm filtered by the datetime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPm[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPm objects based on current ModelCriteria
 * @method     ChildPm[]|ObjectCollection findById(int $id) Return ChildPm objects filtered by the id column
 * @method     ChildPm[]|ObjectCollection findByUserTo(int $user_to) Return ChildPm objects filtered by the user_to column
 * @method     ChildPm[]|ObjectCollection findByUserFrom(int $user_from) Return ChildPm objects filtered by the user_from column
 * @method     ChildPm[]|ObjectCollection findByText(string $text) Return ChildPm objects filtered by the text column
 * @method     ChildPm[]|ObjectCollection findByTitle(string $title) Return ChildPm objects filtered by the title column
 * @method     ChildPm[]|ObjectCollection findByRead(boolean $read) Return ChildPm objects filtered by the read column
 * @method     ChildPm[]|ObjectCollection findByDatetime(string $datetime) Return ChildPm objects filtered by the datetime column
 * @method     ChildPm[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PmQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\User\Base\PmQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\User\\Pm', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPmQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPmQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPmQuery) {
            return $criteria;
        }
        $query = new ChildPmQuery();
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
     * @return ChildPm|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PmTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PmTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPm A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `user_to`, `user_from`, `text`, `title`, `read`, `datetime` FROM `pm` WHERE `id` = :p0';
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
            /** @var ChildPm $obj */
            $obj = new ChildPm();
            $obj->hydrate($row);
            PmTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPm|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PmTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PmTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PmTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PmTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_to column
     *
     * Example usage:
     * <code>
     * $query->filterByUserTo(1234); // WHERE user_to = 1234
     * $query->filterByUserTo(array(12, 34)); // WHERE user_to IN (12, 34)
     * $query->filterByUserTo(array('min' => 12)); // WHERE user_to > 12
     * </code>
     *
     * @see       filterByUserRelatedByUserTo()
     *
     * @param     mixed $userTo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByUserTo($userTo = null, $comparison = null)
    {
        if (is_array($userTo)) {
            $useMinMax = false;
            if (isset($userTo['min'])) {
                $this->addUsingAlias(PmTableMap::COL_USER_TO, $userTo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userTo['max'])) {
                $this->addUsingAlias(PmTableMap::COL_USER_TO, $userTo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_USER_TO, $userTo, $comparison);
    }

    /**
     * Filter the query on the user_from column
     *
     * Example usage:
     * <code>
     * $query->filterByUserFrom(1234); // WHERE user_from = 1234
     * $query->filterByUserFrom(array(12, 34)); // WHERE user_from IN (12, 34)
     * $query->filterByUserFrom(array('min' => 12)); // WHERE user_from > 12
     * </code>
     *
     * @see       filterByUserRelatedByUserFrom()
     *
     * @param     mixed $userFrom The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByUserFrom($userFrom = null, $comparison = null)
    {
        if (is_array($userFrom)) {
            $useMinMax = false;
            if (isset($userFrom['min'])) {
                $this->addUsingAlias(PmTableMap::COL_USER_FROM, $userFrom['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userFrom['max'])) {
                $this->addUsingAlias(PmTableMap::COL_USER_FROM, $userFrom['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_USER_FROM, $userFrom, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%', Criteria::LIKE); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the read column
     *
     * Example usage:
     * <code>
     * $query->filterByRead(true); // WHERE read = true
     * $query->filterByRead('yes'); // WHERE read = true
     * </code>
     *
     * @param     boolean|string $read The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByRead($read = null, $comparison = null)
    {
        if (is_string($read)) {
            $read = in_array(strtolower($read), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PmTableMap::COL_READ, $read, $comparison);
    }

    /**
     * Filter the query on the datetime column
     *
     * Example usage:
     * <code>
     * $query->filterByDatetime('2011-03-14'); // WHERE datetime = '2011-03-14'
     * $query->filterByDatetime('now'); // WHERE datetime = '2011-03-14'
     * $query->filterByDatetime(array('max' => 'yesterday')); // WHERE datetime > '2011-03-13'
     * </code>
     *
     * @param     mixed $datetime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function filterByDatetime($datetime = null, $comparison = null)
    {
        if (is_array($datetime)) {
            $useMinMax = false;
            if (isset($datetime['min'])) {
                $this->addUsingAlias(PmTableMap::COL_DATETIME, $datetime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datetime['max'])) {
                $this->addUsingAlias(PmTableMap::COL_DATETIME, $datetime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PmTableMap::COL_DATETIME, $datetime, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\User object
     *
     * @param \Tekstove\ApiBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPmQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUserTo($user, $comparison = null)
    {
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            return $this
                ->addUsingAlias(PmTableMap::COL_USER_TO, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PmTableMap::COL_USER_TO, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUserTo() only accepts arguments of type \Tekstove\ApiBundle\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUserTo relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUserTo($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByUserTo');

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
            $this->addJoinObject($join, 'UserRelatedByUserTo');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByUserTo relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUserToQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedByUserTo($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUserTo', '\Tekstove\ApiBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\User object
     *
     * @param \Tekstove\ApiBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPmQuery The current query, for fluid interface
     */
    public function filterByUserRelatedByUserFrom($user, $comparison = null)
    {
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            return $this
                ->addUsingAlias(PmTableMap::COL_USER_FROM, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PmTableMap::COL_USER_FROM, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserRelatedByUserFrom() only accepts arguments of type \Tekstove\ApiBundle\Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserRelatedByUserFrom relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function joinUserRelatedByUserFrom($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserRelatedByUserFrom');

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
            $this->addJoinObject($join, 'UserRelatedByUserFrom');
        }

        return $this;
    }

    /**
     * Use the UserRelatedByUserFrom relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserRelatedByUserFromQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserRelatedByUserFrom($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserRelatedByUserFrom', '\Tekstove\ApiBundle\Model\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPm $pm Object to remove from the list of results
     *
     * @return $this|ChildPmQuery The current query, for fluid interface
     */
    public function prune($pm = null)
    {
        if ($pm) {
            $this->addUsingAlias(PmTableMap::COL_ID, $pm->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pm table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PmTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PmTableMap::clearInstancePool();
            PmTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PmTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PmTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PmTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PmTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PmQuery
