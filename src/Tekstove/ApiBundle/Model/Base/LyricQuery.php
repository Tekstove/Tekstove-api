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
use Tekstove\ApiBundle\Model\Lyric as ChildLyric;
use Tekstove\ApiBundle\Model\LyricQuery as ChildLyricQuery;
use Tekstove\ApiBundle\Model\Artist\ArtistLyric;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\LyricVote;
use Tekstove\ApiBundle\Model\Map\LyricTableMap;

/**
 * Base class that represents a query for the 'lyric' table.
 *
 *
 *
 * @method     ChildLyricQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildLyricQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildLyricQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildLyricQuery orderBytextBg($order = Criteria::ASC) Order by the text_bg column
 * @method     ChildLyricQuery orderBytextBgAdded($order = Criteria::ASC) Order by the text_bg_added column
 * @method     ChildLyricQuery orderByextraInfo($order = Criteria::ASC) Order by the extra_info column
 * @method     ChildLyricQuery orderBysendBy($order = Criteria::ASC) Order by the send_by column
 * @method     ChildLyricQuery orderBycacheTitleShort($order = Criteria::ASC) Order by the cache_title_short column
 * @method     ChildLyricQuery orderBycacheCensor($order = Criteria::ASC) Order by the cache_censor column
 * @method     ChildLyricQuery orderBycacheCensorUpdated($order = Criteria::ASC) Order by the cache_censor_updated column
 * @method     ChildLyricQuery orderByViews($order = Criteria::ASC) Order by the views column
 * @method     ChildLyricQuery orderByPopularity($order = Criteria::ASC) Order by the popularity column
 * @method     ChildLyricQuery orderByvotesCount($order = Criteria::ASC) Order by the votes_count column
 * @method     ChildLyricQuery orderByvideoYoutube($order = Criteria::ASC) Order by the video_youtube column
 * @method     ChildLyricQuery orderByvideoVbox7($order = Criteria::ASC) Order by the video_vbox7 column
 * @method     ChildLyricQuery orderByvideoMetacafe($order = Criteria::ASC) Order by the video_metacafe column
 * @method     ChildLyricQuery orderBydownload($order = Criteria::ASC) Order by the download column
 *
 * @method     ChildLyricQuery groupById() Group by the id column
 * @method     ChildLyricQuery groupByTitle() Group by the title column
 * @method     ChildLyricQuery groupByText() Group by the text column
 * @method     ChildLyricQuery groupBytextBg() Group by the text_bg column
 * @method     ChildLyricQuery groupBytextBgAdded() Group by the text_bg_added column
 * @method     ChildLyricQuery groupByextraInfo() Group by the extra_info column
 * @method     ChildLyricQuery groupBysendBy() Group by the send_by column
 * @method     ChildLyricQuery groupBycacheTitleShort() Group by the cache_title_short column
 * @method     ChildLyricQuery groupBycacheCensor() Group by the cache_censor column
 * @method     ChildLyricQuery groupBycacheCensorUpdated() Group by the cache_censor_updated column
 * @method     ChildLyricQuery groupByViews() Group by the views column
 * @method     ChildLyricQuery groupByPopularity() Group by the popularity column
 * @method     ChildLyricQuery groupByvotesCount() Group by the votes_count column
 * @method     ChildLyricQuery groupByvideoYoutube() Group by the video_youtube column
 * @method     ChildLyricQuery groupByvideoVbox7() Group by the video_vbox7 column
 * @method     ChildLyricQuery groupByvideoMetacafe() Group by the video_metacafe column
 * @method     ChildLyricQuery groupBydownload() Group by the download column
 *
 * @method     ChildLyricQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildLyricQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildLyricQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildLyricQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildLyricQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildLyricQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildLyricQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildLyricQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildLyricQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildLyricQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildLyricQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildLyricQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildLyricQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildLyricQuery leftJoinArtistLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the ArtistLyric relation
 * @method     ChildLyricQuery rightJoinArtistLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ArtistLyric relation
 * @method     ChildLyricQuery innerJoinArtistLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the ArtistLyric relation
 *
 * @method     ChildLyricQuery joinWithArtistLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ArtistLyric relation
 *
 * @method     ChildLyricQuery leftJoinWithArtistLyric() Adds a LEFT JOIN clause and with to the query using the ArtistLyric relation
 * @method     ChildLyricQuery rightJoinWithArtistLyric() Adds a RIGHT JOIN clause and with to the query using the ArtistLyric relation
 * @method     ChildLyricQuery innerJoinWithArtistLyric() Adds a INNER JOIN clause and with to the query using the ArtistLyric relation
 *
 * @method     ChildLyricQuery leftJoinLyricLanguage($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricLanguage relation
 * @method     ChildLyricQuery rightJoinLyricLanguage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricLanguage relation
 * @method     ChildLyricQuery innerJoinLyricLanguage($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricLanguage relation
 *
 * @method     ChildLyricQuery joinWithLyricLanguage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricLanguage relation
 *
 * @method     ChildLyricQuery leftJoinWithLyricLanguage() Adds a LEFT JOIN clause and with to the query using the LyricLanguage relation
 * @method     ChildLyricQuery rightJoinWithLyricLanguage() Adds a RIGHT JOIN clause and with to the query using the LyricLanguage relation
 * @method     ChildLyricQuery innerJoinWithLyricLanguage() Adds a INNER JOIN clause and with to the query using the LyricLanguage relation
 *
 * @method     ChildLyricQuery leftJoinLyricTranslation($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricTranslation relation
 * @method     ChildLyricQuery rightJoinLyricTranslation($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricTranslation relation
 * @method     ChildLyricQuery innerJoinLyricTranslation($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricTranslation relation
 *
 * @method     ChildLyricQuery joinWithLyricTranslation($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricTranslation relation
 *
 * @method     ChildLyricQuery leftJoinWithLyricTranslation() Adds a LEFT JOIN clause and with to the query using the LyricTranslation relation
 * @method     ChildLyricQuery rightJoinWithLyricTranslation() Adds a RIGHT JOIN clause and with to the query using the LyricTranslation relation
 * @method     ChildLyricQuery innerJoinWithLyricTranslation() Adds a INNER JOIN clause and with to the query using the LyricTranslation relation
 *
 * @method     ChildLyricQuery leftJoinLyricVote($relationAlias = null) Adds a LEFT JOIN clause to the query using the LyricVote relation
 * @method     ChildLyricQuery rightJoinLyricVote($relationAlias = null) Adds a RIGHT JOIN clause to the query using the LyricVote relation
 * @method     ChildLyricQuery innerJoinLyricVote($relationAlias = null) Adds a INNER JOIN clause to the query using the LyricVote relation
 *
 * @method     ChildLyricQuery joinWithLyricVote($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the LyricVote relation
 *
 * @method     ChildLyricQuery leftJoinWithLyricVote() Adds a LEFT JOIN clause and with to the query using the LyricVote relation
 * @method     ChildLyricQuery rightJoinWithLyricVote() Adds a RIGHT JOIN clause and with to the query using the LyricVote relation
 * @method     ChildLyricQuery innerJoinWithLyricVote() Adds a INNER JOIN clause and with to the query using the LyricVote relation
 *
 * @method     ChildLyricQuery leftJoinAlbumLyric($relationAlias = null) Adds a LEFT JOIN clause to the query using the AlbumLyric relation
 * @method     ChildLyricQuery rightJoinAlbumLyric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AlbumLyric relation
 * @method     ChildLyricQuery innerJoinAlbumLyric($relationAlias = null) Adds a INNER JOIN clause to the query using the AlbumLyric relation
 *
 * @method     ChildLyricQuery joinWithAlbumLyric($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the AlbumLyric relation
 *
 * @method     ChildLyricQuery leftJoinWithAlbumLyric() Adds a LEFT JOIN clause and with to the query using the AlbumLyric relation
 * @method     ChildLyricQuery rightJoinWithAlbumLyric() Adds a RIGHT JOIN clause and with to the query using the AlbumLyric relation
 * @method     ChildLyricQuery innerJoinWithAlbumLyric() Adds a INNER JOIN clause and with to the query using the AlbumLyric relation
 *
 * @method     \Tekstove\ApiBundle\Model\UserQuery|\Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery|\Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery|\Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery|\Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery|\Tekstove\ApiBundle\Model\AlbumLyricQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildLyric findOne(ConnectionInterface $con = null) Return the first ChildLyric matching the query
 * @method     ChildLyric findOneOrCreate(ConnectionInterface $con = null) Return the first ChildLyric matching the query, or a new ChildLyric object populated from the query conditions when no match is found
 *
 * @method     ChildLyric findOneById(int $id) Return the first ChildLyric filtered by the id column
 * @method     ChildLyric findOneByTitle(string $title) Return the first ChildLyric filtered by the title column
 * @method     ChildLyric findOneByText(string $text) Return the first ChildLyric filtered by the text column
 * @method     ChildLyric findOneBytextBg(string $text_bg) Return the first ChildLyric filtered by the text_bg column
 * @method     ChildLyric findOneBytextBgAdded(string $text_bg_added) Return the first ChildLyric filtered by the text_bg_added column
 * @method     ChildLyric findOneByextraInfo(string $extra_info) Return the first ChildLyric filtered by the extra_info column
 * @method     ChildLyric findOneBysendBy(int $send_by) Return the first ChildLyric filtered by the send_by column
 * @method     ChildLyric findOneBycacheTitleShort(string $cache_title_short) Return the first ChildLyric filtered by the cache_title_short column
 * @method     ChildLyric findOneBycacheCensor(boolean $cache_censor) Return the first ChildLyric filtered by the cache_censor column
 * @method     ChildLyric findOneBycacheCensorUpdated(string $cache_censor_updated) Return the first ChildLyric filtered by the cache_censor_updated column
 * @method     ChildLyric findOneByViews(int $views) Return the first ChildLyric filtered by the views column
 * @method     ChildLyric findOneByPopularity(int $popularity) Return the first ChildLyric filtered by the popularity column
 * @method     ChildLyric findOneByvotesCount(int $votes_count) Return the first ChildLyric filtered by the votes_count column
 * @method     ChildLyric findOneByvideoYoutube(string $video_youtube) Return the first ChildLyric filtered by the video_youtube column
 * @method     ChildLyric findOneByvideoVbox7(string $video_vbox7) Return the first ChildLyric filtered by the video_vbox7 column
 * @method     ChildLyric findOneByvideoMetacafe(string $video_metacafe) Return the first ChildLyric filtered by the video_metacafe column
 * @method     ChildLyric findOneBydownload(string $download) Return the first ChildLyric filtered by the download column *

 * @method     ChildLyric requirePk($key, ConnectionInterface $con = null) Return the ChildLyric by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOne(ConnectionInterface $con = null) Return the first ChildLyric matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyric requireOneById(int $id) Return the first ChildLyric filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByTitle(string $title) Return the first ChildLyric filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByText(string $text) Return the first ChildLyric filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBytextBg(string $text_bg) Return the first ChildLyric filtered by the text_bg column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBytextBgAdded(string $text_bg_added) Return the first ChildLyric filtered by the text_bg_added column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByextraInfo(string $extra_info) Return the first ChildLyric filtered by the extra_info column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBysendBy(int $send_by) Return the first ChildLyric filtered by the send_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBycacheTitleShort(string $cache_title_short) Return the first ChildLyric filtered by the cache_title_short column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBycacheCensor(boolean $cache_censor) Return the first ChildLyric filtered by the cache_censor column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBycacheCensorUpdated(string $cache_censor_updated) Return the first ChildLyric filtered by the cache_censor_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByViews(int $views) Return the first ChildLyric filtered by the views column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByPopularity(int $popularity) Return the first ChildLyric filtered by the popularity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByvotesCount(int $votes_count) Return the first ChildLyric filtered by the votes_count column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByvideoYoutube(string $video_youtube) Return the first ChildLyric filtered by the video_youtube column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByvideoVbox7(string $video_vbox7) Return the first ChildLyric filtered by the video_vbox7 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneByvideoMetacafe(string $video_metacafe) Return the first ChildLyric filtered by the video_metacafe column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildLyric requireOneBydownload(string $download) Return the first ChildLyric filtered by the download column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildLyric[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildLyric objects based on current ModelCriteria
 * @method     ChildLyric[]|ObjectCollection findById(int $id) Return ChildLyric objects filtered by the id column
 * @method     ChildLyric[]|ObjectCollection findByTitle(string $title) Return ChildLyric objects filtered by the title column
 * @method     ChildLyric[]|ObjectCollection findByText(string $text) Return ChildLyric objects filtered by the text column
 * @method     ChildLyric[]|ObjectCollection findBytextBg(string $text_bg) Return ChildLyric objects filtered by the text_bg column
 * @method     ChildLyric[]|ObjectCollection findBytextBgAdded(string $text_bg_added) Return ChildLyric objects filtered by the text_bg_added column
 * @method     ChildLyric[]|ObjectCollection findByextraInfo(string $extra_info) Return ChildLyric objects filtered by the extra_info column
 * @method     ChildLyric[]|ObjectCollection findBysendBy(int $send_by) Return ChildLyric objects filtered by the send_by column
 * @method     ChildLyric[]|ObjectCollection findBycacheTitleShort(string $cache_title_short) Return ChildLyric objects filtered by the cache_title_short column
 * @method     ChildLyric[]|ObjectCollection findBycacheCensor(boolean $cache_censor) Return ChildLyric objects filtered by the cache_censor column
 * @method     ChildLyric[]|ObjectCollection findBycacheCensorUpdated(string $cache_censor_updated) Return ChildLyric objects filtered by the cache_censor_updated column
 * @method     ChildLyric[]|ObjectCollection findByViews(int $views) Return ChildLyric objects filtered by the views column
 * @method     ChildLyric[]|ObjectCollection findByPopularity(int $popularity) Return ChildLyric objects filtered by the popularity column
 * @method     ChildLyric[]|ObjectCollection findByvotesCount(int $votes_count) Return ChildLyric objects filtered by the votes_count column
 * @method     ChildLyric[]|ObjectCollection findByvideoYoutube(string $video_youtube) Return ChildLyric objects filtered by the video_youtube column
 * @method     ChildLyric[]|ObjectCollection findByvideoVbox7(string $video_vbox7) Return ChildLyric objects filtered by the video_vbox7 column
 * @method     ChildLyric[]|ObjectCollection findByvideoMetacafe(string $video_metacafe) Return ChildLyric objects filtered by the video_metacafe column
 * @method     ChildLyric[]|ObjectCollection findBydownload(string $download) Return ChildLyric objects filtered by the download column
 * @method     ChildLyric[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class LyricQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Tekstove\ApiBundle\Model\Base\LyricQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Tekstove\\ApiBundle\\Model\\Lyric', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildLyricQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildLyricQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildLyricQuery) {
            return $criteria;
        }
        $query = new ChildLyricQuery();
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
     * @return ChildLyric|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(LyricTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = LyricTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildLyric A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `title`, `text`, `text_bg`, `text_bg_added`, `extra_info`, `send_by`, `cache_title_short`, `cache_censor`, `cache_censor_updated`, `views`, `popularity`, `votes_count`, `video_youtube`, `video_vbox7`, `video_metacafe`, `download` FROM `lyric` WHERE `id` = :p0';
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
            /** @var ChildLyric $obj */
            $obj = new ChildLyric();
            $obj->hydrate($row);
            LyricTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildLyric|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LyricTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LyricTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the text_bg column
     *
     * Example usage:
     * <code>
     * $query->filterBytextBg('fooValue');   // WHERE text_bg = 'fooValue'
     * $query->filterBytextBg('%fooValue%', Criteria::LIKE); // WHERE text_bg LIKE '%fooValue%'
     * </code>
     *
     * @param     string $textBg The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBytextBg($textBg = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($textBg)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_TEXT_BG, $textBg, $comparison);
    }

    /**
     * Filter the query on the text_bg_added column
     *
     * Example usage:
     * <code>
     * $query->filterBytextBgAdded('2011-03-14'); // WHERE text_bg_added = '2011-03-14'
     * $query->filterBytextBgAdded('now'); // WHERE text_bg_added = '2011-03-14'
     * $query->filterBytextBgAdded(array('max' => 'yesterday')); // WHERE text_bg_added > '2011-03-13'
     * </code>
     *
     * @param     mixed $textBgAdded The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBytextBgAdded($textBgAdded = null, $comparison = null)
    {
        if (is_array($textBgAdded)) {
            $useMinMax = false;
            if (isset($textBgAdded['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_TEXT_BG_ADDED, $textBgAdded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($textBgAdded['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_TEXT_BG_ADDED, $textBgAdded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_TEXT_BG_ADDED, $textBgAdded, $comparison);
    }

    /**
     * Filter the query on the extra_info column
     *
     * Example usage:
     * <code>
     * $query->filterByextraInfo('fooValue');   // WHERE extra_info = 'fooValue'
     * $query->filterByextraInfo('%fooValue%', Criteria::LIKE); // WHERE extra_info LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extraInfo The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByextraInfo($extraInfo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extraInfo)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_EXTRA_INFO, $extraInfo, $comparison);
    }

    /**
     * Filter the query on the send_by column
     *
     * Example usage:
     * <code>
     * $query->filterBysendBy(1234); // WHERE send_by = 1234
     * $query->filterBysendBy(array(12, 34)); // WHERE send_by IN (12, 34)
     * $query->filterBysendBy(array('min' => 12)); // WHERE send_by > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $sendBy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBysendBy($sendBy = null, $comparison = null)
    {
        if (is_array($sendBy)) {
            $useMinMax = false;
            if (isset($sendBy['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_SEND_BY, $sendBy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sendBy['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_SEND_BY, $sendBy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_SEND_BY, $sendBy, $comparison);
    }

    /**
     * Filter the query on the cache_title_short column
     *
     * Example usage:
     * <code>
     * $query->filterBycacheTitleShort('fooValue');   // WHERE cache_title_short = 'fooValue'
     * $query->filterBycacheTitleShort('%fooValue%', Criteria::LIKE); // WHERE cache_title_short LIKE '%fooValue%'
     * </code>
     *
     * @param     string $cacheTitleShort The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBycacheTitleShort($cacheTitleShort = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($cacheTitleShort)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_CACHE_TITLE_SHORT, $cacheTitleShort, $comparison);
    }

    /**
     * Filter the query on the cache_censor column
     *
     * Example usage:
     * <code>
     * $query->filterBycacheCensor(true); // WHERE cache_censor = true
     * $query->filterBycacheCensor('yes'); // WHERE cache_censor = true
     * </code>
     *
     * @param     boolean|string $cacheCensor The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBycacheCensor($cacheCensor = null, $comparison = null)
    {
        if (is_string($cacheCensor)) {
            $cacheCensor = in_array(strtolower($cacheCensor), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(LyricTableMap::COL_CACHE_CENSOR, $cacheCensor, $comparison);
    }

    /**
     * Filter the query on the cache_censor_updated column
     *
     * Example usage:
     * <code>
     * $query->filterBycacheCensorUpdated('2011-03-14'); // WHERE cache_censor_updated = '2011-03-14'
     * $query->filterBycacheCensorUpdated('now'); // WHERE cache_censor_updated = '2011-03-14'
     * $query->filterBycacheCensorUpdated(array('max' => 'yesterday')); // WHERE cache_censor_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $cacheCensorUpdated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBycacheCensorUpdated($cacheCensorUpdated = null, $comparison = null)
    {
        if (is_array($cacheCensorUpdated)) {
            $useMinMax = false;
            if (isset($cacheCensorUpdated['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_CACHE_CENSOR_UPDATED, $cacheCensorUpdated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cacheCensorUpdated['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_CACHE_CENSOR_UPDATED, $cacheCensorUpdated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_CACHE_CENSOR_UPDATED, $cacheCensorUpdated, $comparison);
    }

    /**
     * Filter the query on the views column
     *
     * Example usage:
     * <code>
     * $query->filterByViews(1234); // WHERE views = 1234
     * $query->filterByViews(array(12, 34)); // WHERE views IN (12, 34)
     * $query->filterByViews(array('min' => 12)); // WHERE views > 12
     * </code>
     *
     * @param     mixed $views The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByViews($views = null, $comparison = null)
    {
        if (is_array($views)) {
            $useMinMax = false;
            if (isset($views['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_VIEWS, $views['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($views['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_VIEWS, $views['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_VIEWS, $views, $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByPopularity($popularity = null, $comparison = null)
    {
        if (is_array($popularity)) {
            $useMinMax = false;
            if (isset($popularity['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_POPULARITY, $popularity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($popularity['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_POPULARITY, $popularity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_POPULARITY, $popularity, $comparison);
    }

    /**
     * Filter the query on the votes_count column
     *
     * Example usage:
     * <code>
     * $query->filterByvotesCount(1234); // WHERE votes_count = 1234
     * $query->filterByvotesCount(array(12, 34)); // WHERE votes_count IN (12, 34)
     * $query->filterByvotesCount(array('min' => 12)); // WHERE votes_count > 12
     * </code>
     *
     * @param     mixed $votesCount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByvotesCount($votesCount = null, $comparison = null)
    {
        if (is_array($votesCount)) {
            $useMinMax = false;
            if (isset($votesCount['min'])) {
                $this->addUsingAlias(LyricTableMap::COL_VOTES_COUNT, $votesCount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($votesCount['max'])) {
                $this->addUsingAlias(LyricTableMap::COL_VOTES_COUNT, $votesCount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_VOTES_COUNT, $votesCount, $comparison);
    }

    /**
     * Filter the query on the video_youtube column
     *
     * Example usage:
     * <code>
     * $query->filterByvideoYoutube('fooValue');   // WHERE video_youtube = 'fooValue'
     * $query->filterByvideoYoutube('%fooValue%', Criteria::LIKE); // WHERE video_youtube LIKE '%fooValue%'
     * </code>
     *
     * @param     string $videoYoutube The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByvideoYoutube($videoYoutube = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($videoYoutube)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_VIDEO_YOUTUBE, $videoYoutube, $comparison);
    }

    /**
     * Filter the query on the video_vbox7 column
     *
     * Example usage:
     * <code>
     * $query->filterByvideoVbox7('fooValue');   // WHERE video_vbox7 = 'fooValue'
     * $query->filterByvideoVbox7('%fooValue%', Criteria::LIKE); // WHERE video_vbox7 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $videoVbox7 The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByvideoVbox7($videoVbox7 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($videoVbox7)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_VIDEO_VBOX7, $videoVbox7, $comparison);
    }

    /**
     * Filter the query on the video_metacafe column
     *
     * Example usage:
     * <code>
     * $query->filterByvideoMetacafe('fooValue');   // WHERE video_metacafe = 'fooValue'
     * $query->filterByvideoMetacafe('%fooValue%', Criteria::LIKE); // WHERE video_metacafe LIKE '%fooValue%'
     * </code>
     *
     * @param     string $videoMetacafe The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterByvideoMetacafe($videoMetacafe = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($videoMetacafe)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_VIDEO_METACAFE, $videoMetacafe, $comparison);
    }

    /**
     * Filter the query on the download column
     *
     * Example usage:
     * <code>
     * $query->filterBydownload('fooValue');   // WHERE download = 'fooValue'
     * $query->filterBydownload('%fooValue%', Criteria::LIKE); // WHERE download LIKE '%fooValue%'
     * </code>
     *
     * @param     string $download The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function filterBydownload($download = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($download)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LyricTableMap::COL_DOWNLOAD, $download, $comparison);
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\User object
     *
     * @param \Tekstove\ApiBundle\Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Tekstove\ApiBundle\Model\User) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_SEND_BY, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(LyricTableMap::COL_SEND_BY, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\Artist\ArtistLyric object
     *
     * @param \Tekstove\ApiBundle\Model\Artist\ArtistLyric|ObjectCollection $artistLyric the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByArtistLyric($artistLyric, $comparison = null)
    {
        if ($artistLyric instanceof \Tekstove\ApiBundle\Model\Artist\ArtistLyric) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_ID, $artistLyric->getLyricId(), $comparison);
        } elseif ($artistLyric instanceof ObjectCollection) {
            return $this
                ->useArtistLyricQuery()
                ->filterByPrimaryKeys($artistLyric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByArtistLyric() only accepts arguments of type \Tekstove\ApiBundle\Model\Artist\ArtistLyric or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ArtistLyric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function joinArtistLyric($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ArtistLyric');

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
            $this->addJoinObject($join, 'ArtistLyric');
        }

        return $this;
    }

    /**
     * Use the ArtistLyric relation ArtistLyric object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery A secondary query class using the current class as primary query
     */
    public function useArtistLyricQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArtistLyric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ArtistLyric', '\Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery');
    }

    /**
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric\LyricLanguage object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric\LyricLanguage|ObjectCollection $lyricLanguage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByLyricLanguage($lyricLanguage, $comparison = null)
    {
        if ($lyricLanguage instanceof \Tekstove\ApiBundle\Model\Lyric\LyricLanguage) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_ID, $lyricLanguage->getLyricId(), $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\Lyric\LyricTranslation object
     *
     * @param \Tekstove\ApiBundle\Model\Lyric\LyricTranslation|ObjectCollection $lyricTranslation the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByLyricTranslation($lyricTranslation, $comparison = null)
    {
        if ($lyricTranslation instanceof \Tekstove\ApiBundle\Model\Lyric\LyricTranslation) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_ID, $lyricTranslation->getLyricId(), $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
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
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByLyricVote($lyricVote, $comparison = null)
    {
        if ($lyricVote instanceof \Tekstove\ApiBundle\Model\Lyric\LyricVote) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_ID, $lyricVote->getLyricId(), $comparison);
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
     * @return $this|ChildLyricQuery The current query, for fluid interface
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
     * Filter the query by a related \Tekstove\ApiBundle\Model\AlbumLyric object
     *
     * @param \Tekstove\ApiBundle\Model\AlbumLyric|ObjectCollection $albumLyric the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByAlbumLyric($albumLyric, $comparison = null)
    {
        if ($albumLyric instanceof \Tekstove\ApiBundle\Model\AlbumLyric) {
            return $this
                ->addUsingAlias(LyricTableMap::COL_ID, $albumLyric->getLyricId(), $comparison);
        } elseif ($albumLyric instanceof ObjectCollection) {
            return $this
                ->useAlbumLyricQuery()
                ->filterByPrimaryKeys($albumLyric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAlbumLyric() only accepts arguments of type \Tekstove\ApiBundle\Model\AlbumLyric or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AlbumLyric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function joinAlbumLyric($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AlbumLyric');

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
            $this->addJoinObject($join, 'AlbumLyric');
        }

        return $this;
    }

    /**
     * Use the AlbumLyric relation AlbumLyric object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Tekstove\ApiBundle\Model\AlbumLyricQuery A secondary query class using the current class as primary query
     */
    public function useAlbumLyricQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAlbumLyric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AlbumLyric', '\Tekstove\ApiBundle\Model\AlbumLyricQuery');
    }

    /**
     * Filter the query by a related Artist object
     * using the artist_lyric table as cross reference
     *
     * @param Artist $artist the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByArtist($artist, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useArtistLyricQuery()
            ->filterByArtist($artist, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Language object
     * using the lyric_language table as cross reference
     *
     * @param Language $language the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildLyricQuery The current query, for fluid interface
     */
    public function filterByLanguage($language, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useLyricLanguageQuery()
            ->filterByLanguage($language, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildLyric $lyric Object to remove from the list of results
     *
     * @return $this|ChildLyricQuery The current query, for fluid interface
     */
    public function prune($lyric = null)
    {
        if ($lyric) {
            $this->addUsingAlias(LyricTableMap::COL_ID, $lyric->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lyric table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            LyricTableMap::clearInstancePool();
            LyricTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(LyricTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            LyricTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            LyricTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // LyricQuery
