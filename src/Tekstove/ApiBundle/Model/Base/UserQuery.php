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
use Tekstove\ApiBundle\Model\User as ChildUser;
use Tekstove\ApiBundle\Model\UserQuery as ChildUserQuery;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUser;
use Tekstove\ApiBundle\Model\Forum\Post;
use Tekstove\ApiBundle\Model\Forum\Topic;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\LyricVote;
use Tekstove\ApiBundle\Model\Map\UserTableMap;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByapiKey($order = Criteria::ASC) Order by the api_key column
 * @method     ChildUserQuery orderByMail($order = Criteria::ASC) Order by the mail column
 * @method     ChildUserQuery orderByAvatar($order = Criteria::ASC) Order by the avatar column
 * @method     ChildUserQuery orderByAbout($order = Criteria::ASC) Order by the about column
 * @method     ChildUserQuery orderByAutoplay($order = Criteria::ASC) Order by the autoplay column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByapiKey() Group by the api_key column
 * @method     ChildUserQuery groupByMail() Group by the mail column
 * @method     ChildUserQuery groupByAvatar() Group by the avatar column
 * @method     ChildUserQuery groupByAbout() Group by the about column
 * @method     ChildUserQuery groupByAutoplay() Group by the autoplay column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinPermissionGroupUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PermissionGroupUser relation
 * @method     ChildUserQuery rightJoinPermissionGroupUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PermissionGroupUser relation
 * @method     ChildUserQuery innerJoinPermissionGroupUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PermissionGroupUser relation
 *
 * @method     ChildUserQuery joinWithPermissionGroupUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PermissionGroupUser relation
 *
 * @method     ChildUserQuery leftJoinWithPermissionGroupUser() Adds a LEFT JOIN clause and with to the query using the PermissionGroupUser relation
 * @method     ChildUserQuery rightJoinWithPermissionGroupUser() Adds a RIGHT JOIN clause and with to the query using the PermissionGroupUser relation
 * @method     ChildUserQuery innerJoinWithPermissionGroupUser() Adds a INNER JOIN clause and with to the query using the PermissionGroupUser relation
 *
 * @method     ChildUserQuery leftJoinLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the Lyric relation
 * @method     ChildUserQuery rightJoinLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Lyric relation
 * @method     ChildUserQuery innerJoinLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the Lyric relation
 *
 * @method     ChildUserQuery joinWithLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Lyric relation
 *
 * @method     ChildUserQuery leftJoinWithLyric() Adds a LEFT JOIN clause and with to the query using the Lyric relation
 * @method     ChildUserQuery rightJoinWithLyric() Adds a RIGHT JOIN clause and with to the query using the Lyric relation
 * @method     ChildUserQuery innerJoinWithLyric() Adds a INNER JOIN clause and with to the query using the Lyric relation
 *
 * @method     ChildUserQuery leftJoinLyricTranslation($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricTranslation relation
 * @method     ChildUserQuery rightJoinLyricTranslation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricTranslation relation
 * @method     ChildUserQuery innerJoinLyricTranslation($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricTranslation relation
 *
 * @method     ChildUserQuery joinWithLyricTranslation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricTranslation relation
 *
 * @method     ChildUserQuery leftJoinWithLyricTranslation() Adds a LEFT JOIN clause and with to the query using the LyricTranslation relation
 * @method     ChildUserQuery rightJoinWithLyricTranslation() Adds a RIGHT JOIN clause and with to the query using the LyricTranslation relation
 * @method     ChildUserQuery innerJoinWithLyricTranslation() Adds a INNER JOIN clause and with to the query using the LyricTranslation relation
 *
 * @method     ChildUserQuery leftJoinLyricVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricVote relation
 * @method     ChildUserQuery rightJoinLyricVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricVote relation
 * @method     ChildUserQuery innerJoinLyricVote($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricVote relation
 *
 * @method     ChildUserQuery joinWithLyricVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricVote relation
 *
 * @method     ChildUserQuery leftJoinWithLyricVote() Adds a LEFT JOIN clause and with to the query using the LyricVote relation
 * @method     ChildUserQuery rightJoinWithLyricVote() Adds a RIGHT JOIN clause and with to the query using the LyricVote relation
 * @method     ChildUserQuery innerJoinWithLyricVote() Adds a INNER JOIN clause and with to the query using the LyricVote relation
 *
 * @method     ChildUserQuery leftJoinArtist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Artist relation
 * @method     ChildUserQuery rightJoinArtist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Artist relation
 * @method     ChildUserQuery innerJoinArtist($relationAlias = null) Adds a INNER JOIN clause to the query using the Artist relation
 *
 * @method     ChildUserQuery joinWithArtist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Artist relation
 *
 * @method     ChildUserQuery leftJoinWithArtist() Adds a LEFT JOIN clause and with to the query using the Artist relation
 * @method     ChildUserQuery rightJoinWithArtist() Adds a RIGHT JOIN clause and with to the query using the Artist relation
 * @method     ChildUserQuery innerJoinWithArtist() Adds a INNER JOIN clause and with to the query using the Artist relation
 *
 * @method     ChildUserQuery leftJoinAlbum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Album relation
 * @method     ChildUserQuery rightJoinAlbum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Album relation
 * @method     ChildUserQuery innerJoinAlbum($relationAlias = null) Adds a INNER JOIN clause to the query using the Album relation
 *
 * @method     ChildUserQuery joinWithAlbum($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Album relation
 *
 * @method     ChildUserQuery leftJoinWithAlbum() Adds a LEFT JOIN clause and with to the query using the Album relation
 * @method     ChildUserQuery rightJoinWithAlbum() Adds a RIGHT JOIN clause and with to the query using the Album relation
 * @method     ChildUserQuery innerJoinWithAlbum() Adds a INNER JOIN clause and with to the query using the Album relation
 *
 * @method     ChildUserQuery leftJoinTopic($relationAlias = null) Adds a LEFT JOIN clause to the query using the Topic relation
 * @method     ChildUserQuery rightJoinTopic($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Topic relation
 * @method     ChildUserQuery innerJoinTopic($relationAlias = null) Adds a INNER JOIN clause to the query using the Topic relation
 *
 * @method     ChildUserQuery joinWithTopic($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Topic relation
 *
 * @method     ChildUserQuery leftJoinWithTopic() Adds a LEFT JOIN clause and with to the query using the Topic relation
 * @method     ChildUserQuery rightJoinWithTopic() Adds a RIGHT JOIN clause and with to the query using the Topic relation
 * @method     ChildUserQuery innerJoinWithTopic() Adds a INNER JOIN clause and with to the query using the Topic relation
 *
 * @method     ChildUserQuery leftJoinPost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Post relation
 * @method     ChildUserQuery rightJoinPost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Post relation
 * @method     ChildUserQuery innerJoinPost($relationAlias = null) Adds a INNER JOIN clause to the query using the Post relation
 *
 * @method     ChildUserQuery joinWithPost($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Post relation
 *
 * @method     ChildUserQuery leftJoinWithPost() Adds a LEFT JOIN clause and with to the query using the Post relation
 * @method     ChildUserQuery rightJoinWithPost() Adds a RIGHT JOIN clause and with to the query using the Post relation
 * @method     ChildUserQuery innerJoinWithPost() Adds a INNER JOIN clause and with to the query using the Post relation
 *
 * @method     \Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery|\Tekstove\ApiBundle\Model\LyricQuery|\Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery|\Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery|\Tekstove\ApiBundle\Model\ArtistQuery|\Tekstove\ApiBundle\Model\AlbumQuery|\Tekstove\ApiBundle\Model\Forum\TopicQuery|\Tekstove\ApiBundle\Model\Forum\PostQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByapiKey(string $api_key) Return the first ChildUser filtered by the api_key column
 * @method     ChildUser findOneByMail(string $mail) Return the first ChildUser filtered by the mail column
 * @method     ChildUser findOneByAvatar(string $avatar) Return the first ChildUser filtered by the avatar column
 * @method     ChildUser findOneByAbout(string $about) Return the first ChildUser filtered by the about column
 * @method     ChildUser findOneByAutoplay(int $autoplay) Return the first ChildUser filtered by the autoplay column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByapiKey(string $api_key) Return the first ChildUser filtered by the api_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByMail(string $mail) Return the first ChildUser filtered by the mail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAvatar(string $avatar) Return the first ChildUser filtered by the avatar column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAbout(string $about) Return the first ChildUser filtered by the about column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAutoplay(int $autoplay) Return the first ChildUser filtered by the autoplay column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByapiKey(string $api_key) Return ChildUser objects filtered by the api_key column
 * @method     ChildUser[]|ObjectCollection findByMail(string $mail) Return ChildUser objects filtered by the mail column
 * @method     ChildUser[]|ObjectCollection findByAvatar(string $avatar) Return ChildUser objects filtered by the avatar column
 * @method     ChildUser[]|ObjectCollection findByAbout(string $about) Return ChildUser objects filtered by the about column
 * @method     ChildUser[]|ObjectCollection findByAutoplay(int $autoplay) Return ChildUser objects filtered by the autoplay column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `username`, `password`, `api_key`, `mail`, `avatar`, `about`, `autoplay` FROM `user` WHERE `id` = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the api_key column
     *
     * Example usage:
     * <code>
     * $query->filterByapiKey('fooValue');   // WHERE api_key = 'fooValue'
     * $query->filterByapiKey('%fooValue%', Criteria::LIKE); // WHERE api_key LIKE '%fooValue%'
     * </code>
     *
     * @param     string $apiKey The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByapiKey($apiKey = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($apiKey)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_API_KEY, $apiKey, $comparison);
    }

    /**
     * Filter the query on the mail column
     *
     * Example usage:
     * <code>
     * $query->filterByMail('fooValue');   // WHERE mail = 'fooValue'
     * $query->filterByMail('%fooValue%', Criteria::LIKE); // WHERE mail LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mail The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByMail($mail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mail)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_MAIL, $mail, $comparison);
    }

    /**
     * Filter the query on the avatar column
     *
     * Example usage:
     * <code>
     * $query->filterByAvatar('fooValue');   // WHERE avatar = 'fooValue'
     * $query->filterByAvatar('%fooValue%', Criteria::LIKE); // WHERE avatar LIKE '%fooValue%'
     * </code>
     *
     * @param     string $avatar The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAvatar($avatar = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($avatar)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_AVATAR, $avatar, $comparison);
    }

    /**
     * Filter the query on the about column
     *
     * Example usage:
     * <code>
     * $query->filterByAbout('fooValue');   // WHERE about = 'fooValue'
     * $query->filterByAbout('%fooValue%', Criteria::LIKE); // WHERE about LIKE '%fooValue%'
     * </code>
     *
     * @param     string $about The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAbout($about = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($about)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ABOUT, $about, $comparison);
    }

    /**
     * Filter the query on the autoplay column
     *
     * Example usage:
     * <code>
     * $query->filterByAutoplay(1234); // WHERE autoplay = 1234
     * $query->filterByAutoplay(array(12, 34)); // WHERE autoplay IN (12, 34)
     * $query->filterByAutoplay(array('min' => 12)); // WHERE autoplay > 12
     * </code>
     *
     * @param     mixed $autoplay The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAutoplay($autoplay = null, $comparison = null)
    {
        if (is_array($autoplay)) {
            $useMinMax = false;
            if (isset($autoplay['min'])) {
                $this->addUsingAlias(UserTableMap::COL_AUTOPLAY, $autoplay['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($autoplay['max'])) {
                $this->addUsingAlias(UserTableMap::COL_AUTOPLAY, $autoplay['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_AUTOPLAY, $autoplay, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser object
     *
     * @param \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser|ObjectCollection $permissionGroupUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPermissionGroupUser($permissionGroupUser, $comparison = null)
    {
        if ($permissionGroupUser instanceof \Tekstove\ApiBundle\Model\Acl\PermissionGroupUser) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $permissionGroupUser->getUserId(), $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric|ObjectCollection $lyric the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLyric($lyric, $comparison = null)
    {
        if ($lyric instanceof \Tekstove\ApiBundle\Model\Lyric) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $lyric->getsendBy(), $comparison);
        } elseif ($lyric instanceof ObjectCollection) {
            return $this
                ->useLyricQuery()
                ->filterByPrimaryKeys($lyric->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric\LyricTranslation object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric\LyricTranslation|ObjectCollection $lyricTranslation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLyricTranslation($lyricTranslation, $comparison = null)
    {
        if ($lyricTranslation instanceof \Tekstove\ApiBundle\Model\Lyric\LyricTranslation) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $lyricTranslation->getUserId(), $comparison);
        } elseif ($lyricTranslation instanceof ObjectCollection) {
            return $this
                ->useLyricTranslationQuery()
                ->filterByPrimaryKeys($lyricTranslation->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLyricTranslation() only accepts arguments of type \Tekstove\ApiBundle\Model\Lyric\LyricTranslation or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LyricTranslation relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinLyricTranslation($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LyricTranslation');

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
            $this->addJoinObject($join, 'LyricTranslation');
        }

        return $this;
    }

    /**
     * Use the LyricTranslation relation LyricTranslation object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery A secondary query class using the current class as primary query
     */
    public function useLyricTranslationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLyricTranslation($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LyricTranslation', '\Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric\LyricVote object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric\LyricVote|ObjectCollection $lyricVote the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByLyricVote($lyricVote, $comparison = null)
    {
        if ($lyricVote instanceof \Tekstove\ApiBundle\Model\Lyric\LyricVote) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $lyricVote->getUserId(), $comparison);
        } elseif ($lyricVote instanceof ObjectCollection) {
            return $this
                ->useLyricVoteQuery()
                ->filterByPrimaryKeys($lyricVote->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByLyricVote() only accepts arguments of type \Tekstove\ApiBundle\Model\Lyric\LyricVote or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the LyricVote relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinLyricVote($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('LyricVote');

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
            $this->addJoinObject($join, 'LyricVote');
        }

        return $this;
    }

    /**
     * Use the LyricVote relation LyricVote object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery A secondary query class using the current class as primary query
     */
    public function useLyricVoteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinLyricVote($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'LyricVote', '\Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Artist object
     *
     * @param \Tekstove\ApiBundle\Model\Artist|ObjectCollection $artist the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByArtist($artist, $comparison = null)
    {
        if ($artist instanceof \Tekstove\ApiBundle\Model\Artist) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $artist->getUserId(), $comparison);
        } elseif ($artist instanceof ObjectCollection) {
            return $this
                ->useArtistQuery()
                ->filterByPrimaryKeys($artist->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByArtist() only accepts arguments of type \Tekstove\ApiBundle\Model\Artist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Artist relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinArtist($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Artist');

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
            $this->addJoinObject($join, 'Artist');
        }

        return $this;
    }

    /**
     * Use the Artist relation Artist object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\ArtistQuery A secondary query class using the current class as primary query
     */
    public function useArtistQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArtist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Artist', '\Tekstove\ApiBundle\Model\ArtistQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Album object
     *
     * @param \Tekstove\ApiBundle\Model\Album|ObjectCollection $album the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByAlbum($album, $comparison = null)
    {
        if ($album instanceof \Tekstove\ApiBundle\Model\Album) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $album->getsendBy(), $comparison);
        } elseif ($album instanceof ObjectCollection) {
            return $this
                ->useAlbumQuery()
                ->filterByPrimaryKeys($album->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAlbum() only accepts arguments of type \Tekstove\ApiBundle\Model\Album or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Album relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinAlbum($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Album');

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
            $this->addJoinObject($join, 'Album');
        }

        return $this;
    }

    /**
     * Use the Album relation Album object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\AlbumQuery A secondary query class using the current class as primary query
     */
    public function useAlbumQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAlbum($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Album', '\Tekstove\ApiBundle\Model\AlbumQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Forum\Topic object
     *
     * @param \Tekstove\ApiBundle\Model\Forum\Topic|ObjectCollection $topic the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByTopic($topic, $comparison = null)
    {
        if ($topic instanceof \Tekstove\ApiBundle\Model\Forum\Topic) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $topic->getUserId(), $comparison);
        } elseif ($topic instanceof ObjectCollection) {
            return $this
                ->useTopicQuery()
                ->filterByPrimaryKeys($topic->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTopic() only accepts arguments of type \Tekstove\ApiBundle\Model\Forum\Topic or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Topic relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinTopic($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Topic');

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
            $this->addJoinObject($join, 'Topic');
        }

        return $this;
    }

    /**
     * Use the Topic relation Topic object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Forum\TopicQuery A secondary query class using the current class as primary query
     */
    public function useTopicQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTopic($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Topic', '\Tekstove\ApiBundle\Model\Forum\TopicQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Forum\Post object
     *
     * @param \Tekstove\ApiBundle\Model\Forum\Post|ObjectCollection $post the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPost($post, $comparison = null)
    {
        if ($post instanceof \Tekstove\ApiBundle\Model\Forum\Post) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $post->getUserId(), $comparison);
        } elseif ($post instanceof ObjectCollection) {
            return $this
                ->usePostQuery()
                ->filterByPrimaryKeys($post->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPost() only accepts arguments of type \Tekstove\ApiBundle\Model\Forum\Post or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Post relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPost($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Post');

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
            $this->addJoinObject($join, 'Post');
        }

        return $this;
    }

    /**
     * Use the Post relation Post object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Forum\PostQuery A secondary query class using the current class as primary query
     */
    public function usePostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Post', '\Tekstove\ApiBundle\Model\Forum\PostQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UserQuery
