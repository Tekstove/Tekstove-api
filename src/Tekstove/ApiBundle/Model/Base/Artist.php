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
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tekstove\ApiBundle\Model\AlbumArtist as ChildAlbumArtist;
use Tekstove\ApiBundle\Model\AlbumArtistQuery as ChildAlbumArtistQuery;
use Tekstove\ApiBundle\Model\Artist as ChildArtist;
use Tekstove\ApiBundle\Model\ArtistQuery as ChildArtistQuery;
use Tekstove\ApiBundle\Model\Lyric as ChildLyric;
use Tekstove\ApiBundle\Model\LyricQuery as ChildLyricQuery;
use Tekstove\ApiBundle\Model\User as ChildUser;
use Tekstove\ApiBundle\Model\UserQuery as ChildUserQuery;
use Tekstove\ApiBundle\Model\Artist\ArtistLyric;
use Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery;
use Tekstove\ApiBundle\Model\Artist\Base\ArtistLyric as BaseArtistLyric;
use Tekstove\ApiBundle\Model\Artist\Map\ArtistLyricTableMap;
use Tekstove\ApiBundle\Model\Map\AlbumArtistTableMap;
use Tekstove\ApiBundle\Model\Map\ArtistTableMap;

/**
 * Base class that represents a row from the 'artist' table.
 *
 *
 *
 * @package    propel.generator.src.Tekstove.ApiBundle.Model.Base
 */
abstract class Artist implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Map\\ArtistTableMap';


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
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the forbidden field.
     *
     * @var        int
     */
    protected $forbidden;

    /**
     * The value for the about field.
     *
     * @var        string
     */
    protected $about;

    /**
     * @var        ChildUser
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ArtistLyric[] Collection to store aggregation of ArtistLyric objects.
     */
    protected $collArtistLyrics;
    protected $collArtistLyricsPartial;

    /**
     * @var        ObjectCollection|ChildAlbumArtist[] Collection to store aggregation of ChildAlbumArtist objects.
     */
    protected $collAlbumArtists;
    protected $collAlbumArtistsPartial;

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

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLyric[]
     */
    protected $lyricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ArtistLyric[]
     */
    protected $artistLyricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAlbumArtist[]
     */
    protected $albumArtistsScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Base\Artist object.
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
     * Compares this with another <code>Artist</code> instance.  If
     * <code>obj</code> is an instance of <code>Artist</code>, delegates to
     * <code>equals(Artist)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Artist The current object, for fluid interface
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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [forbidden] column value.
     *
     * @return int
     */
    public function getForbidden()
    {
        return $this->forbidden;
    }

    /**
     * Get the [about] column value.
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ArtistTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[ArtistTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[ArtistTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [forbidden] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function setForbidden($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->forbidden !== $v) {
            $this->forbidden = $v;
            $this->modifiedColumns[ArtistTableMap::COL_FORBIDDEN] = true;
        }

        return $this;
    } // setForbidden()

    /**
     * Set the value of [about] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function setAbout($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->about !== $v) {
            $this->about = $v;
            $this->modifiedColumns[ArtistTableMap::COL_ABOUT] = true;
        }

        return $this;
    } // setAbout()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ArtistTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ArtistTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ArtistTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ArtistTableMap::translateFieldName('Forbidden', TableMap::TYPE_PHPNAME, $indexType)];
            $this->forbidden = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ArtistTableMap::translateFieldName('About', TableMap::TYPE_PHPNAME, $indexType)];
            $this->about = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = ArtistTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\Artist'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(ArtistTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildArtistQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collArtistLyrics = null;

            $this->collAlbumArtists = null;

            $this->collLyrics = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Artist::setDeleted()
     * @see Artist::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArtistTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildArtistQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(ArtistTableMap::DATABASE_NAME);
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
                ArtistTableMap::addInstanceToPool($this);
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

            if ($this->lyricsScheduledForDeletion !== null) {
                if (!$this->lyricsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->lyricsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->lyricsScheduledForDeletion = null;
                }

            }

            if ($this->collLyrics) {
                foreach ($this->collLyrics as $lyric) {
                    if (!$lyric->isDeleted() && ($lyric->isNew() || $lyric->isModified())) {
                        $lyric->save($con);
                    }
                }
            }


            if ($this->artistLyricsScheduledForDeletion !== null) {
                if (!$this->artistLyricsScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery::create()
                        ->filterByPrimaryKeys($this->artistLyricsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->artistLyricsScheduledForDeletion = null;
                }
            }

            if ($this->collArtistLyrics !== null) {
                foreach ($this->collArtistLyrics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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

        $this->modifiedColumns[ArtistTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ArtistTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ArtistTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ArtistTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(ArtistTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(ArtistTableMap::COL_FORBIDDEN)) {
            $modifiedColumns[':p' . $index++]  = '`forbidden`';
        }
        if ($this->isColumnModified(ArtistTableMap::COL_ABOUT)) {
            $modifiedColumns[':p' . $index++]  = '`about`';
        }

        $sql = sprintf(
            'INSERT INTO `artist` (%s) VALUES (%s)',
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
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`forbidden`':
                        $stmt->bindValue($identifier, $this->forbidden, PDO::PARAM_INT);
                        break;
                    case '`about`':
                        $stmt->bindValue($identifier, $this->about, PDO::PARAM_STR);
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
        $pos = ArtistTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUserId();
                break;
            case 3:
                return $this->getForbidden();
                break;
            case 4:
                return $this->getAbout();
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

        if (isset($alreadyDumpedObjects['Artist'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Artist'][$this->hashCode()] = true;
        $keys = ArtistTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getUserId(),
            $keys[3] => $this->getForbidden(),
            $keys[4] => $this->getAbout(),
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
            if (null !== $this->collArtistLyrics) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'artistLyrics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'artist_lyrics';
                        break;
                    default:
                        $key = 'ArtistLyrics';
                }

                $result[$key] = $this->collArtistLyrics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Tekstove\ApiBundle\Model\Artist
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ArtistTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Tekstove\ApiBundle\Model\Artist
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
                $this->setUserId($value);
                break;
            case 3:
                $this->setForbidden($value);
                break;
            case 4:
                $this->setAbout($value);
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
        $keys = ArtistTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUserId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setForbidden($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAbout($arr[$keys[4]]);
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
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object, for fluid interface
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
        $criteria = new Criteria(ArtistTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ArtistTableMap::COL_ID)) {
            $criteria->add(ArtistTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ArtistTableMap::COL_NAME)) {
            $criteria->add(ArtistTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(ArtistTableMap::COL_USER_ID)) {
            $criteria->add(ArtistTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(ArtistTableMap::COL_FORBIDDEN)) {
            $criteria->add(ArtistTableMap::COL_FORBIDDEN, $this->forbidden);
        }
        if ($this->isColumnModified(ArtistTableMap::COL_ABOUT)) {
            $criteria->add(ArtistTableMap::COL_ABOUT, $this->about);
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
        $criteria = ChildArtistQuery::create();
        $criteria->add(ArtistTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\Artist (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setForbidden($this->getForbidden());
        $copyObj->setAbout($this->getAbout());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArtistLyrics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArtistLyric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAlbumArtists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAlbumArtist($relObj->copy($deepCopy));
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
     * @return \Tekstove\ApiBundle\Model\Artist Clone of current object.
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
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(ChildUser $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildUser object, it will not be re-added.
        if ($v !== null) {
            $v->addArtist($this);
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
                $this->aUser->addArtists($this);
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
        if ('ArtistLyric' == $relationName) {
            $this->initArtistLyrics();
            return;
        }
        if ('AlbumArtist' == $relationName) {
            $this->initAlbumArtists();
            return;
        }
    }

    /**
     * Clears out the collArtistLyrics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addArtistLyrics()
     */
    public function clearArtistLyrics()
    {
        $this->collArtistLyrics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collArtistLyrics collection loaded partially.
     */
    public function resetPartialArtistLyrics($v = true)
    {
        $this->collArtistLyricsPartial = $v;
    }

    /**
     * Initializes the collArtistLyrics collection.
     *
     * By default this just sets the collArtistLyrics collection to an empty array (like clearcollArtistLyrics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArtistLyrics($overrideExisting = true)
    {
        if (null !== $this->collArtistLyrics && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArtistLyricTableMap::getTableMap()->getCollectionClassName();

        $this->collArtistLyrics = new $collectionClassName;
        $this->collArtistLyrics->setModel('\Tekstove\ApiBundle\Model\Artist\ArtistLyric');
    }

    /**
     * Gets an array of ArtistLyric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildArtist is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ArtistLyric[] List of ArtistLyric objects
     * @throws PropelException
     */
    public function getArtistLyrics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistLyricsPartial && !$this->isNew();
        if (null === $this->collArtistLyrics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArtistLyrics) {
                // return empty collection
                $this->initArtistLyrics();
            } else {
                $collArtistLyrics = ArtistLyricQuery::create(null, $criteria)
                    ->filterByArtist($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArtistLyricsPartial && count($collArtistLyrics)) {
                        $this->initArtistLyrics(false);

                        foreach ($collArtistLyrics as $obj) {
                            if (false == $this->collArtistLyrics->contains($obj)) {
                                $this->collArtistLyrics->append($obj);
                            }
                        }

                        $this->collArtistLyricsPartial = true;
                    }

                    return $collArtistLyrics;
                }

                if ($partial && $this->collArtistLyrics) {
                    foreach ($this->collArtistLyrics as $obj) {
                        if ($obj->isNew()) {
                            $collArtistLyrics[] = $obj;
                        }
                    }
                }

                $this->collArtistLyrics = $collArtistLyrics;
                $this->collArtistLyricsPartial = false;
            }
        }

        return $this->collArtistLyrics;
    }

    /**
     * Sets a collection of ArtistLyric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $artistLyrics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildArtist The current object (for fluent API support)
     */
    public function setArtistLyrics(Collection $artistLyrics, ConnectionInterface $con = null)
    {
        /** @var ArtistLyric[] $artistLyricsToDelete */
        $artistLyricsToDelete = $this->getArtistLyrics(new Criteria(), $con)->diff($artistLyrics);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->artistLyricsScheduledForDeletion = clone $artistLyricsToDelete;

        foreach ($artistLyricsToDelete as $artistLyricRemoved) {
            $artistLyricRemoved->setArtist(null);
        }

        $this->collArtistLyrics = null;
        foreach ($artistLyrics as $artistLyric) {
            $this->addArtistLyric($artistLyric);
        }

        $this->collArtistLyrics = $artistLyrics;
        $this->collArtistLyricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseArtistLyric objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseArtistLyric objects.
     * @throws PropelException
     */
    public function countArtistLyrics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistLyricsPartial && !$this->isNew();
        if (null === $this->collArtistLyrics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArtistLyrics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArtistLyrics());
            }

            $query = ArtistLyricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByArtist($this)
                ->count($con);
        }

        return count($this->collArtistLyrics);
    }

    /**
     * Method called to associate a ArtistLyric object to this object
     * through the ArtistLyric foreign key attribute.
     *
     * @param  ArtistLyric $l ArtistLyric
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
     */
    public function addArtistLyric(ArtistLyric $l)
    {
        if ($this->collArtistLyrics === null) {
            $this->initArtistLyrics();
            $this->collArtistLyricsPartial = true;
        }

        if (!$this->collArtistLyrics->contains($l)) {
            $this->doAddArtistLyric($l);

            if ($this->artistLyricsScheduledForDeletion and $this->artistLyricsScheduledForDeletion->contains($l)) {
                $this->artistLyricsScheduledForDeletion->remove($this->artistLyricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ArtistLyric $artistLyric The ArtistLyric object to add.
     */
    protected function doAddArtistLyric(ArtistLyric $artistLyric)
    {
        $this->collArtistLyrics[]= $artistLyric;
        $artistLyric->setArtist($this);
    }

    /**
     * @param  ArtistLyric $artistLyric The ArtistLyric object to remove.
     * @return $this|ChildArtist The current object (for fluent API support)
     */
    public function removeArtistLyric(ArtistLyric $artistLyric)
    {
        if ($this->getArtistLyrics()->contains($artistLyric)) {
            $pos = $this->collArtistLyrics->search($artistLyric);
            $this->collArtistLyrics->remove($pos);
            if (null === $this->artistLyricsScheduledForDeletion) {
                $this->artistLyricsScheduledForDeletion = clone $this->collArtistLyrics;
                $this->artistLyricsScheduledForDeletion->clear();
            }
            $this->artistLyricsScheduledForDeletion[]= clone $artistLyric;
            $artistLyric->setArtist(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Artist is new, it will return
     * an empty collection; or if this Artist has previously
     * been saved, it will retrieve related ArtistLyrics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Artist.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ArtistLyric[] List of ArtistLyric objects
     */
    public function getArtistLyricsJoinLyric(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ArtistLyricQuery::create(null, $criteria);
        $query->joinWith('Lyric', $joinBehavior);

        return $this->getArtistLyrics($query, $con);
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
     * If this ChildArtist is new, it will return
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
                    ->filterByArtist($this)
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
     * @return $this|ChildArtist The current object (for fluent API support)
     */
    public function setAlbumArtists(Collection $albumArtists, ConnectionInterface $con = null)
    {
        /** @var ChildAlbumArtist[] $albumArtistsToDelete */
        $albumArtistsToDelete = $this->getAlbumArtists(new Criteria(), $con)->diff($albumArtists);


        $this->albumArtistsScheduledForDeletion = $albumArtistsToDelete;

        foreach ($albumArtistsToDelete as $albumArtistRemoved) {
            $albumArtistRemoved->setArtist(null);
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
                ->filterByArtist($this)
                ->count($con);
        }

        return count($this->collAlbumArtists);
    }

    /**
     * Method called to associate a ChildAlbumArtist object to this object
     * through the ChildAlbumArtist foreign key attribute.
     *
     * @param  ChildAlbumArtist $l ChildAlbumArtist
     * @return $this|\Tekstove\ApiBundle\Model\Artist The current object (for fluent API support)
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
        $albumArtist->setArtist($this);
    }

    /**
     * @param  ChildAlbumArtist $albumArtist The ChildAlbumArtist object to remove.
     * @return $this|ChildArtist The current object (for fluent API support)
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
            $albumArtist->setArtist(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Artist is new, it will return
     * an empty collection; or if this Artist has previously
     * been saved, it will retrieve related AlbumArtists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Artist.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAlbumArtist[] List of ChildAlbumArtist objects
     */
    public function getAlbumArtistsJoinAlbum(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAlbumArtistQuery::create(null, $criteria);
        $query->joinWith('Album', $joinBehavior);

        return $this->getAlbumArtists($query, $con);
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
        $collectionClassName = ArtistLyricTableMap::getTableMap()->getCollectionClassName();

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
     * to the current object by way of the artist_lyric cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildArtist is new, it will return
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
                    ->filterByArtist($this);
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
     * to the current object by way of the artist_lyric cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $lyrics A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildArtist The current object (for fluent API support)
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
     * to the current object by way of the artist_lyric cross-reference table.
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
                    ->filterByArtist($this)
                    ->count($con);
            }
        } else {
            return count($this->collLyrics);
        }
    }

    /**
     * Associate a ChildLyric to this object
     * through the artist_lyric cross reference table.
     *
     * @param ChildLyric $lyric
     * @return ChildArtist The current object (for fluent API support)
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
        $artistLyric = new ArtistLyric();

        $artistLyric->setLyric($lyric);

        $artistLyric->setArtist($this);

        $this->addArtistLyric($artistLyric);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$lyric->isArtistsLoaded()) {
            $lyric->initArtists();
            $lyric->getArtists()->push($this);
        } elseif (!$lyric->getArtists()->contains($this)) {
            $lyric->getArtists()->push($this);
        }

    }

    /**
     * Remove lyric of this object
     * through the artist_lyric cross reference table.
     *
     * @param ChildLyric $lyric
     * @return ChildArtist The current object (for fluent API support)
     */
    public function removeLyric(ChildLyric $lyric)
    {
        if ($this->getLyrics()->contains($lyric)) {
            $artistLyric = new ArtistLyric();
            $artistLyric->setLyric($lyric);
            if ($lyric->isArtistsLoaded()) {
                //remove the back reference if available
                $lyric->getArtists()->removeObject($this);
            }

            $artistLyric->setArtist($this);
            $this->removeArtistLyric(clone $artistLyric);
            $artistLyric->clear();

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
        if (null !== $this->aUser) {
            $this->aUser->removeArtist($this);
        }
        $this->id = null;
        $this->name = null;
        $this->user_id = null;
        $this->forbidden = null;
        $this->about = null;
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
            if ($this->collArtistLyrics) {
                foreach ($this->collArtistLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAlbumArtists) {
                foreach ($this->collAlbumArtists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLyrics) {
                foreach ($this->collLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArtistLyrics = null;
        $this->collAlbumArtists = null;
        $this->collLyrics = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ArtistTableMap::DEFAULT_STRING_FORMAT);
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      ValidatorInterface|null $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
     */
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = new RecursiveValidator(
                new ExecutionContextFactory(new IdentityTranslator()),
                new LazyLoadingMetadataFactory(new StaticMethodLoader()),
                new ConstraintValidatorFactory()
            );
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            // If validate() method exists, the validate-behavior is configured for related object
            if (method_exists($this->aUser, 'validate')) {
                if (!$this->aUser->validate($validator)) {
                    $failureMap->addAll($this->aUser->getValidationFailures());
                }
            }

            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collArtistLyrics) {
                foreach ($this->collArtistLyrics as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collAlbumArtists) {
                foreach ($this->collAlbumArtists as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }

            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return     object ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
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
