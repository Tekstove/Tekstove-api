<?php

namespace Tekstove\ApiBundle\Model\User\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use Tekstove\ApiBundle\Model\User\Pm;
use Tekstove\ApiBundle\Model\User\PmQuery;


/**
 * This class defines the structure of the 'pm' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PmTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Tekstove.ApiBundle.Model.User.Map.PmTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'pm';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Tekstove\\ApiBundle\\Model\\User\\Pm';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Tekstove.ApiBundle.Model.User.Pm';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'pm.id';

    /**
     * the column name for the user_to field
     */
    const COL_USER_TO = 'pm.user_to';

    /**
     * the column name for the user_from field
     */
    const COL_USER_FROM = 'pm.user_from';

    /**
     * the column name for the text field
     */
    const COL_TEXT = 'pm.text';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'pm.title';

    /**
     * the column name for the read field
     */
    const COL_READ = 'pm.read';

    /**
     * the column name for the datetime field
     */
    const COL_DATETIME = 'pm.datetime';

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
        self::TYPE_PHPNAME       => array('Id', 'UserTo', 'UserFrom', 'Text', 'Title', 'Read', 'Datetime', ),
        self::TYPE_CAMELNAME     => array('id', 'userTo', 'userFrom', 'text', 'title', 'read', 'datetime', ),
        self::TYPE_COLNAME       => array(PmTableMap::COL_ID, PmTableMap::COL_USER_TO, PmTableMap::COL_USER_FROM, PmTableMap::COL_TEXT, PmTableMap::COL_TITLE, PmTableMap::COL_READ, PmTableMap::COL_DATETIME, ),
        self::TYPE_FIELDNAME     => array('id', 'user_to', 'user_from', 'text', 'title', 'read', 'datetime', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'UserTo' => 1, 'UserFrom' => 2, 'Text' => 3, 'Title' => 4, 'Read' => 5, 'Datetime' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'userTo' => 1, 'userFrom' => 2, 'text' => 3, 'title' => 4, 'read' => 5, 'datetime' => 6, ),
        self::TYPE_COLNAME       => array(PmTableMap::COL_ID => 0, PmTableMap::COL_USER_TO => 1, PmTableMap::COL_USER_FROM => 2, PmTableMap::COL_TEXT => 3, PmTableMap::COL_TITLE => 4, PmTableMap::COL_READ => 5, PmTableMap::COL_DATETIME => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'user_to' => 1, 'user_from' => 2, 'text' => 3, 'title' => 4, 'read' => 5, 'datetime' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('pm');
        $this->setPhpName('Pm');
        $this->setIdentifierQuoting(true);
        $this->setClassName('\\Tekstove\\ApiBundle\\Model\\User\\Pm');
        $this->setPackage('Tekstove.ApiBundle.Model.User');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('user_to', 'UserTo', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('user_from', 'UserFrom', 'INTEGER', 'user', 'id', true, null, null);
        $this->addColumn('text', 'Text', 'VARCHAR', true, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('read', 'Read', 'BOOLEAN', true, 1, null);
        $this->addColumn('datetime', 'Datetime', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserRelatedByUserTo', '\\Tekstove\\ApiBundle\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_to',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('UserRelatedByUserFrom', '\\Tekstove\\ApiBundle\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_from',
    1 => ':id',
  ),
), null, null, null, false);
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
            'validate' => array('titleValidationNotEmpty' => array ('column' => 'title','validator' => 'NotBlank',), 'titleValidationMinLength' => array ('column' => 'title','validator' => 'Length','options' => array ('min' => 3,'max' => 150,),), 'textValidationNotEmpty' => array ('column' => 'text','validator' => 'NotBlank',), 'textValidationMaxLength' => array ('column' => 'text','validator' => 'Length','options' => array ('min' => 3,'max' => 999,),), ),
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
        return $withPrefix ? PmTableMap::CLASS_DEFAULT : PmTableMap::OM_CLASS;
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
     * @return array           (Pm object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PmTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PmTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PmTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PmTableMap::OM_CLASS;
            /** @var Pm $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PmTableMap::addInstanceToPool($obj, $key);
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
            $key = PmTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PmTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Pm $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PmTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PmTableMap::COL_ID);
            $criteria->addSelectColumn(PmTableMap::COL_USER_TO);
            $criteria->addSelectColumn(PmTableMap::COL_USER_FROM);
            $criteria->addSelectColumn(PmTableMap::COL_TEXT);
            $criteria->addSelectColumn(PmTableMap::COL_TITLE);
            $criteria->addSelectColumn(PmTableMap::COL_READ);
            $criteria->addSelectColumn(PmTableMap::COL_DATETIME);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.user_to');
            $criteria->addSelectColumn($alias . '.user_from');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.read');
            $criteria->addSelectColumn($alias . '.datetime');
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
        return Propel::getServiceContainer()->getDatabaseMap(PmTableMap::DATABASE_NAME)->getTable(PmTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PmTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PmTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PmTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Pm or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Pm object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PmTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Tekstove\ApiBundle\Model\User\Pm) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PmTableMap::DATABASE_NAME);
            $criteria->add(PmTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PmQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PmTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PmTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the pm table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PmQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Pm or Criteria object.
     *
     * @param mixed               $criteria Criteria or Pm object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PmTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Pm object
        }

        if ($criteria->containsKey(PmTableMap::COL_ID) && $criteria->keyContainsValue(PmTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PmTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PmQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PmTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PmTableMap::buildTableMap();
