<?php

namespace Tekstove\ApiBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Tekstove\ApiBundle\Model\Language as ChildLanguage;
use Tekstove\ApiBundle\Model\LanguageQuery as ChildLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguage;
use Tekstove\ApiBundle\Model\Map\LanguageTableMap;

/**
 * Base class that represents a query for the 'language' table.
 *
 *
 *
 * @method     ChildLanguageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLanguageQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildLanguageQuery groupById() Group by the id column
 * @method     ChildLanguageQuery groupByName() Group by the name column
 *
 * @method     ChildLanguageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLanguageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLanguageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLanguageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLanguageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLanguageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLanguageQuery leftJoinLyricLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricLanguage relation
 * @method     ChildLanguageQuery rightJoinLyricLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricLanguage relation
 * @method     ChildLanguageQuery innerJoinLyricLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricLanguage relation
 *
 * @method     ChildLanguageQuery joinWithLyricLanguage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricLanguage relation
 *
 * @method     ChildLanguageQuery leftJoinWithLyricLanguage() Adds a LEFT JOIN clause and with to the query using the LyricLanguage relation
 * @method     ChildLanguageQuery rightJoinWithLyricLanguage() Adds a RIGHT JOIN clause and with to the query using the LyricLanguage relation
 * @method     ChildLanguageQuery innerJoinWithLyricLanguage() Adds a INNER JOIN clause and with to the query using the LyricLanguage relation
 *
 * @method     \Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLanguage findOne(ConnectionInterface $con = null) Return the first ChildLanguage matching the query
 * @method     ChildLanguage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLanguage matching the query, or a new ChildLanguage object populated from the query conditions when no match is found
 *
 * @method     ChildLanguage findOneById(int $id) Return the first ChildLanguage filtered by the id column
 * @method     ChildLanguage findOneByName(string $name) Return the first ChildLanguage filtered by the name column *

 * @method     ChildLanguage requirePk($key, ConnectionInterface $con = null) Return the ChildLanguage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOne(ConnectionInterface $con = null) Return the first ChildLanguage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguage requireOneById(int $id) Return the first ChildLanguage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLanguage requireOneByName(string $name) Return the first ChildLanguage filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLanguage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLanguage objects based on current ModelCriteria
 * @method     ChildLanguage[]|ObjectCollection findById(int $id) Return ChildLanguage objects filtered by the id column
 * @method     ChildLanguage[]|ObjectCollection findByName(string $name) Return ChildLanguage objects filtered by the name column
 * @method     ChildLanguage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LanguageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Base\LanguageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Language', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLanguageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLanguageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLanguageQuery) {
            return $criteria;
        }
        $query = new ChildLanguageQuery();
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
     * @return ChildLanguage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LanguageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LanguageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLanguage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `name` FROM `language` WHERE `id` = :p0';
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
            /** @var ChildLanguage $obj */
            $obj = new ChildLanguage();
            $obj->hydrate($row);
            LanguageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLanguage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LanguageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LanguageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LanguageTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric\LyricLanguage object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric\LyricLanguage|ObjectCollection $lyricLanguage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLyricLanguage($lyricLanguage, $comparison = null)
    {
        if ($lyricLanguage instanceof \Tekstove\ApiBundle\Model\Lyric\LyricLanguage) {
            return $this
                ->addUsingAlias(LanguageTableMap::COL_ID, $lyricLanguage->getLanguageId(), $comparison);
        } elseif ($lyricLanguage instanceof ObjectCollection) {
            return $this
                ->useLyricLanguageQuery()
                ->filterByPrimaryKeys($lyricLanguage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLyricLanguage() only accepts arguments of type \Tekstove\ApiBundle\Model\Lyric\LyricLanguage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LyricLanguage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function joinLyricLanguage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LyricLanguage');

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
            $this->addJoinObject($join, 'LyricLanguage');
        }

        return $this;
    }

    /**
     * Use the LyricLanguage relation LyricLanguage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery A secondary query class using the current class as primary query
     */
    public function useLyricLanguageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinLyricLanguage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LyricLanguage', '\Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery');
    }

    /**
     * Filter the query by a related Lyric object
     * using the lyric_language table as cross reference
     *
     * @param Lyric $lyric the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLanguageQuery The current query, for fluid interface
     */
    public function filterByLyric($lyric, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLyricLanguageQuery()
            ->filterByLyric($lyric, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLanguage $language Object to remove from the list of results
     *
     * @return $this|ChildLanguageQuery The current query, for fluid interface
     */
    public function prune($language = null)
    {
        if ($language) {
            $this->addUsingAlias(LanguageTableMap::COL_ID, $language->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

} // LanguageQuery
