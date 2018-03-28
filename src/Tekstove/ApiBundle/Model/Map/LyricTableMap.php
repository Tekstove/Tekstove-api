<?php

namespace Tekstove\ApiBundle\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use Tekstove\ApiBundle\Model\Lyric;
use Tekstove\ApiBundle\Model\LyricQuery;


/**
 * This class defines the structure of the 'lyric' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class LyricTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Tekstove.ApiBundle.Model.Map.LyricTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'lyric';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Tekstove\\ApiBundle\\Model\\Lyric';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'src.Tekstove.ApiBundle.Model.Lyric';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 18;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 18;

    /**
     * the column name for the id field
     */
    const COL_ID = 'lyric.id';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'lyric.title';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'lyric.text';

    /**
     * the column name for the text_bg field
     */
    const COL_TEXT_BG = 'lyric.text_bg';

    /**
     * the column name for the text_bg_added field
     */
    const COL_TEXT_BG_ADDED = 'lyric.text_bg_added';

    /**
     * the column name for the extra_info field
     */
    const COL_EXTRA_INFO = 'lyric.extra_info';

    /**
     * the column name for the send_by field
     */
    const COL_SEND_BY = 'lyric.send_by';

    /**
     * the column name for the cache_title_short field
     */
    const COL_CACHE_TITLE_SHORT = 'lyric.cache_title_short';

    /**
     * the column name for the cache_censor field
     */
    const COL_CACHE_CENSOR = 'lyric.cache_censor';

    /**
     * the column name for the manual_censore field
     */
    const COL_MANUAL_CENSORE = 'lyric.manual_censore';

    /**
     * the column name for the cache_censor_updated field
     */
    const COL_CACHE_CENSOR_UPDATED = 'lyric.cache_censor_updated';

    /**
     * the column name for the views field
     */
    const COL_VIEWS = 'lyric.views';

    /**
     * the column name for the popularity field
     */
    const COL_POPULARITY = 'lyric.popularity';

    /**
     * the column name for the votes_count field
     */
    const COL_VOTES_COUNT = 'lyric.votes_count';

    /**
     * the column name for the video_youtube field
     */
    const COL_VIDEO_YOUTUBE = 'lyric.video_youtube';

    /**
     * the column name for the video_vbox7 field
     */
    const COL_VIDEO_VBOX7 = 'lyric.video_vbox7';

    /**
     * the column name for the video_metacafe field
     */
    const COL_VIDEO_METACAFE = 'lyric.video_metacafe';

    /**
     * the column name for the download field
     */
    const COL_DOWNLOAD = 'lyric.download';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Title', 'Text', 'textBg', 'textBgAdded', 'extraInfo', 'sendBy', 'cacheTitleShort', 'cacheCensor', 'manualCensore', 'cacheCensorUpdated', 'Views', 'Popularity', 'votesCount', 'videoYoutube', 'videoVbox7', 'videoMetacafe', 'download', ),
        self::TYPE_CAMELNAME     => array('id', 'title', 'text', 'textBg', 'textBgAdded', 'extraInfo', 'sendBy', 'cacheTitleShort', 'cacheCensor', 'manualCensore', 'cacheCensorUpdated', 'views', 'popularity', 'votesCount', 'videoYoutube', 'videoVbox7', 'videoMetacafe', 'download', ),
        self::TYPE_COLNAME       => array(LyricTableMap::COL_ID, LyricTableMap::COL_TITLE, LyricTableMap::COL_TEXT, LyricTableMap::COL_TEXT_BG, LyricTableMap::COL_TEXT_BG_ADDED, LyricTableMap::COL_EXTRA_INFO, LyricTableMap::COL_SEND_BY, LyricTableMap::COL_CACHE_TITLE_SHORT, LyricTableMap::COL_CACHE_CENSOR, LyricTableMap::COL_MANUAL_CENSORE, LyricTableMap::COL_CACHE_CENSOR_UPDATED, LyricTableMap::COL_VIEWS, LyricTableMap::COL_POPULARITY, LyricTableMap::COL_VOTES_COUNT, LyricTableMap::COL_VIDEO_YOUTUBE, LyricTableMap::COL_VIDEO_VBOX7, LyricTableMap::COL_VIDEO_METACAFE, LyricTableMap::COL_DOWNLOAD, ),
        self::TYPE_FIELDNAME     => array('id', 'title', 'text', 'text_bg', 'text_bg_added', 'extra_info', 'send_by', 'cache_title_short', 'cache_censor', 'manual_censore', 'cache_censor_updated', 'views', 'popularity', 'votes_count', 'video_youtube', 'video_vbox7', 'video_metacafe', 'download', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Title' => 1, 'Text' => 2, 'textBg' => 3, 'textBgAdded' => 4, 'extraInfo' => 5, 'sendBy' => 6, 'cacheTitleShort' => 7, 'cacheCensor' => 8, 'manualCensore' => 9, 'cacheCensorUpdated' => 10, 'Views' => 11, 'Popularity' => 12, 'votesCount' => 13, 'videoYoutube' => 14, 'videoVbox7' => 15, 'videoMetacafe' => 16, 'download' => 17, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'title' => 1, 'text' => 2, 'textBg' => 3, 'textBgAdded' => 4, 'extraInfo' => 5, 'sendBy' => 6, 'cacheTitleShort' => 7, 'cacheCensor' => 8, 'manualCensore' => 9, 'cacheCensorUpdated' => 10, 'views' => 11, 'popularity' => 12, 'votesCount' => 13, 'videoYoutube' => 14, 'videoVbox7' => 15, 'videoMetacafe' => 16, 'download' => 17, ),
        self::TYPE_COLNAME       => array(LyricTableMap::COL_ID => 0, LyricTableMap::COL_TITLE => 1, LyricTableMap::COL_TEXT => 2, LyricTableMap::COL_TEXT_BG => 3, LyricTableMap::COL_TEXT_BG_ADDED => 4, LyricTableMap::COL_EXTRA_INFO => 5, LyricTableMap::COL_SEND_BY => 6, LyricTableMap::COL_CACHE_TITLE_SHORT => 7, LyricTableMap::COL_CACHE_CENSOR => 8, LyricTableMap::COL_MANUAL_CENSORE => 9, LyricTableMap::COL_CACHE_CENSOR_UPDATED => 10, LyricTableMap::COL_VIEWS => 11, LyricTableMap::COL_POPULARITY => 12, LyricTableMap::COL_VOTES_COUNT => 13, LyricTableMap::COL_VIDEO_YOUTUBE => 14, LyricTableMap::COL_VIDEO_VBOX7 => 15, LyricTableMap::COL_VIDEO_METACAFE => 16, LyricTableMap::COL_DOWNLOAD => 17, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'title' => 1, 'text' => 2, 'text_bg' => 3, 'text_bg_added' => 4, 'extra_info' => 5, 'send_by' => 6, 'cache_title_short' => 7, 'cache_censor' => 8, 'manual_censore' => 9, 'cache_censor_updated' => 10, 'views' => 11, 'popularity' => 12, 'votes_count' => 13, 'video_youtube' => 14, 'video_vbox7' => 15, 'video_metacafe' => 16, 'download' => 17, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('lyric');
        $this->setPhpName('Lyric');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Tekstove\\ApiBundle\\Model\\Lyric');
        $this->setPackage('src.Tekstove.ApiBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 255, null);
        $this->addColumn('text', 'Text', 'VARCHAR', false, 255, null);
        $this->addColumn('text_bg', 'textBg', 'VARCHAR', false, 255, null);
        $this->addColumn('text_bg_added', 'textBgAdded', 'TIMESTAMP', false, null, null);
        $this->addColumn('extra_info', 'extraInfo', 'VARCHAR', false, 255, null);
        $this->addForeignKey('send_by', 'sendBy', 'INTEGER', 'user', 'id', false, null, null);
        $this->addColumn('cache_title_short', 'cacheTitleShort', 'VARCHAR', false, 255, null);
        $this->addColumn('cache_censor', 'cacheCensor', 'BOOLEAN', true, 1, null);
        $this->addColumn('manual_censore', 'manualCensore', 'BOOLEAN', true, 1, null);
        $this->addColumn('cache_censor_updated', 'cacheCensorUpdated', 'TIMESTAMP', true, null, null);
        $this->addColumn('views', 'Views', 'INTEGER', false, null, null);
        $this->addColumn('popularity', 'Popularity', 'INTEGER', false, null, null);
        $this->addColumn('votes_count', 'votesCount', 'INTEGER', false, null, null);
        $this->addColumn('video_youtube', 'videoYoutube', 'VARCHAR', false, 255, null);
        $this->addColumn('video_vbox7', 'videoVbox7', 'VARCHAR', false, 255, null);
        $this->addColumn('video_metacafe', 'videoMetacafe', 'VARCHAR', false, 255, null);
        $this->addColumn('download', 'download', 'VARCHAR', false, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\Tekstove\\ApiBundle\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':send_by',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('ArtistLyric', '\\Tekstove\\ApiBundle\\Model\\Artist\\ArtistLyric', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'ArtistLyrics', false);
        $this->addRelation('LyricLanguage', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricLanguage', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'LyricLanguages', false);
        $this->addRelation('LyricTranslation', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricTranslation', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'LyricTranslations', false);
        $this->addRelation('LyricVote', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricVote', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'LyricVotes', false);
        $this->addRelation('LyricTopPopularity', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricTopPopularity', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'LyricTopPopularities', false);
        $this->addRelation('AlbumLyric', '\\Tekstove\\ApiBundle\\Model\\AlbumLyric', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':lyric_id',
    1 => ':id',
  ),
), null, null, 'AlbumLyrics', false);
        $this->addRelation('Artist', '\\Tekstove\\ApiBundle\\Model\\Artist', RelationMap::MANY_TO_MANY, array(), null, null, 'Artists');
        $this->addRelation('Language', '\\Tekstove\\ApiBundle\\Model\\Language', RelationMap::MANY_TO_MANY, array(), null, null, 'Languages');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'validate' => array('textValidationNotEmpty' => array ('column' => 'text','validator' => 'NotBlank',), 'textValidationMinLength' => array ('column' => 'text','validator' => 'Length','options' => array ('min' => 10,),), 'titleValidationNotEmpty' => array ('column' => 'title','validator' => 'NotBlank',), 'titleValidationMaxLength' => array ('column' => 'title','validator' => 'Length','options' => array ('max' => 60,),), ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? LyricTableMap::CLASS_DEFAULT : LyricTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Lyric object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = LyricTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = LyricTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + LyricTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = LyricTableMap::OM_CLASS;
            /** @var Lyric $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            LyricTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = LyricTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = LyricTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Lyric $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                LyricTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(LyricTableMap::COL_ID);
            $criteria->addSelectColumn(LyricTableMap::COL_TITLE);
            $criteria->addSelectColumn(LyricTableMap::COL_TEXT);
            $criteria->addSelectColumn(LyricTableMap::COL_TEXT_BG);
            $criteria->addSelectColumn(LyricTableMap::COL_TEXT_BG_ADDED);
            $criteria->addSelectColumn(LyricTableMap::COL_EXTRA_INFO);
            $criteria->addSelectColumn(LyricTableMap::COL_SEND_BY);
            $criteria->addSelectColumn(LyricTableMap::COL_CACHE_TITLE_SHORT);
            $criteria->addSelectColumn(LyricTableMap::COL_CACHE_CENSOR);
            $criteria->addSelectColumn(LyricTableMap::COL_MANUAL_CENSORE);
            $criteria->addSelectColumn(LyricTableMap::COL_CACHE_CENSOR_UPDATED);
            $criteria->addSelectColumn(LyricTableMap::COL_VIEWS);
            $criteria->addSelectColumn(LyricTableMap::COL_POPULARITY);
            $criteria->addSelectColumn(LyricTableMap::COL_VOTES_COUNT);
            $criteria->addSelectColumn(LyricTableMap::COL_VIDEO_YOUTUBE);
            $criteria->addSelectColumn(LyricTableMap::COL_VIDEO_VBOX7);
            $criteria->addSelectColumn(LyricTableMap::COL_VIDEO_METACAFE);
            $criteria->addSelectColumn(LyricTableMap::COL_DOWNLOAD);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.text_bg');
            $criteria->addSelectColumn($alias . '.text_bg_added');
            $criteria->addSelectColumn($alias . '.extra_info');
            $criteria->addSelectColumn($alias . '.send_by');
            $criteria->addSelectColumn($alias . '.cache_title_short');
            $criteria->addSelectColumn($alias . '.cache_censor');
            $criteria->addSelectColumn($alias . '.manual_censore');
            $criteria->addSelectColumn($alias . '.cache_censor_updated');
            $criteria->addSelectColumn($alias . '.views');
            $criteria->addSelectColumn($alias . '.popularity');
            $criteria->addSelectColumn($alias . '.votes_count');
            $criteria->addSelectColumn($alias . '.video_youtube');
            $criteria->addSelectColumn($alias . '.video_vbox7');
            $criteria->addSelectColumn($alias . '.video_metacafe');
            $criteria->addSelectColumn($alias . '.download');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(LyricTableMap::DATABASE_NAME)->getTable(LyricTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(LyricTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(LyricTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new LyricTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Lyric or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Lyric object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Tekstove\ApiBundle\Model\Lyric) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(LyricTableMap::DATABASE_NAME);
            $criteria->add(LyricTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = LyricQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            LyricTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                LyricTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the lyric table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return LyricQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Lyric or Criteria object.
     *
     * @param mixed               $criteria Criteria or Lyric object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Lyric object
        }

        if ($criteria->containsKey(LyricTableMap::COL_ID) && $criteria->keyContainsValue(LyricTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.LyricTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = LyricQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // LyricTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
LyricTableMap::buildTableMap();
