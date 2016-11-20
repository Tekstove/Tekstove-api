<?php

namespace Tekstove\ApiBundle\Model\Lyric\Base;

use \Exception;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Tekstove\ApiBundle\Model\Lyric\LyricRedirect as ChildLyricRedirect;
use Tekstove\ApiBundle\Model\Lyric\LyricRedirectQuery as ChildLyricRedirectQuery;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricRedirectTableMap;

/**
 * Base class that represents a query for the 'lyric_redirect' table.
 *
 *
 *
 * @method     ChildLyricRedirectQuery orderByDeletedId($order = Criteria::ASC) Order by the deleted_id column
 * @method     ChildLyricRedirectQuery orderByRedirectId($order = Criteria::ASC) Order by the redirect_id column
 *
 * @method     ChildLyricRedirectQuery groupByDeletedId() Group by the deleted_id column
 * @method     ChildLyricRedirectQuery groupByRedirectId() Group by the redirect_id column
 *
 * @method     ChildLyricRedirectQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLyricRedirectQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLyricRedirectQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLyricRedirectQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLyricRedirectQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLyricRedirectQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLyricRedirect findOne(ConnectionInterface $con = null) Return the first ChildLyricRedirect matching the query
 * @method     ChildLyricRedirect findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLyricRedirect matching the query, or a new ChildLyricRedirect object populated from the query conditions when no match is found
 *
 * @method     ChildLyricRedirect findOneByDeletedId(int $deleted_id) Return the first ChildLyricRedirect filtered by the deleted_id column
 * @method     ChildLyricRedirect findOneByRedirectId(int $redirect_id) Return the first ChildLyricRedirect filtered by the redirect_id column *

 * @method     ChildLyricRedirect requirePk($key, ConnectionInterface $con = null) Return the ChildLyricRedirect by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricRedirect requireOne(ConnectionInterface $con = null) Return the first ChildLyricRedirect matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricRedirect requireOneByDeletedId(int $deleted_id) Return the first ChildLyricRedirect filtered by the deleted_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyricRedirect requireOneByRedirectId(int $redirect_id) Return the first ChildLyricRedirect filtered by the redirect_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyricRedirect[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLyricRedirect objects based on current ModelCriteria
 * @method     ChildLyricRedirect[]|ObjectCollection findByDeletedId(int $deleted_id) Return ChildLyricRedirect objects filtered by the deleted_id column
 * @method     ChildLyricRedirect[]|ObjectCollection findByRedirectId(int $redirect_id) Return ChildLyricRedirect objects filtered by the redirect_id column
 * @method     ChildLyricRedirect[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LyricRedirectQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Lyric\Base\LyricRedirectQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricRedirect', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLyricRedirectQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLyricRedirectQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLyricRedirectQuery) {
            return $criteria;
        }
        $query = new ChildLyricRedirectQuery();
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
     * @return ChildLyricRedirect|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        throw new LogicException('The LyricRedirect object has no primary key');
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
        throw new LogicException('The LyricRedirect object has no primary key');
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildLyricRedirectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        throw new LogicException('The LyricRedirect object has no primary key');
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLyricRedirectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        throw new LogicException('The LyricRedirect object has no primary key');
    }

    /**
     * Filter the query on the deleted_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedId(1234); // WHERE deleted_id = 1234
     * $query->filterByDeletedId(array(12, 34)); // WHERE deleted_id IN (12, 34)
     * $query->filterByDeletedId(array('min' => 12)); // WHERE deleted_id > 12
     * </code>
     *
     * @param     mixed $deletedId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricRedirectQuery The current query, for fluid interface
     */
    public function filterByDeletedId($deletedId = null, $comparison = null)
    {
        if (is_array($deletedId)) {
            $useMinMax = false;
            if (isset($deletedId['min'])) {
                $this->addUsingAlias(LyricRedirectTableMap::COL_DELETED_ID, $deletedId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedId['max'])) {
                $this->addUsingAlias(LyricRedirectTableMap::COL_DELETED_ID, $deletedId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricRedirectTableMap::COL_DELETED_ID, $deletedId, $comparison);
    }

    /**
     * Filter the query on the redirect_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRedirectId(1234); // WHERE redirect_id = 1234
     * $query->filterByRedirectId(array(12, 34)); // WHERE redirect_id IN (12, 34)
     * $query->filterByRedirectId(array('min' => 12)); // WHERE redirect_id > 12
     * </code>
     *
     * @param     mixed $redirectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricRedirectQuery The current query, for fluid interface
     */
    public function filterByRedirectId($redirectId = null, $comparison = null)
    {
        if (is_array($redirectId)) {
            $useMinMax = false;
            if (isset($redirectId['min'])) {
                $this->addUsingAlias(LyricRedirectTableMap::COL_REDIRECT_ID, $redirectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($redirectId['max'])) {
                $this->addUsingAlias(LyricRedirectTableMap::COL_REDIRECT_ID, $redirectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricRedirectTableMap::COL_REDIRECT_ID, $redirectId, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLyricRedirect $lyricRedirect Object to remove from the list of results
     *
     * @return $this|ChildLyricRedirectQuery The current query, for fluid interface
     */
    public function prune($lyricRedirect = null)
    {
        if ($lyricRedirect) {
            throw new LogicException('LyricRedirect object has no primary key');

        }

        return $this;
    }

    /**
     * Deletes all rows from the lyric_redirect table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricRedirectTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LyricRedirectTableMap::clearInstancePool();
            LyricRedirectTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LyricRedirectTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LyricRedirectTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LyricRedirectTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LyricRedirectTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LyricRedirectQuery
