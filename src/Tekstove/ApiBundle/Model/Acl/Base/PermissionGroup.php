<?php

namespace Tekstove\ApiBundle\Model\Acl\Base;

use \Exception;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Tekstove\ApiBundle\Model\Acl\PermissionGroup as ChildPermissionGroup;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission as ChildPermissionGroupPermission;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupPermissionQuery as ChildPermissionGroupPermissionQuery;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupQuery as ChildPermissionGroupQuery;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUser as ChildPermissionGroupUser;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery as ChildPermissionGroupUserQuery;
use Tekstove\ApiBundle\Model\Acl\Map\PermissionGroupTableMap;

/**
 * Base class that represents a row from the 'permission_group' table.
 *
 *
 *
* @package    propel.generator.src..Tekstove.ApiBundle.Model.Acl.Base
*/
abstract class PermissionGroup implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Acl\\Map\\PermissionGroupTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the image field.
     *
     * @var        string
     */
    protected $image;

    /**
     * @var        ObjectCollection|ChildPermissionGroupPermission[] Collection to store aggregation of ChildPermissionGroupPermission objects.
     */
    protected $collPermissionGroupPermissions;
    protected $collPermissionGroupPermissionsPartial;

    /**
     * @var        ObjectCollection|ChildPermissionGroupUser[] Collection to store aggregation of ChildPermissionGroupUser objects.
     */
    protected $collPermissionGroupUsers;
    protected $collPermissionGroupUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermissionGroupPermission[]
     */
    protected $permissionGroupPermissionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPermissionGroupUser[]
     */
    protected $permissionGroupUsersScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Acl\Base\PermissionGroup object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>PermissionGroup</code> instance.  If
     * <code>obj</code> is an instance of <code>PermissionGroup</code>, delegates to
     * <code>equals(PermissionGroup)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|PermissionGroup The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [image] column value.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Acl\PermissionGroup The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PermissionGroupTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Acl\PermissionGroup The current object (for fluent API support)
     */
    protected function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[PermissionGroupTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [image] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Acl\PermissionGroup The current object (for fluent API support)
     */
    protected function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image !== $v) {
            $this->image = $v;
            $this->modifiedColumns[PermissionGroupTableMap::COL_IMAGE] = true;
        }

        return $this;
    } // setImage()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PermissionGroupTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PermissionGroupTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PermissionGroupTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = PermissionGroupTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\Acl\\PermissionGroup'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PermissionGroupTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getImage();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['PermissionGroup'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PermissionGroup'][$this->hashCode()] = true;
        $keys = PermissionGroupTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getImage(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPermissionGroupPermissions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'permissionGroupPermissions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'permission_group_permissions';
                        break;
                    default:
                        $key = 'PermissionGroupPermissions';
                }

                $result[$key] = $this->collPermissionGroupPermissions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPermissionGroupUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'permissionGroupUsers';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'permission_group_users';
                        break;
                    default:
                        $key = 'PermissionGroupUsers';
                }

                $result[$key] = $this->collPermissionGroupUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PermissionGroupTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PermissionGroupTableMap::COL_ID)) {
            $criteria->add(PermissionGroupTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(PermissionGroupTableMap::COL_NAME)) {
            $criteria->add(PermissionGroupTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(PermissionGroupTableMap::COL_IMAGE)) {
            $criteria->add(PermissionGroupTableMap::COL_IMAGE, $this->image);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildPermissionGroupQuery::create();
        $criteria->add(PermissionGroupTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\Acl\PermissionGroup (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setImage($this->getImage());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPermissionGroupPermissions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermissionGroupPermission($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPermissionGroupUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermissionGroupUser($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Tekstove\ApiBundle\Model\Acl\PermissionGroup Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PermissionGroupPermission' == $relationName) {
            return $this->initPermissionGroupPermissions();
        }
        if ('PermissionGroupUser' == $relationName) {
            return $this->initPermissionGroupUsers();
        }
    }

    /**
     * Clears out the collPermissionGroupPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPermissionGroupPermissions()
     */
    public function clearPermissionGroupPermissions()
    {
        $this->collPermissionGroupPermissions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPermissionGroupPermissions collection loaded partially.
     */
    public function resetPartialPermissionGroupPermissions($v = true)
    {
        $this->collPermissionGroupPermissionsPartial = $v;
    }

    /**
     * Initializes the collPermissionGroupPermissions collection.
     *
     * By default this just sets the collPermissionGroupPermissions collection to an empty array (like clearcollPermissionGroupPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPermissionGroupPermissions($overrideExisting = true)
    {
        if (null !== $this->collPermissionGroupPermissions && !$overrideExisting) {
            return;
        }
        $this->collPermissionGroupPermissions = new ObjectCollection();
        $this->collPermissionGroupPermissions->setModel('\Tekstove\ApiBundle\Model\Acl\PermissionGroupPermission');
    }

    /**
     * Gets an array of ChildPermissionGroupPermission objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPermissionGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPermissionGroupPermission[] List of ChildPermissionGroupPermission objects
     * @throws PropelException
     */
    public function getPermissionGroupPermissions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionGroupPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissionGroupPermissions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPermissionGroupPermissions) {
                // return empty collection
                $this->initPermissionGroupPermissions();
            } else {
                $collPermissionGroupPermissions = ChildPermissionGroupPermissionQuery::create(null, $criteria)
                    ->filterByPermissionGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPermissionGroupPermissionsPartial && count($collPermissionGroupPermissions)) {
                        $this->initPermissionGroupPermissions(false);

                        foreach ($collPermissionGroupPermissions as $obj) {
                            if (false == $this->collPermissionGroupPermissions->contains($obj)) {
                                $this->collPermissionGroupPermissions->append($obj);
                            }
                        }

                        $this->collPermissionGroupPermissionsPartial = true;
                    }

                    return $collPermissionGroupPermissions;
                }

                if ($partial && $this->collPermissionGroupPermissions) {
                    foreach ($this->collPermissionGroupPermissions as $obj) {
                        if ($obj->isNew()) {
                            $collPermissionGroupPermissions[] = $obj;
                        }
                    }
                }

                $this->collPermissionGroupPermissions = $collPermissionGroupPermissions;
                $this->collPermissionGroupPermissionsPartial = false;
            }
        }

        return $this->collPermissionGroupPermissions;
    }

    /**
     * Sets a collection of ChildPermissionGroupPermission objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $permissionGroupPermissions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPermissionGroup The current object (for fluent API support)
     */
    public function setPermissionGroupPermissions(Collection $permissionGroupPermissions, ConnectionInterface $con = null)
    {
        /** @var ChildPermissionGroupPermission[] $permissionGroupPermissionsToDelete */
        $permissionGroupPermissionsToDelete = $this->getPermissionGroupPermissions(new Criteria(), $con)->diff($permissionGroupPermissions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->permissionGroupPermissionsScheduledForDeletion = clone $permissionGroupPermissionsToDelete;

        foreach ($permissionGroupPermissionsToDelete as $permissionGroupPermissionRemoved) {
            $permissionGroupPermissionRemoved->setPermissionGroup(null);
        }

        $this->collPermissionGroupPermissions = null;
        foreach ($permissionGroupPermissions as $permissionGroupPermission) {
            $this->addPermissionGroupPermission($permissionGroupPermission);
        }

        $this->collPermissionGroupPermissions = $permissionGroupPermissions;
        $this->collPermissionGroupPermissionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PermissionGroupPermission objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PermissionGroupPermission objects.
     * @throws PropelException
     */
    public function countPermissionGroupPermissions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionGroupPermissionsPartial && !$this->isNew();
        if (null === $this->collPermissionGroupPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissionGroupPermissions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPermissionGroupPermissions());
            }

            $query = ChildPermissionGroupPermissionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPermissionGroup($this)
                ->count($con);
        }

        return count($this->collPermissionGroupPermissions);
    }

    /**
     * Method called to associate a ChildPermissionGroupPermission object to this object
     * through the ChildPermissionGroupPermission foreign key attribute.
     *
     * @param  ChildPermissionGroupPermission $l ChildPermissionGroupPermission
     * @return $this|\Tekstove\ApiBundle\Model\Acl\PermissionGroup The current object (for fluent API support)
     */
    public function addPermissionGroupPermission(ChildPermissionGroupPermission $l)
    {
        if ($this->collPermissionGroupPermissions === null) {
            $this->initPermissionGroupPermissions();
            $this->collPermissionGroupPermissionsPartial = true;
        }

        if (!$this->collPermissionGroupPermissions->contains($l)) {
            $this->doAddPermissionGroupPermission($l);

            if ($this->permissionGroupPermissionsScheduledForDeletion and $this->permissionGroupPermissionsScheduledForDeletion->contains($l)) {
                $this->permissionGroupPermissionsScheduledForDeletion->remove($this->permissionGroupPermissionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPermissionGroupPermission $permissionGroupPermission The ChildPermissionGroupPermission object to add.
     */
    protected function doAddPermissionGroupPermission(ChildPermissionGroupPermission $permissionGroupPermission)
    {
        $this->collPermissionGroupPermissions[]= $permissionGroupPermission;
        $permissionGroupPermission->setPermissionGroup($this);
    }

    /**
     * @param  ChildPermissionGroupPermission $permissionGroupPermission The ChildPermissionGroupPermission object to remove.
     * @return $this|ChildPermissionGroup The current object (for fluent API support)
     */
    public function removePermissionGroupPermission(ChildPermissionGroupPermission $permissionGroupPermission)
    {
        if ($this->getPermissionGroupPermissions()->contains($permissionGroupPermission)) {
            $pos = $this->collPermissionGroupPermissions->search($permissionGroupPermission);
            $this->collPermissionGroupPermissions->remove($pos);
            if (null === $this->permissionGroupPermissionsScheduledForDeletion) {
                $this->permissionGroupPermissionsScheduledForDeletion = clone $this->collPermissionGroupPermissions;
                $this->permissionGroupPermissionsScheduledForDeletion->clear();
            }
            $this->permissionGroupPermissionsScheduledForDeletion[]= clone $permissionGroupPermission;
            $permissionGroupPermission->setPermissionGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PermissionGroup is new, it will return
     * an empty collection; or if this PermissionGroup has previously
     * been saved, it will retrieve related PermissionGroupPermissions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PermissionGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPermissionGroupPermission[] List of ChildPermissionGroupPermission objects
     */
    public function getPermissionGroupPermissionsJoinPermission(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPermissionGroupPermissionQuery::create(null, $criteria);
        $query->joinWith('Permission', $joinBehavior);

        return $this->getPermissionGroupPermissions($query, $con);
    }

    /**
     * Clears out the collPermissionGroupUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPermissionGroupUsers()
     */
    public function clearPermissionGroupUsers()
    {
        $this->collPermissionGroupUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPermissionGroupUsers collection loaded partially.
     */
    public function resetPartialPermissionGroupUsers($v = true)
    {
        $this->collPermissionGroupUsersPartial = $v;
    }

    /**
     * Initializes the collPermissionGroupUsers collection.
     *
     * By default this just sets the collPermissionGroupUsers collection to an empty array (like clearcollPermissionGroupUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPermissionGroupUsers($overrideExisting = true)
    {
        if (null !== $this->collPermissionGroupUsers && !$overrideExisting) {
            return;
        }
        $this->collPermissionGroupUsers = new ObjectCollection();
        $this->collPermissionGroupUsers->setModel('\Tekstove\ApiBundle\Model\Acl\PermissionGroupUser');
    }

    /**
     * Gets an array of ChildPermissionGroupUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPermissionGroup is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPermissionGroupUser[] List of ChildPermissionGroupUser objects
     * @throws PropelException
     */
    public function getPermissionGroupUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionGroupUsersPartial && !$this->isNew();
        if (null === $this->collPermissionGroupUsers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPermissionGroupUsers) {
                // return empty collection
                $this->initPermissionGroupUsers();
            } else {
                $collPermissionGroupUsers = ChildPermissionGroupUserQuery::create(null, $criteria)
                    ->filterByPermissionGroup($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPermissionGroupUsersPartial && count($collPermissionGroupUsers)) {
                        $this->initPermissionGroupUsers(false);

                        foreach ($collPermissionGroupUsers as $obj) {
                            if (false == $this->collPermissionGroupUsers->contains($obj)) {
                                $this->collPermissionGroupUsers->append($obj);
                            }
                        }

                        $this->collPermissionGroupUsersPartial = true;
                    }

                    return $collPermissionGroupUsers;
                }

                if ($partial && $this->collPermissionGroupUsers) {
                    foreach ($this->collPermissionGroupUsers as $obj) {
                        if ($obj->isNew()) {
                            $collPermissionGroupUsers[] = $obj;
                        }
                    }
                }

                $this->collPermissionGroupUsers = $collPermissionGroupUsers;
                $this->collPermissionGroupUsersPartial = false;
            }
        }

        return $this->collPermissionGroupUsers;
    }

    /**
     * Sets a collection of ChildPermissionGroupUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $permissionGroupUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildPermissionGroup The current object (for fluent API support)
     */
    public function setPermissionGroupUsers(Collection $permissionGroupUsers, ConnectionInterface $con = null)
    {
        /** @var ChildPermissionGroupUser[] $permissionGroupUsersToDelete */
        $permissionGroupUsersToDelete = $this->getPermissionGroupUsers(new Criteria(), $con)->diff($permissionGroupUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->permissionGroupUsersScheduledForDeletion = clone $permissionGroupUsersToDelete;

        foreach ($permissionGroupUsersToDelete as $permissionGroupUserRemoved) {
            $permissionGroupUserRemoved->setPermissionGroup(null);
        }

        $this->collPermissionGroupUsers = null;
        foreach ($permissionGroupUsers as $permissionGroupUser) {
            $this->addPermissionGroupUser($permissionGroupUser);
        }

        $this->collPermissionGroupUsers = $permissionGroupUsers;
        $this->collPermissionGroupUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PermissionGroupUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PermissionGroupUser objects.
     * @throws PropelException
     */
    public function countPermissionGroupUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPermissionGroupUsersPartial && !$this->isNew();
        if (null === $this->collPermissionGroupUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPermissionGroupUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPermissionGroupUsers());
            }

            $query = ChildPermissionGroupUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPermissionGroup($this)
                ->count($con);
        }

        return count($this->collPermissionGroupUsers);
    }

    /**
     * Method called to associate a ChildPermissionGroupUser object to this object
     * through the ChildPermissionGroupUser foreign key attribute.
     *
     * @param  ChildPermissionGroupUser $l ChildPermissionGroupUser
     * @return $this|\Tekstove\ApiBundle\Model\Acl\PermissionGroup The current object (for fluent API support)
     */
    public function addPermissionGroupUser(ChildPermissionGroupUser $l)
    {
        if ($this->collPermissionGroupUsers === null) {
            $this->initPermissionGroupUsers();
            $this->collPermissionGroupUsersPartial = true;
        }

        if (!$this->collPermissionGroupUsers->contains($l)) {
            $this->doAddPermissionGroupUser($l);

            if ($this->permissionGroupUsersScheduledForDeletion and $this->permissionGroupUsersScheduledForDeletion->contains($l)) {
                $this->permissionGroupUsersScheduledForDeletion->remove($this->permissionGroupUsersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPermissionGroupUser $permissionGroupUser The ChildPermissionGroupUser object to add.
     */
    protected function doAddPermissionGroupUser(ChildPermissionGroupUser $permissionGroupUser)
    {
        $this->collPermissionGroupUsers[]= $permissionGroupUser;
        $permissionGroupUser->setPermissionGroup($this);
    }

    /**
     * @param  ChildPermissionGroupUser $permissionGroupUser The ChildPermissionGroupUser object to remove.
     * @return $this|ChildPermissionGroup The current object (for fluent API support)
     */
    public function removePermissionGroupUser(ChildPermissionGroupUser $permissionGroupUser)
    {
        if ($this->getPermissionGroupUsers()->contains($permissionGroupUser)) {
            $pos = $this->collPermissionGroupUsers->search($permissionGroupUser);
            $this->collPermissionGroupUsers->remove($pos);
            if (null === $this->permissionGroupUsersScheduledForDeletion) {
                $this->permissionGroupUsersScheduledForDeletion = clone $this->collPermissionGroupUsers;
                $this->permissionGroupUsersScheduledForDeletion->clear();
            }
            $this->permissionGroupUsersScheduledForDeletion[]= clone $permissionGroupUser;
            $permissionGroupUser->setPermissionGroup(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PermissionGroup is new, it will return
     * an empty collection; or if this PermissionGroup has previously
     * been saved, it will retrieve related PermissionGroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PermissionGroup.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPermissionGroupUser[] List of ChildPermissionGroupUser objects
     */
    public function getPermissionGroupUsersJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPermissionGroupUserQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getPermissionGroupUsers($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->image = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPermissionGroupPermissions) {
                foreach ($this->collPermissionGroupPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPermissionGroupUsers) {
                foreach ($this->collPermissionGroupUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPermissionGroupPermissions = null;
        $this->collPermissionGroupUsers = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PermissionGroupTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
