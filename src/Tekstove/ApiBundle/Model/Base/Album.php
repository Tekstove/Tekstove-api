<?php

namespace Tekstove\ApiBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Tekstove\ApiBundle\Model\Album as ChildAlbum;
use Tekstove\ApiBundle\Model\AlbumArtist as ChildAlbumArtist;
use Tekstove\ApiBundle\Model\AlbumArtistQuery as ChildAlbumArtistQuery;
use Tekstove\ApiBundle\Model\AlbumLyric as ChildAlbumLyric;
use Tekstove\ApiBundle\Model\AlbumLyricQuery as ChildAlbumLyricQuery;
use Tekstove\ApiBundle\Model\AlbumQuery as ChildAlbumQuery;
use Tekstove\ApiBundle\Model\User as ChildUser;
use Tekstove\ApiBundle\Model\UserQuery as ChildUserQuery;
use Tekstove\ApiBundle\Model\Map\AlbumArtistTableMap;
use Tekstove\ApiBundle\Model\Map\AlbumLyricTableMap;
use Tekstove\ApiBundle\Model\Map\AlbumTableMap;

/**
 * Base class that represents a row from the 'album' table.
 *
 *
 *
 * @package    propel.generator.src.Tekstove.ApiBundle.Model.Base
 */
abstract class Album implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Map\\AlbumTableMap';


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
     * The value for the year field.
     *
     * @var        int
     */
    protected $year;

    /**
     * The value for the image field.
     *
     * @var        string
     */
    protected $image;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildAlbumArtist[] Collection to store aggregation of ChildAlbumArtist objects.
     */
    protected $collAlbumArtists;
    protected $collAlbumArtistsPartial;

    /**
     * @var        ObjectCollection|ChildAlbumLyric[] Collection to store aggregation of ChildAlbumLyric objects.
     */
    protected $collAlbumLyrics;
    protected $collAlbumLyricsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAlbumArtist[]
     */
    protected $albumArtistsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAlbumLyric[]
     */
    protected $albumLyricsScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Base\Album object.
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
     * Compares this with another <code>Album</code> instance.  If
     * <code>obj</code> is an instance of <code>Album</code>, delegates to
     * <code>equals(Album)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Album The current object, for fluid interface
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
     * Get the [year] column value.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getsendBy()
    {
        return $this->user_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AlbumTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[AlbumTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [year] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function setYear($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->year !== $v) {
            $this->year = $v;
            $this->modifiedColumns[AlbumTableMap::COL_YEAR] = true;
        }

        return $this;
    } // setYear()

    /**
     * Set the value of [image] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function setImage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image !== $v) {
            $this->image = $v;
            $this->modifiedColumns[AlbumTableMap::COL_IMAGE] = true;
        }

        return $this;
    } // setImage()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function setsendBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[AlbumTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setsendBy()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AlbumTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AlbumTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AlbumTableMap::translateFieldName('Year', TableMap::TYPE_PHPNAME, $indexType)];
            $this->year = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AlbumTableMap::translateFieldName('Image', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AlbumTableMap::translateFieldName('sendBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = AlbumTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\Album'), 0, $e);
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
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AlbumTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAlbumQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collAlbumArtists = null;

            $this->collAlbumLyrics = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Album::setDeleted()
     * @see Album::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AlbumTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAlbumQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AlbumTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                AlbumTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->albumArtistsScheduledForDeletion !== null) {
                if (!$this->albumArtistsScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\AlbumArtistQuery::create()
                        ->filterByPrimaryKeys($this->albumArtistsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->albumArtistsScheduledForDeletion = null;
                }
            }

            if ($this->collAlbumArtists !== null) {
                foreach ($this->collAlbumArtists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->albumLyricsScheduledForDeletion !== null) {
                if (!$this->albumLyricsScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\AlbumLyricQuery::create()
                        ->filterByPrimaryKeys($this->albumLyricsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->albumLyricsScheduledForDeletion = null;
                }
            }

            if ($this->collAlbumLyrics !== null) {
                foreach ($this->collAlbumLyrics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[AlbumTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AlbumTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AlbumTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(AlbumTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(AlbumTableMap::COL_YEAR)) {
            $modifiedColumns[':p' . $index++]  = '`year`';
        }
        if ($this->isColumnModified(AlbumTableMap::COL_IMAGE)) {
            $modifiedColumns[':p' . $index++]  = '`image`';
        }
        if ($this->isColumnModified(AlbumTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }

        $sql = sprintf(
            'INSERT INTO `album` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`year`':
                        $stmt->bindValue($identifier, $this->year, PDO::PARAM_INT);
                        break;
                    case '`image`':
                        $stmt->bindValue($identifier, $this->image, PDO::PARAM_STR);
                        break;
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

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
        $pos = AlbumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getYear();
                break;
            case 3:
                return $this->getImage();
                break;
            case 4:
                return $this->getsendBy();
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

        if (isset($alreadyDumpedObjects['Album'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Album'][$this->hashCode()] = true;
        $keys = AlbumTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getYear(),
            $keys[3] => $this->getImage(),
            $keys[4] => $this->getsendBy(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collAlbumArtists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'albumArtists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'album_artists';
                        break;
                    default:
                        $key = 'AlbumArtists';
                }

                $result[$key] = $this->collAlbumArtists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAlbumLyrics) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'albumLyrics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'album_lyrics';
                        break;
                    default:
                        $key = 'AlbumLyrics';
                }

                $result[$key] = $this->collAlbumLyrics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Tekstove\ApiBundle\Model\Album
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AlbumTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Tekstove\ApiBundle\Model\Album
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setYear($value);
                break;
            case 3:
                $this->setImage($value);
                break;
            case 4:
                $this->setsendBy($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = AlbumTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setYear($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setImage($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setsendBy($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AlbumTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AlbumTableMap::COL_ID)) {
            $criteria->add(AlbumTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AlbumTableMap::COL_NAME)) {
            $criteria->add(AlbumTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(AlbumTableMap::COL_YEAR)) {
            $criteria->add(AlbumTableMap::COL_YEAR, $this->year);
        }
        if ($this->isColumnModified(AlbumTableMap::COL_IMAGE)) {
            $criteria->add(AlbumTableMap::COL_IMAGE, $this->image);
        }
        if ($this->isColumnModified(AlbumTableMap::COL_USER_ID)) {
            $criteria->add(AlbumTableMap::COL_USER_ID, $this->user_id);
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
        $criteria = ChildAlbumQuery::create();
        $criteria->add(AlbumTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\Album (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setYear($this->getYear());
        $copyObj->setImage($this->getImage());
        $copyObj->setsendBy($this->getsendBy());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAlbumArtists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAlbumArtist($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAlbumLyrics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAlbumLyric($relObj->copy($deepCopy));
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
     * @return \Tekstove\ApiBundle\Model\Album Clone of current object.
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
     * Declares an association between this object and a ChildUser object.
     *
     * @param  ChildUser $v
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setsendBy(NULL);
        } else {
            $this->setsendBy($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addAlbum($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildUser object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildUser The associated ChildUser object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id != 0)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addAlbums($this);
             */
        }

        return $this->aUser;
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
        if ('AlbumArtist' == $relationName) {
            $this->initAlbumArtists();
            return;
        }
        if ('AlbumLyric' == $relationName) {
            $this->initAlbumLyrics();
            return;
        }
    }

    /**
     * Clears out the collAlbumArtists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAlbumArtists()
     */
    public function clearAlbumArtists()
    {
        $this->collAlbumArtists = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAlbumArtists collection loaded partially.
     */
    public function resetPartialAlbumArtists($v = true)
    {
        $this->collAlbumArtistsPartial = $v;
    }

    /**
     * Initializes the collAlbumArtists collection.
     *
     * By default this just sets the collAlbumArtists collection to an empty array (like clearcollAlbumArtists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAlbumArtists($overrideExisting = true)
    {
        if (null !== $this->collAlbumArtists && !$overrideExisting) {
            return;
        }

        $collectionClassName = AlbumArtistTableMap::getTableMap()->getCollectionClassName();

        $this->collAlbumArtists = new $collectionClassName;
        $this->collAlbumArtists->setModel('\Tekstove\ApiBundle\Model\AlbumArtist');
    }

    /**
     * Gets an array of ChildAlbumArtist objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAlbum is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAlbumArtist[] List of ChildAlbumArtist objects
     * @throws PropelException
     */
    public function getAlbumArtists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumArtistsPartial && !$this->isNew();
        if (null === $this->collAlbumArtists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAlbumArtists) {
                // return empty collection
                $this->initAlbumArtists();
            } else {
                $collAlbumArtists = ChildAlbumArtistQuery::create(null, $criteria)
                    ->filterByAlbum($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAlbumArtistsPartial && count($collAlbumArtists)) {
                        $this->initAlbumArtists(false);

                        foreach ($collAlbumArtists as $obj) {
                            if (false == $this->collAlbumArtists->contains($obj)) {
                                $this->collAlbumArtists->append($obj);
                            }
                        }

                        $this->collAlbumArtistsPartial = true;
                    }

                    return $collAlbumArtists;
                }

                if ($partial && $this->collAlbumArtists) {
                    foreach ($this->collAlbumArtists as $obj) {
                        if ($obj->isNew()) {
                            $collAlbumArtists[] = $obj;
                        }
                    }
                }

                $this->collAlbumArtists = $collAlbumArtists;
                $this->collAlbumArtistsPartial = false;
            }
        }

        return $this->collAlbumArtists;
    }

    /**
     * Sets a collection of ChildAlbumArtist objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $albumArtists A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAlbum The current object (for fluent API support)
     */
    public function setAlbumArtists(Collection $albumArtists, ConnectionInterface $con = null)
    {
        /** @var ChildAlbumArtist[] $albumArtistsToDelete */
        $albumArtistsToDelete = $this->getAlbumArtists(new Criteria(), $con)->diff($albumArtists);


        $this->albumArtistsScheduledForDeletion = $albumArtistsToDelete;

        foreach ($albumArtistsToDelete as $albumArtistRemoved) {
            $albumArtistRemoved->setAlbum(null);
        }

        $this->collAlbumArtists = null;
        foreach ($albumArtists as $albumArtist) {
            $this->addAlbumArtist($albumArtist);
        }

        $this->collAlbumArtists = $albumArtists;
        $this->collAlbumArtistsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AlbumArtist objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AlbumArtist objects.
     * @throws PropelException
     */
    public function countAlbumArtists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumArtistsPartial && !$this->isNew();
        if (null === $this->collAlbumArtists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAlbumArtists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAlbumArtists());
            }

            $query = ChildAlbumArtistQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAlbum($this)
                ->count($con);
        }

        return count($this->collAlbumArtists);
    }

    /**
     * Method called to associate a ChildAlbumArtist object to this object
     * through the ChildAlbumArtist foreign key attribute.
     *
     * @param  ChildAlbumArtist $l ChildAlbumArtist
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function addAlbumArtist(ChildAlbumArtist $l)
    {
        if ($this->collAlbumArtists === null) {
            $this->initAlbumArtists();
            $this->collAlbumArtistsPartial = true;
        }

        if (!$this->collAlbumArtists->contains($l)) {
            $this->doAddAlbumArtist($l);

            if ($this->albumArtistsScheduledForDeletion and $this->albumArtistsScheduledForDeletion->contains($l)) {
                $this->albumArtistsScheduledForDeletion->remove($this->albumArtistsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAlbumArtist $albumArtist The ChildAlbumArtist object to add.
     */
    protected function doAddAlbumArtist(ChildAlbumArtist $albumArtist)
    {
        $this->collAlbumArtists[]= $albumArtist;
        $albumArtist->setAlbum($this);
    }

    /**
     * @param  ChildAlbumArtist $albumArtist The ChildAlbumArtist object to remove.
     * @return $this|ChildAlbum The current object (for fluent API support)
     */
    public function removeAlbumArtist(ChildAlbumArtist $albumArtist)
    {
        if ($this->getAlbumArtists()->contains($albumArtist)) {
            $pos = $this->collAlbumArtists->search($albumArtist);
            $this->collAlbumArtists->remove($pos);
            if (null === $this->albumArtistsScheduledForDeletion) {
                $this->albumArtistsScheduledForDeletion = clone $this->collAlbumArtists;
                $this->albumArtistsScheduledForDeletion->clear();
            }
            $this->albumArtistsScheduledForDeletion[]= clone $albumArtist;
            $albumArtist->setAlbum(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Album is new, it will return
     * an empty collection; or if this Album has previously
     * been saved, it will retrieve related AlbumArtists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Album.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAlbumArtist[] List of ChildAlbumArtist objects
     */
    public function getAlbumArtistsJoinArtist(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAlbumArtistQuery::create(null, $criteria);
        $query->joinWith('Artist', $joinBehavior);

        return $this->getAlbumArtists($query, $con);
    }

    /**
     * Clears out the collAlbumLyrics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAlbumLyrics()
     */
    public function clearAlbumLyrics()
    {
        $this->collAlbumLyrics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAlbumLyrics collection loaded partially.
     */
    public function resetPartialAlbumLyrics($v = true)
    {
        $this->collAlbumLyricsPartial = $v;
    }

    /**
     * Initializes the collAlbumLyrics collection.
     *
     * By default this just sets the collAlbumLyrics collection to an empty array (like clearcollAlbumLyrics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAlbumLyrics($overrideExisting = true)
    {
        if (null !== $this->collAlbumLyrics && !$overrideExisting) {
            return;
        }

        $collectionClassName = AlbumLyricTableMap::getTableMap()->getCollectionClassName();

        $this->collAlbumLyrics = new $collectionClassName;
        $this->collAlbumLyrics->setModel('\Tekstove\ApiBundle\Model\AlbumLyric');
    }

    /**
     * Gets an array of ChildAlbumLyric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAlbum is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAlbumLyric[] List of ChildAlbumLyric objects
     * @throws PropelException
     */
    public function getAlbumLyrics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumLyricsPartial && !$this->isNew();
        if (null === $this->collAlbumLyrics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAlbumLyrics) {
                // return empty collection
                $this->initAlbumLyrics();
            } else {
                $collAlbumLyrics = ChildAlbumLyricQuery::create(null, $criteria)
                    ->filterByAlbum($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAlbumLyricsPartial && count($collAlbumLyrics)) {
                        $this->initAlbumLyrics(false);

                        foreach ($collAlbumLyrics as $obj) {
                            if (false == $this->collAlbumLyrics->contains($obj)) {
                                $this->collAlbumLyrics->append($obj);
                            }
                        }

                        $this->collAlbumLyricsPartial = true;
                    }

                    return $collAlbumLyrics;
                }

                if ($partial && $this->collAlbumLyrics) {
                    foreach ($this->collAlbumLyrics as $obj) {
                        if ($obj->isNew()) {
                            $collAlbumLyrics[] = $obj;
                        }
                    }
                }

                $this->collAlbumLyrics = $collAlbumLyrics;
                $this->collAlbumLyricsPartial = false;
            }
        }

        return $this->collAlbumLyrics;
    }

    /**
     * Sets a collection of ChildAlbumLyric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $albumLyrics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAlbum The current object (for fluent API support)
     */
    public function setAlbumLyrics(Collection $albumLyrics, ConnectionInterface $con = null)
    {
        /** @var ChildAlbumLyric[] $albumLyricsToDelete */
        $albumLyricsToDelete = $this->getAlbumLyrics(new Criteria(), $con)->diff($albumLyrics);


        $this->albumLyricsScheduledForDeletion = $albumLyricsToDelete;

        foreach ($albumLyricsToDelete as $albumLyricRemoved) {
            $albumLyricRemoved->setAlbum(null);
        }

        $this->collAlbumLyrics = null;
        foreach ($albumLyrics as $albumLyric) {
            $this->addAlbumLyric($albumLyric);
        }

        $this->collAlbumLyrics = $albumLyrics;
        $this->collAlbumLyricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AlbumLyric objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AlbumLyric objects.
     * @throws PropelException
     */
    public function countAlbumLyrics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumLyricsPartial && !$this->isNew();
        if (null === $this->collAlbumLyrics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAlbumLyrics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAlbumLyrics());
            }

            $query = ChildAlbumLyricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAlbum($this)
                ->count($con);
        }

        return count($this->collAlbumLyrics);
    }

    /**
     * Method called to associate a ChildAlbumLyric object to this object
     * through the ChildAlbumLyric foreign key attribute.
     *
     * @param  ChildAlbumLyric $l ChildAlbumLyric
     * @return $this|\Tekstove\ApiBundle\Model\Album The current object (for fluent API support)
     */
    public function addAlbumLyric(ChildAlbumLyric $l)
    {
        if ($this->collAlbumLyrics === null) {
            $this->initAlbumLyrics();
            $this->collAlbumLyricsPartial = true;
        }

        if (!$this->collAlbumLyrics->contains($l)) {
            $this->doAddAlbumLyric($l);

            if ($this->albumLyricsScheduledForDeletion and $this->albumLyricsScheduledForDeletion->contains($l)) {
                $this->albumLyricsScheduledForDeletion->remove($this->albumLyricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAlbumLyric $albumLyric The ChildAlbumLyric object to add.
     */
    protected function doAddAlbumLyric(ChildAlbumLyric $albumLyric)
    {
        $this->collAlbumLyrics[]= $albumLyric;
        $albumLyric->setAlbum($this);
    }

    /**
     * @param  ChildAlbumLyric $albumLyric The ChildAlbumLyric object to remove.
     * @return $this|ChildAlbum The current object (for fluent API support)
     */
    public function removeAlbumLyric(ChildAlbumLyric $albumLyric)
    {
        if ($this->getAlbumLyrics()->contains($albumLyric)) {
            $pos = $this->collAlbumLyrics->search($albumLyric);
            $this->collAlbumLyrics->remove($pos);
            if (null === $this->albumLyricsScheduledForDeletion) {
                $this->albumLyricsScheduledForDeletion = clone $this->collAlbumLyrics;
                $this->albumLyricsScheduledForDeletion->clear();
            }
            $this->albumLyricsScheduledForDeletion[]= clone $albumLyric;
            $albumLyric->setAlbum(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Album is new, it will return
     * an empty collection; or if this Album has previously
     * been saved, it will retrieve related AlbumLyrics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Album.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAlbumLyric[] List of ChildAlbumLyric objects
     */
    public function getAlbumLyricsJoinLyric(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAlbumLyricQuery::create(null, $criteria);
        $query->joinWith('Lyric', $joinBehavior);

        return $this->getAlbumLyrics($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aUser) {
            $this->aUser->removeAlbum($this);
        }
        $this->id = null;
        $this->name = null;
        $this->year = null;
        $this->image = null;
        $this->user_id = null;
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
            if ($this->collAlbumArtists) {
                foreach ($this->collAlbumArtists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAlbumLyrics) {
                foreach ($this->collAlbumLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAlbumArtists = null;
        $this->collAlbumLyrics = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AlbumTableMap::DEFAULT_STRING_FORMAT);
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
