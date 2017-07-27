<?php

namespace Tekstove\ApiBundle\Model\Base;

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
use Tekstove\ApiBundle\Model\Language as ChildLanguage;
use Tekstove\ApiBundle\Model\LanguageQuery as ChildLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric as ChildLyric;
use Tekstove\ApiBundle\Model\LyricQuery as ChildLyricQuery;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricLanguage as BaseLyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricLanguageTableMap;
use Tekstove\ApiBundle\Model\Map\LanguageTableMap;

/**
 * Base class that represents a row from the 'language' table.
 *
 *
 *
 * @package    propel.generator.src.Tekstove.ApiBundle.Model.Base
 */
abstract class Language implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Map\\LanguageTableMap';


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
     * @var        ObjectCollection|LyricLanguage[] Collection to store aggregation of LyricLanguage objects.
     */
    protected $collLyricLanguages;
    protected $collLyricLanguagesPartial;

    /**
     * @var        ObjectCollection|ChildLyric[] Cross Collection to store aggregation of ChildLyric objects.
     */
    protected $collLyrics;

    /**
     * @var bool
     */
    protected $collLyricsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLyric[]
     */
    protected $lyricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|LyricLanguage[]
     */
    protected $lyricLanguagesScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Base\Language object.
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
     * Compares this with another <code>Language</code> instance.  If
     * <code>obj</code> is an instance of <code>Language</code>, delegates to
     * <code>equals(Language)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Language The current object, for fluid interface
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
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Language The current object (for fluent API support)
     */
    protected function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LanguageTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Language The current object (for fluent API support)
     */
    protected function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[LanguageTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LanguageTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LanguageTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 2; // 2 = LanguageTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\Language'), 0, $e);
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
        $pos = LanguageTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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

        if (isset($alreadyDumpedObjects['Language'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Language'][$this->hashCode()] = true;
        $keys = LanguageTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collLyricLanguages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lyricLanguages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lyric_languages';
                        break;
                    default:
                        $key = 'LyricLanguages';
                }

                $result[$key] = $this->collLyricLanguages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $criteria = new Criteria(LanguageTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LanguageTableMap::COL_ID)) {
            $criteria->add(LanguageTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LanguageTableMap::COL_NAME)) {
            $criteria->add(LanguageTableMap::COL_NAME, $this->name);
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
        $criteria = ChildLanguageQuery::create();
        $criteria->add(LanguageTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\Language (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getLyricLanguages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLyricLanguage($relObj->copy($deepCopy));
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
     * @return \Tekstove\ApiBundle\Model\Language Clone of current object.
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
        if ('LyricLanguage' == $relationName) {
            $this->initLyricLanguages();
            return;
        }
    }

    /**
     * Clears out the collLyricLanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLyricLanguages()
     */
    public function clearLyricLanguages()
    {
        $this->collLyricLanguages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLyricLanguages collection loaded partially.
     */
    public function resetPartialLyricLanguages($v = true)
    {
        $this->collLyricLanguagesPartial = $v;
    }

    /**
     * Initializes the collLyricLanguages collection.
     *
     * By default this just sets the collLyricLanguages collection to an empty array (like clearcollLyricLanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLyricLanguages($overrideExisting = true)
    {
        if (null !== $this->collLyricLanguages && !$overrideExisting) {
            return;
        }

        $collectionClassName = LyricLanguageTableMap::getTableMap()->getCollectionClassName();

        $this->collLyricLanguages = new $collectionClassName;
        $this->collLyricLanguages->setModel('\Tekstove\ApiBundle\Model\Lyric\LyricLanguage');
    }

    /**
     * Gets an array of LyricLanguage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguage is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|LyricLanguage[] List of LyricLanguage objects
     * @throws PropelException
     */
    public function getLyricLanguages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricLanguagesPartial && !$this->isNew();
        if (null === $this->collLyricLanguages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLyricLanguages) {
                // return empty collection
                $this->initLyricLanguages();
            } else {
                $collLyricLanguages = LyricLanguageQuery::create(null, $criteria)
                    ->filterByLanguage($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLyricLanguagesPartial && count($collLyricLanguages)) {
                        $this->initLyricLanguages(false);

                        foreach ($collLyricLanguages as $obj) {
                            if (false == $this->collLyricLanguages->contains($obj)) {
                                $this->collLyricLanguages->append($obj);
                            }
                        }

                        $this->collLyricLanguagesPartial = true;
                    }

                    return $collLyricLanguages;
                }

                if ($partial && $this->collLyricLanguages) {
                    foreach ($this->collLyricLanguages as $obj) {
                        if ($obj->isNew()) {
                            $collLyricLanguages[] = $obj;
                        }
                    }
                }

                $this->collLyricLanguages = $collLyricLanguages;
                $this->collLyricLanguagesPartial = false;
            }
        }

        return $this->collLyricLanguages;
    }

    /**
     * Sets a collection of LyricLanguage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lyricLanguages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function setLyricLanguages(Collection $lyricLanguages, ConnectionInterface $con = null)
    {
        /** @var LyricLanguage[] $lyricLanguagesToDelete */
        $lyricLanguagesToDelete = $this->getLyricLanguages(new Criteria(), $con)->diff($lyricLanguages);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->lyricLanguagesScheduledForDeletion = clone $lyricLanguagesToDelete;

        foreach ($lyricLanguagesToDelete as $lyricLanguageRemoved) {
            $lyricLanguageRemoved->setLanguage(null);
        }

        $this->collLyricLanguages = null;
        foreach ($lyricLanguages as $lyricLanguage) {
            $this->addLyricLanguage($lyricLanguage);
        }

        $this->collLyricLanguages = $lyricLanguages;
        $this->collLyricLanguagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseLyricLanguage objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseLyricLanguage objects.
     * @throws PropelException
     */
    public function countLyricLanguages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricLanguagesPartial && !$this->isNew();
        if (null === $this->collLyricLanguages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLyricLanguages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLyricLanguages());
            }

            $query = LyricLanguageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByLanguage($this)
                ->count($con);
        }

        return count($this->collLyricLanguages);
    }

    /**
     * Method called to associate a LyricLanguage object to this object
     * through the LyricLanguage foreign key attribute.
     *
     * @param  LyricLanguage $l LyricLanguage
     * @return $this|\Tekstove\ApiBundle\Model\Language The current object (for fluent API support)
     */
    public function addLyricLanguage(LyricLanguage $l)
    {
        if ($this->collLyricLanguages === null) {
            $this->initLyricLanguages();
            $this->collLyricLanguagesPartial = true;
        }

        if (!$this->collLyricLanguages->contains($l)) {
            $this->doAddLyricLanguage($l);

            if ($this->lyricLanguagesScheduledForDeletion and $this->lyricLanguagesScheduledForDeletion->contains($l)) {
                $this->lyricLanguagesScheduledForDeletion->remove($this->lyricLanguagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param LyricLanguage $lyricLanguage The LyricLanguage object to add.
     */
    protected function doAddLyricLanguage(LyricLanguage $lyricLanguage)
    {
        $this->collLyricLanguages[]= $lyricLanguage;
        $lyricLanguage->setLanguage($this);
    }

    /**
     * @param  LyricLanguage $lyricLanguage The LyricLanguage object to remove.
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function removeLyricLanguage(LyricLanguage $lyricLanguage)
    {
        if ($this->getLyricLanguages()->contains($lyricLanguage)) {
            $pos = $this->collLyricLanguages->search($lyricLanguage);
            $this->collLyricLanguages->remove($pos);
            if (null === $this->lyricLanguagesScheduledForDeletion) {
                $this->lyricLanguagesScheduledForDeletion = clone $this->collLyricLanguages;
                $this->lyricLanguagesScheduledForDeletion->clear();
            }
            $this->lyricLanguagesScheduledForDeletion[]= clone $lyricLanguage;
            $lyricLanguage->setLanguage(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Language is new, it will return
     * an empty collection; or if this Language has previously
     * been saved, it will retrieve related LyricLanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Language.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricLanguage[] List of LyricLanguage objects
     */
    public function getLyricLanguagesJoinLyric(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricLanguageQuery::create(null, $criteria);
        $query->joinWith('Lyric', $joinBehavior);

        return $this->getLyricLanguages($query, $con);
    }

    /**
     * Clears out the collLyrics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLyrics()
     */
    public function clearLyrics()
    {
        $this->collLyrics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collLyrics crossRef collection.
     *
     * By default this just sets the collLyrics collection to an empty collection (like clearLyrics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLyrics()
    {
        $collectionClassName = LyricLanguageTableMap::getTableMap()->getCollectionClassName();

        $this->collLyrics = new $collectionClassName;
        $this->collLyricsPartial = true;
        $this->collLyrics->setModel('\Tekstove\ApiBundle\Model\Lyric');
    }

    /**
     * Checks if the collLyrics collection is loaded.
     *
     * @return bool
     */
    public function isLyricsLoaded()
    {
        return null !== $this->collLyrics;
    }

    /**
     * Gets a collection of ChildLyric objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLanguage is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildLyric[] List of ChildLyric objects
     */
    public function getLyrics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricsPartial && !$this->isNew();
        if (null === $this->collLyrics || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLyrics) {
                    $this->initLyrics();
                }
            } else {

                $query = ChildLyricQuery::create(null, $criteria)
                    ->filterByLanguage($this);
                $collLyrics = $query->find($con);
                if (null !== $criteria) {
                    return $collLyrics;
                }

                if ($partial && $this->collLyrics) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collLyrics as $obj) {
                        if (!$collLyrics->contains($obj)) {
                            $collLyrics[] = $obj;
                        }
                    }
                }

                $this->collLyrics = $collLyrics;
                $this->collLyricsPartial = false;
            }
        }

        return $this->collLyrics;
    }

    /**
     * Sets a collection of Lyric objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $lyrics A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLanguage The current object (for fluent API support)
     */
    public function setLyrics(Collection $lyrics, ConnectionInterface $con = null)
    {
        $this->clearLyrics();
        $currentLyrics = $this->getLyrics();

        $lyricsScheduledForDeletion = $currentLyrics->diff($lyrics);

        foreach ($lyricsScheduledForDeletion as $toDelete) {
            $this->removeLyric($toDelete);
        }

        foreach ($lyrics as $lyric) {
            if (!$currentLyrics->contains($lyric)) {
                $this->doAddLyric($lyric);
            }
        }

        $this->collLyricsPartial = false;
        $this->collLyrics = $lyrics;

        return $this;
    }

    /**
     * Gets the number of Lyric objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Lyric objects
     */
    public function countLyrics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricsPartial && !$this->isNew();
        if (null === $this->collLyrics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLyrics) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getLyrics());
                }

                $query = ChildLyricQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLanguage($this)
                    ->count($con);
            }
        } else {
            return count($this->collLyrics);
        }
    }

    /**
     * Associate a ChildLyric to this object
     * through the lyric_language cross reference table.
     *
     * @param ChildLyric $lyric
     * @return ChildLanguage The current object (for fluent API support)
     */
    public function addLyric(ChildLyric $lyric)
    {
        if ($this->collLyrics === null) {
            $this->initLyrics();
        }

        if (!$this->getLyrics()->contains($lyric)) {
            // only add it if the **same** object is not already associated
            $this->collLyrics->push($lyric);
            $this->doAddLyric($lyric);
        }

        return $this;
    }

    /**
     *
     * @param ChildLyric $lyric
     */
    protected function doAddLyric(ChildLyric $lyric)
    {
        $lyricLanguage = new LyricLanguage();

        $lyricLanguage->setLyric($lyric);

        $lyricLanguage->setLanguage($this);

        $this->addLyricLanguage($lyricLanguage);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$lyric->isLanguagesLoaded()) {
            $lyric->initLanguages();
            $lyric->getLanguages()->push($this);
        } elseif (!$lyric->getLanguages()->contains($this)) {
            $lyric->getLanguages()->push($this);
        }

    }

    /**
     * Remove lyric of this object
     * through the lyric_language cross reference table.
     *
     * @param ChildLyric $lyric
     * @return ChildLanguage The current object (for fluent API support)
     */
    public function removeLyric(ChildLyric $lyric)
    {
        if ($this->getLyrics()->contains($lyric)) {
            $lyricLanguage = new LyricLanguage();
            $lyricLanguage->setLyric($lyric);
            if ($lyric->isLanguagesLoaded()) {
                //remove the back reference if available
                $lyric->getLanguages()->removeObject($this);
            }

            $lyricLanguage->setLanguage($this);
            $this->removeLyricLanguage(clone $lyricLanguage);
            $lyricLanguage->clear();

            $this->collLyrics->remove($this->collLyrics->search($lyric));

            if (null === $this->lyricsScheduledForDeletion) {
                $this->lyricsScheduledForDeletion = clone $this->collLyrics;
                $this->lyricsScheduledForDeletion->clear();
            }

            $this->lyricsScheduledForDeletion->push($lyric);
        }


        return $this;
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
            if ($this->collLyricLanguages) {
                foreach ($this->collLyricLanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLyrics) {
                foreach ($this->collLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collLyricLanguages = null;
        $this->collLyrics = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LanguageTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
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
