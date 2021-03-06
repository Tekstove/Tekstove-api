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
use Tekstove\ApiBundle\Model\User;
use Tekstove\ApiBundle\Model\UserQuery;


/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class UserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Tekstove.ApiBundle.Model.Map.UserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Tekstove\\ApiBundle\\Model\\User';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'src.Tekstove.ApiBundle.Model.User';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the id field
     */
    const COL_ID = 'user.id';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'user.username';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'user.password';

    /**
     * the column name for the api_key field
     */
    const COL_API_KEY = 'user.api_key';

    /**
     * the column name for the mail field
     */
    const COL_MAIL = 'user.mail';

    /**
     * the column name for the avatar field
     */
    const COL_AVATAR = 'user.avatar';

    /**
     * the column name for the about field
     */
    const COL_ABOUT = 'user.about';

    /**
     * the column name for the autoplay field
     */
    const COL_AUTOPLAY = 'user.autoplay';

    /**
     * the column name for the terms_accepted field
     */
    const COL_TERMS_ACCEPTED = 'user.terms_accepted';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'user.status';

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
        self::TYPE_PHPNAME       => array('Id', 'Username', 'Password', 'apiKey', 'Mail', 'Avatar', 'About', 'Autoplay', 'termsAccepted', 'status', ),
        self::TYPE_CAMELNAME     => array('id', 'username', 'password', 'apiKey', 'mail', 'avatar', 'about', 'autoplay', 'termsAccepted', 'status', ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID, UserTableMap::COL_USERNAME, UserTableMap::COL_PASSWORD, UserTableMap::COL_API_KEY, UserTableMap::COL_MAIL, UserTableMap::COL_AVATAR, UserTableMap::COL_ABOUT, UserTableMap::COL_AUTOPLAY, UserTableMap::COL_TERMS_ACCEPTED, UserTableMap::COL_STATUS, ),
        self::TYPE_FIELDNAME     => array('id', 'username', 'password', 'api_key', 'mail', 'avatar', 'about', 'autoplay', 'terms_accepted', 'status', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Username' => 1, 'Password' => 2, 'apiKey' => 3, 'Mail' => 4, 'Avatar' => 5, 'About' => 6, 'Autoplay' => 7, 'termsAccepted' => 8, 'status' => 9, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'username' => 1, 'password' => 2, 'apiKey' => 3, 'mail' => 4, 'avatar' => 5, 'about' => 6, 'autoplay' => 7, 'termsAccepted' => 8, 'status' => 9, ),
        self::TYPE_COLNAME       => array(UserTableMap::COL_ID => 0, UserTableMap::COL_USERNAME => 1, UserTableMap::COL_PASSWORD => 2, UserTableMap::COL_API_KEY => 3, UserTableMap::COL_MAIL => 4, UserTableMap::COL_AVATAR => 5, UserTableMap::COL_ABOUT => 6, UserTableMap::COL_AUTOPLAY => 7, UserTableMap::COL_TERMS_ACCEPTED => 8, UserTableMap::COL_STATUS => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'username' => 1, 'password' => 2, 'api_key' => 3, 'mail' => 4, 'avatar' => 5, 'about' => 6, 'autoplay' => 7, 'terms_accepted' => 8, 'status' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $this->setName('user');
        $this->setPhpName('User');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Tekstove\\ApiBundle\\Model\\User');
        $this->setPackage('src.Tekstove.ApiBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 100, null);
        $this->getColumn('username')->setPrimaryString(true);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('api_key', 'apiKey', 'VARCHAR', true, 255, null);
        $this->addColumn('mail', 'Mail', 'VARCHAR', true, 255, null);
        $this->addColumn('avatar', 'Avatar', 'VARCHAR', false, 255, null);
        $this->addColumn('about', 'About', 'VARCHAR', false, 255, null);
        $this->addColumn('autoplay', 'Autoplay', 'SMALLINT', false, null, null);
        $this->addColumn('terms_accepted', 'termsAccepted', 'TIMESTAMP', true, null, null);
        $this->addColumn('status', 'status', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PmRelatedByUserTo', '\\Tekstove\\ApiBundle\\Model\\User\\Pm', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_to',
    1 => ':id',
  ),
), null, null, 'PmsRelatedByUserTo', false);
        $this->addRelation('PmRelatedByUserFrom', '\\Tekstove\\ApiBundle\\Model\\User\\Pm', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_from',
    1 => ':id',
  ),
), null, null, 'PmsRelatedByUserFrom', false);
        $this->addRelation('PermissionGroupUser', '\\Tekstove\\ApiBundle\\Model\\Acl\\PermissionGroupUser', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'PermissionGroupUsers', false);
        $this->addRelation('Lyric', '\\Tekstove\\ApiBundle\\Model\\Lyric', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':send_by',
    1 => ':id',
  ),
), null, null, 'Lyrics', false);
        $this->addRelation('LyricTranslation', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricTranslation', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'LyricTranslations', false);
        $this->addRelation('LyricVote', '\\Tekstove\\ApiBundle\\Model\\Lyric\\LyricVote', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'LyricVotes', false);
        $this->addRelation('Artist', '\\Tekstove\\ApiBundle\\Model\\Artist', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Artists', false);
        $this->addRelation('Album', '\\Tekstove\\ApiBundle\\Model\\Album', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Albums', false);
        $this->addRelation('Topic', '\\Tekstove\\ApiBundle\\Model\\Forum\\Topic', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Topics', false);
        $this->addRelation('Post', '\\Tekstove\\ApiBundle\\Model\\Forum\\Post', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Posts', false);
        $this->addRelation('Message', '\\Tekstove\\ApiBundle\\Model\\Chat\\Message', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Messages', false);
        $this->addRelation('Online', '\\Tekstove\\ApiBundle\\Model\\Chat\\Online', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, 'Onlines', false);
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
            'validate' => array('requiredEmail' => array ('column' => 'mail','validator' => 'NotBlank',), 'validEmail' => array ('column' => 'mail','validator' => 'Email',), 'uniqueEmail' => array ('column' => 'mail','validator' => 'Unique',), 'requiredUsername' => array ('column' => 'username','validator' => 'NotBlank',), 'uniqueUsername' => array ('column' => 'username','validator' => 'Unique',), 'requiredPassword' => array ('column' => 'password','validator' => 'NotBlank',), 'requiredApiKey' => array ('column' => 'api_key','validator' => 'NotBlank',), 'avatarMaxLength' => array ('column' => 'avatar','validator' => 'Length','options' => array ('max' => 100,),), 'aboutMaxLength' => array ('column' => 'about','validator' => 'Length','options' => array ('max' => 65000,),), 'terms_accepted' => array ('column' => 'terms_accepted','validator' => 'GreaterThanOrEqual','options' => array ('value' => '2018-05-04',),), 'terms_acceptedNotBlank' => array ('column' => 'terms_accepted','validator' => 'NotBlank',), ),
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
        return $withPrefix ? UserTableMap::CLASS_DEFAULT : UserTableMap::OM_CLASS;
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
     * @return array           (User object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = UserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + UserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = UserTableMap::OM_CLASS;
            /** @var User $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            UserTableMap::addInstanceToPool($obj, $key);
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
            $key = UserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = UserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var User $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                UserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(UserTableMap::COL_ID);
            $criteria->addSelectColumn(UserTableMap::COL_USERNAME);
            $criteria->addSelectColumn(UserTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(UserTableMap::COL_API_KEY);
            $criteria->addSelectColumn(UserTableMap::COL_MAIL);
            $criteria->addSelectColumn(UserTableMap::COL_AVATAR);
            $criteria->addSelectColumn(UserTableMap::COL_ABOUT);
            $criteria->addSelectColumn(UserTableMap::COL_AUTOPLAY);
            $criteria->addSelectColumn(UserTableMap::COL_TERMS_ACCEPTED);
            $criteria->addSelectColumn(UserTableMap::COL_STATUS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.api_key');
            $criteria->addSelectColumn($alias . '.mail');
            $criteria->addSelectColumn($alias . '.avatar');
            $criteria->addSelectColumn($alias . '.about');
            $criteria->addSelectColumn($alias . '.autoplay');
            $criteria->addSelectColumn($alias . '.terms_accepted');
            $criteria->addSelectColumn($alias . '.status');
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
        return Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME)->getTable(UserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(UserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(UserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new UserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a User or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or User object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Tekstove\ApiBundle\Model\User) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(UserTableMap::DATABASE_NAME);
            $criteria->add(UserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = UserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            UserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                UserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return UserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a User or Criteria object.
     *
     * @param mixed               $criteria Criteria or User object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from User object
        }

        if ($criteria->containsKey(UserTableMap::COL_ID) && $criteria->keyContainsValue(UserTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.UserTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = UserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // UserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
UserTableMap::buildTableMap();
