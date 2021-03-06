<?php

namespace Tekstove\ApiBundle\Model\Base;

use \DateTime;
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
use Propel\Runtime\Util\PropelDateTime;
use Propel\Runtime\Validator\Constraints\Unique;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tekstove\ApiBundle\Model\Album as ChildAlbum;
use Tekstove\ApiBundle\Model\AlbumQuery as ChildAlbumQuery;
use Tekstove\ApiBundle\Model\Artist as ChildArtist;
use Tekstove\ApiBundle\Model\ArtistQuery as ChildArtistQuery;
use Tekstove\ApiBundle\Model\Lyric as ChildLyric;
use Tekstove\ApiBundle\Model\LyricQuery as ChildLyricQuery;
use Tekstove\ApiBundle\Model\User as ChildUser;
use Tekstove\ApiBundle\Model\UserQuery as ChildUserQuery;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUser;
use Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery;
use Tekstove\ApiBundle\Model\Acl\Base\PermissionGroupUser as BasePermissionGroupUser;
use Tekstove\ApiBundle\Model\Acl\Map\PermissionGroupUserTableMap;
use Tekstove\ApiBundle\Model\Chat\Message;
use Tekstove\ApiBundle\Model\Chat\MessageQuery;
use Tekstove\ApiBundle\Model\Chat\Online;
use Tekstove\ApiBundle\Model\Chat\OnlineQuery;
use Tekstove\ApiBundle\Model\Chat\Base\Message as BaseMessage;
use Tekstove\ApiBundle\Model\Chat\Base\Online as BaseOnline;
use Tekstove\ApiBundle\Model\Chat\Map\MessageTableMap;
use Tekstove\ApiBundle\Model\Chat\Map\OnlineTableMap;
use Tekstove\ApiBundle\Model\Forum\Post;
use Tekstove\ApiBundle\Model\Forum\PostQuery;
use Tekstove\ApiBundle\Model\Forum\Topic;
use Tekstove\ApiBundle\Model\Forum\TopicQuery;
use Tekstove\ApiBundle\Model\Forum\Base\Post as BasePost;
use Tekstove\ApiBundle\Model\Forum\Base\Topic as BaseTopic;
use Tekstove\ApiBundle\Model\Forum\Map\PostTableMap;
use Tekstove\ApiBundle\Model\Forum\Map\TopicTableMap;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery;
use Tekstove\ApiBundle\Model\Lyric\LyricVote;
use Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricTranslation as BaseLyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricVote as BaseLyricVote;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricTranslationTableMap;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricVoteTableMap;
use Tekstove\ApiBundle\Model\Map\AlbumTableMap;
use Tekstove\ApiBundle\Model\Map\ArtistTableMap;
use Tekstove\ApiBundle\Model\Map\LyricTableMap;
use Tekstove\ApiBundle\Model\Map\UserTableMap;
use Tekstove\ApiBundle\Model\User\Pm;
use Tekstove\ApiBundle\Model\User\PmQuery;
use Tekstove\ApiBundle\Model\User\Base\Pm as BasePm;
use Tekstove\ApiBundle\Model\User\Map\PmTableMap;

/**
 * Base class that represents a row from the 'user' table.
 *
 *
 *
 * @package    propel.generator.src.Tekstove.ApiBundle.Model.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Map\\UserTableMap';


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
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * The value for the api_key field.
     *
     * @var        string
     */
    protected $api_key;

    /**
     * The value for the mail field.
     *
     * @var        string
     */
    protected $mail;

    /**
     * The value for the avatar field.
     *
     * @var        string
     */
    protected $avatar;

    /**
     * The value for the about field.
     *
     * @var        string
     */
    protected $about;

    /**
     * The value for the autoplay field.
     *
     * @var        int
     */
    protected $autoplay;

    /**
     * The value for the terms_accepted field.
     *
     * @var        DateTime
     */
    protected $terms_accepted;

    /**
     * The value for the status field.
     *
     * @var        int
     */
    protected $status;

    /**
     * @var        ObjectCollection|Pm[] Collection to store aggregation of Pm objects.
     */
    protected $collPmsRelatedByUserTo;
    protected $collPmsRelatedByUserToPartial;

    /**
     * @var        ObjectCollection|Pm[] Collection to store aggregation of Pm objects.
     */
    protected $collPmsRelatedByUserFrom;
    protected $collPmsRelatedByUserFromPartial;

    /**
     * @var        ObjectCollection|PermissionGroupUser[] Collection to store aggregation of PermissionGroupUser objects.
     */
    protected $collPermissionGroupUsers;
    protected $collPermissionGroupUsersPartial;

    /**
     * @var        ObjectCollection|ChildLyric[] Collection to store aggregation of ChildLyric objects.
     */
    protected $collLyrics;
    protected $collLyricsPartial;

    /**
     * @var        ObjectCollection|LyricTranslation[] Collection to store aggregation of LyricTranslation objects.
     */
    protected $collLyricTranslations;
    protected $collLyricTranslationsPartial;

    /**
     * @var        ObjectCollection|LyricVote[] Collection to store aggregation of LyricVote objects.
     */
    protected $collLyricVotes;
    protected $collLyricVotesPartial;

    /**
     * @var        ObjectCollection|ChildArtist[] Collection to store aggregation of ChildArtist objects.
     */
    protected $collArtists;
    protected $collArtistsPartial;

    /**
     * @var        ObjectCollection|ChildAlbum[] Collection to store aggregation of ChildAlbum objects.
     */
    protected $collAlbums;
    protected $collAlbumsPartial;

    /**
     * @var        ObjectCollection|Topic[] Collection to store aggregation of Topic objects.
     */
    protected $collTopics;
    protected $collTopicsPartial;

    /**
     * @var        ObjectCollection|Post[] Collection to store aggregation of Post objects.
     */
    protected $collPosts;
    protected $collPostsPartial;

    /**
     * @var        ObjectCollection|Message[] Collection to store aggregation of Message objects.
     */
    protected $collMessages;
    protected $collMessagesPartial;

    /**
     * @var        ObjectCollection|Online[] Collection to store aggregation of Online objects.
     */
    protected $collOnlines;
    protected $collOnlinesPartial;

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
     * @var ObjectCollection|Pm[]
     */
    protected $pmsRelatedByUserToScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Pm[]
     */
    protected $pmsRelatedByUserFromScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|PermissionGroupUser[]
     */
    protected $permissionGroupUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLyric[]
     */
    protected $lyricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|LyricTranslation[]
     */
    protected $lyricTranslationsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|LyricVote[]
     */
    protected $lyricVotesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArtist[]
     */
    protected $artistsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAlbum[]
     */
    protected $albumsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Topic[]
     */
    protected $topicsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Post[]
     */
    protected $postsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Message[]
     */
    protected $messagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|Online[]
     */
    protected $onlinesScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Base\User object.
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
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|User The current object, for fluid interface
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
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [api_key] column value.
     *
     * @return string
     */
    public function getapiKey()
    {
        return $this->api_key;
    }

    /**
     * Get the [mail] column value.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Get the [avatar] column value.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
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
     * Get the [autoplay] column value.
     *
     * @return int
     */
    public function getAutoplay()
    {
        return $this->autoplay;
    }

    /**
     * Get the [optionally formatted] temporal [terms_accepted] column value.
     *
     *
     * @param      string|null $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function gettermsAccepted($format = NULL)
    {
        if ($format === null) {
            return $this->terms_accepted;
        } else {
            return $this->terms_accepted instanceof \DateTimeInterface ? $this->terms_accepted->format($format) : null;
        }
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getstatus()
    {
        return $this->status;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [api_key] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setapiKey($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->api_key !== $v) {
            $this->api_key = $v;
            $this->modifiedColumns[UserTableMap::COL_API_KEY] = true;
        }

        return $this;
    } // setapiKey()

    /**
     * Set the value of [mail] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setMail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mail !== $v) {
            $this->mail = $v;
            $this->modifiedColumns[UserTableMap::COL_MAIL] = true;
        }

        return $this;
    } // setMail()

    /**
     * Set the value of [avatar] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setAvatar($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->avatar !== $v) {
            $this->avatar = $v;
            $this->modifiedColumns[UserTableMap::COL_AVATAR] = true;
        }

        return $this;
    } // setAvatar()

    /**
     * Set the value of [about] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setAbout($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->about !== $v) {
            $this->about = $v;
            $this->modifiedColumns[UserTableMap::COL_ABOUT] = true;
        }

        return $this;
    } // setAbout()

    /**
     * Set the value of [autoplay] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setAutoplay($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->autoplay !== $v) {
            $this->autoplay = $v;
            $this->modifiedColumns[UserTableMap::COL_AUTOPLAY] = true;
        }

        return $this;
    } // setAutoplay()

    /**
     * Sets the value of [terms_accepted] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function settermsAccepted($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->terms_accepted !== null || $dt !== null) {
            if ($this->terms_accepted === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->terms_accepted->format("Y-m-d H:i:s.u")) {
                $this->terms_accepted = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_TERMS_ACCEPTED] = true;
            }
        } // if either are not null

        return $this;
    } // settermsAccepted()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function setstatus($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[UserTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setstatus()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('apiKey', TableMap::TYPE_PHPNAME, $indexType)];
            $this->api_key = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Mail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mail = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Avatar', TableMap::TYPE_PHPNAME, $indexType)];
            $this->avatar = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('About', TableMap::TYPE_PHPNAME, $indexType)];
            $this->about = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('Autoplay', TableMap::TYPE_PHPNAME, $indexType)];
            $this->autoplay = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('termsAccepted', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->terms_accepted = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('status', TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\User'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPmsRelatedByUserTo = null;

            $this->collPmsRelatedByUserFrom = null;

            $this->collPermissionGroupUsers = null;

            $this->collLyrics = null;

            $this->collLyricTranslations = null;

            $this->collLyricVotes = null;

            $this->collArtists = null;

            $this->collAlbums = null;

            $this->collTopics = null;

            $this->collPosts = null;

            $this->collMessages = null;

            $this->collOnlines = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
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
                UserTableMap::addInstanceToPool($this);
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

            if ($this->pmsRelatedByUserToScheduledForDeletion !== null) {
                if (!$this->pmsRelatedByUserToScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\User\PmQuery::create()
                        ->filterByPrimaryKeys($this->pmsRelatedByUserToScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pmsRelatedByUserToScheduledForDeletion = null;
                }
            }

            if ($this->collPmsRelatedByUserTo !== null) {
                foreach ($this->collPmsRelatedByUserTo as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pmsRelatedByUserFromScheduledForDeletion !== null) {
                if (!$this->pmsRelatedByUserFromScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\User\PmQuery::create()
                        ->filterByPrimaryKeys($this->pmsRelatedByUserFromScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->pmsRelatedByUserFromScheduledForDeletion = null;
                }
            }

            if ($this->collPmsRelatedByUserFrom !== null) {
                foreach ($this->collPmsRelatedByUserFrom as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->permissionGroupUsersScheduledForDeletion !== null) {
                if (!$this->permissionGroupUsersScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\Acl\PermissionGroupUserQuery::create()
                        ->filterByPrimaryKeys($this->permissionGroupUsersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->permissionGroupUsersScheduledForDeletion = null;
                }
            }

            if ($this->collPermissionGroupUsers !== null) {
                foreach ($this->collPermissionGroupUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lyricsScheduledForDeletion !== null) {
                if (!$this->lyricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->lyricsScheduledForDeletion as $lyric) {
                        // need to save related object because we set the relation to null
                        $lyric->save($con);
                    }
                    $this->lyricsScheduledForDeletion = null;
                }
            }

            if ($this->collLyrics !== null) {
                foreach ($this->collLyrics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lyricTranslationsScheduledForDeletion !== null) {
                if (!$this->lyricTranslationsScheduledForDeletion->isEmpty()) {
                    foreach ($this->lyricTranslationsScheduledForDeletion as $lyricTranslation) {
                        // need to save related object because we set the relation to null
                        $lyricTranslation->save($con);
                    }
                    $this->lyricTranslationsScheduledForDeletion = null;
                }
            }

            if ($this->collLyricTranslations !== null) {
                foreach ($this->collLyricTranslations as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->lyricVotesScheduledForDeletion !== null) {
                if (!$this->lyricVotesScheduledForDeletion->isEmpty()) {
                    foreach ($this->lyricVotesScheduledForDeletion as $lyricVote) {
                        // need to save related object because we set the relation to null
                        $lyricVote->save($con);
                    }
                    $this->lyricVotesScheduledForDeletion = null;
                }
            }

            if ($this->collLyricVotes !== null) {
                foreach ($this->collLyricVotes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->artistsScheduledForDeletion !== null) {
                if (!$this->artistsScheduledForDeletion->isEmpty()) {
                    foreach ($this->artistsScheduledForDeletion as $artist) {
                        // need to save related object because we set the relation to null
                        $artist->save($con);
                    }
                    $this->artistsScheduledForDeletion = null;
                }
            }

            if ($this->collArtists !== null) {
                foreach ($this->collArtists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->albumsScheduledForDeletion !== null) {
                if (!$this->albumsScheduledForDeletion->isEmpty()) {
                    foreach ($this->albumsScheduledForDeletion as $album) {
                        // need to save related object because we set the relation to null
                        $album->save($con);
                    }
                    $this->albumsScheduledForDeletion = null;
                }
            }

            if ($this->collAlbums !== null) {
                foreach ($this->collAlbums as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->topicsScheduledForDeletion !== null) {
                if (!$this->topicsScheduledForDeletion->isEmpty()) {
                    foreach ($this->topicsScheduledForDeletion as $topic) {
                        // need to save related object because we set the relation to null
                        $topic->save($con);
                    }
                    $this->topicsScheduledForDeletion = null;
                }
            }

            if ($this->collTopics !== null) {
                foreach ($this->collTopics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->postsScheduledForDeletion !== null) {
                if (!$this->postsScheduledForDeletion->isEmpty()) {
                    foreach ($this->postsScheduledForDeletion as $post) {
                        // need to save related object because we set the relation to null
                        $post->save($con);
                    }
                    $this->postsScheduledForDeletion = null;
                }
            }

            if ($this->collPosts !== null) {
                foreach ($this->collPosts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->messagesScheduledForDeletion !== null) {
                if (!$this->messagesScheduledForDeletion->isEmpty()) {
                    foreach ($this->messagesScheduledForDeletion as $message) {
                        // need to save related object because we set the relation to null
                        $message->save($con);
                    }
                    $this->messagesScheduledForDeletion = null;
                }
            }

            if ($this->collMessages !== null) {
                foreach ($this->collMessages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->onlinesScheduledForDeletion !== null) {
                if (!$this->onlinesScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\Chat\OnlineQuery::create()
                        ->filterByPrimaryKeys($this->onlinesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->onlinesScheduledForDeletion = null;
                }
            }

            if ($this->collOnlines !== null) {
                foreach ($this->collOnlines as $referrerFK) {
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

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`username`';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`password`';
        }
        if ($this->isColumnModified(UserTableMap::COL_API_KEY)) {
            $modifiedColumns[':p' . $index++]  = '`api_key`';
        }
        if ($this->isColumnModified(UserTableMap::COL_MAIL)) {
            $modifiedColumns[':p' . $index++]  = '`mail`';
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR)) {
            $modifiedColumns[':p' . $index++]  = '`avatar`';
        }
        if ($this->isColumnModified(UserTableMap::COL_ABOUT)) {
            $modifiedColumns[':p' . $index++]  = '`about`';
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTOPLAY)) {
            $modifiedColumns[':p' . $index++]  = '`autoplay`';
        }
        if ($this->isColumnModified(UserTableMap::COL_TERMS_ACCEPTED)) {
            $modifiedColumns[':p' . $index++]  = '`terms_accepted`';
        }
        if ($this->isColumnModified(UserTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }

        $sql = sprintf(
            'INSERT INTO `user` (%s) VALUES (%s)',
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
                    case '`username`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`password`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case '`api_key`':
                        $stmt->bindValue($identifier, $this->api_key, PDO::PARAM_STR);
                        break;
                    case '`mail`':
                        $stmt->bindValue($identifier, $this->mail, PDO::PARAM_STR);
                        break;
                    case '`avatar`':
                        $stmt->bindValue($identifier, $this->avatar, PDO::PARAM_STR);
                        break;
                    case '`about`':
                        $stmt->bindValue($identifier, $this->about, PDO::PARAM_STR);
                        break;
                    case '`autoplay`':
                        $stmt->bindValue($identifier, $this->autoplay, PDO::PARAM_INT);
                        break;
                    case '`terms_accepted`':
                        $stmt->bindValue($identifier, $this->terms_accepted ? $this->terms_accepted->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
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
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUsername();
                break;
            case 2:
                return $this->getPassword();
                break;
            case 3:
                return $this->getapiKey();
                break;
            case 4:
                return $this->getMail();
                break;
            case 5:
                return $this->getAvatar();
                break;
            case 6:
                return $this->getAbout();
                break;
            case 7:
                return $this->getAutoplay();
                break;
            case 8:
                return $this->gettermsAccepted();
                break;
            case 9:
                return $this->getstatus();
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

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
            $keys[3] => $this->getapiKey(),
            $keys[4] => $this->getMail(),
            $keys[5] => $this->getAvatar(),
            $keys[6] => $this->getAbout(),
            $keys[7] => $this->getAutoplay(),
            $keys[8] => $this->gettermsAccepted(),
            $keys[9] => $this->getstatus(),
        );
        if ($result[$keys[8]] instanceof \DateTimeInterface) {
            $result[$keys[8]] = $result[$keys[8]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPmsRelatedByUserTo) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'pms';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pms';
                        break;
                    default:
                        $key = 'Pms';
                }

                $result[$key] = $this->collPmsRelatedByUserTo->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPmsRelatedByUserFrom) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'pms';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pms';
                        break;
                    default:
                        $key = 'Pms';
                }

                $result[$key] = $this->collPmsRelatedByUserFrom->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->collLyrics) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lyrics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lyrics';
                        break;
                    default:
                        $key = 'Lyrics';
                }

                $result[$key] = $this->collLyrics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLyricTranslations) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lyricTranslations';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lyric_translations';
                        break;
                    default:
                        $key = 'LyricTranslations';
                }

                $result[$key] = $this->collLyricTranslations->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collLyricVotes) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'lyricVotes';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'lyric_votes';
                        break;
                    default:
                        $key = 'LyricVotes';
                }

                $result[$key] = $this->collLyricVotes->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collArtists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'artists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'artists';
                        break;
                    default:
                        $key = 'Artists';
                }

                $result[$key] = $this->collArtists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAlbums) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'albums';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'albums';
                        break;
                    default:
                        $key = 'Albums';
                }

                $result[$key] = $this->collAlbums->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTopics) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'topics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'forum_topics';
                        break;
                    default:
                        $key = 'Topics';
                }

                $result[$key] = $this->collTopics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPosts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'posts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'forum_posts';
                        break;
                    default:
                        $key = 'Posts';
                }

                $result[$key] = $this->collPosts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMessages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'messages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chats';
                        break;
                    default:
                        $key = 'Messages';
                }

                $result[$key] = $this->collMessages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collOnlines) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'onlines';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'chat_onlines';
                        break;
                    default:
                        $key = 'Onlines';
                }

                $result[$key] = $this->collOnlines->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\Tekstove\ApiBundle\Model\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Tekstove\ApiBundle\Model\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
                break;
            case 3:
                $this->setapiKey($value);
                break;
            case 4:
                $this->setMail($value);
                break;
            case 5:
                $this->setAvatar($value);
                break;
            case 6:
                $this->setAbout($value);
                break;
            case 7:
                $this->setAutoplay($value);
                break;
            case 8:
                $this->settermsAccepted($value);
                break;
            case 9:
                $this->setstatus($value);
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
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setapiKey($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setMail($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAvatar($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setAbout($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setAutoplay($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->settermsAccepted($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setstatus($arr[$keys[9]]);
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
     * @return $this|\Tekstove\ApiBundle\Model\User The current object, for fluid interface
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
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_API_KEY)) {
            $criteria->add(UserTableMap::COL_API_KEY, $this->api_key);
        }
        if ($this->isColumnModified(UserTableMap::COL_MAIL)) {
            $criteria->add(UserTableMap::COL_MAIL, $this->mail);
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR)) {
            $criteria->add(UserTableMap::COL_AVATAR, $this->avatar);
        }
        if ($this->isColumnModified(UserTableMap::COL_ABOUT)) {
            $criteria->add(UserTableMap::COL_ABOUT, $this->about);
        }
        if ($this->isColumnModified(UserTableMap::COL_AUTOPLAY)) {
            $criteria->add(UserTableMap::COL_AUTOPLAY, $this->autoplay);
        }
        if ($this->isColumnModified(UserTableMap::COL_TERMS_ACCEPTED)) {
            $criteria->add(UserTableMap::COL_TERMS_ACCEPTED, $this->terms_accepted);
        }
        if ($this->isColumnModified(UserTableMap::COL_STATUS)) {
            $criteria->add(UserTableMap::COL_STATUS, $this->status);
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
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setapiKey($this->getapiKey());
        $copyObj->setMail($this->getMail());
        $copyObj->setAvatar($this->getAvatar());
        $copyObj->setAbout($this->getAbout());
        $copyObj->setAutoplay($this->getAutoplay());
        $copyObj->settermsAccepted($this->gettermsAccepted());
        $copyObj->setstatus($this->getstatus());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPmsRelatedByUserTo() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPmRelatedByUserTo($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPmsRelatedByUserFrom() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPmRelatedByUserFrom($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPermissionGroupUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPermissionGroupUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLyrics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLyric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLyricTranslations() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLyricTranslation($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLyricVotes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLyricVote($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getArtists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArtist($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAlbums() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAlbum($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTopics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTopic($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPosts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPost($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMessages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMessage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getOnlines() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addOnline($relObj->copy($deepCopy));
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
     * @return \Tekstove\ApiBundle\Model\User Clone of current object.
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
        if ('PmRelatedByUserTo' == $relationName) {
            $this->initPmsRelatedByUserTo();
            return;
        }
        if ('PmRelatedByUserFrom' == $relationName) {
            $this->initPmsRelatedByUserFrom();
            return;
        }
        if ('PermissionGroupUser' == $relationName) {
            $this->initPermissionGroupUsers();
            return;
        }
        if ('Lyric' == $relationName) {
            $this->initLyrics();
            return;
        }
        if ('LyricTranslation' == $relationName) {
            $this->initLyricTranslations();
            return;
        }
        if ('LyricVote' == $relationName) {
            $this->initLyricVotes();
            return;
        }
        if ('Artist' == $relationName) {
            $this->initArtists();
            return;
        }
        if ('Album' == $relationName) {
            $this->initAlbums();
            return;
        }
        if ('Topic' == $relationName) {
            $this->initTopics();
            return;
        }
        if ('Post' == $relationName) {
            $this->initPosts();
            return;
        }
        if ('Message' == $relationName) {
            $this->initMessages();
            return;
        }
        if ('Online' == $relationName) {
            $this->initOnlines();
            return;
        }
    }

    /**
     * Clears out the collPmsRelatedByUserTo collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPmsRelatedByUserTo()
     */
    public function clearPmsRelatedByUserTo()
    {
        $this->collPmsRelatedByUserTo = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPmsRelatedByUserTo collection loaded partially.
     */
    public function resetPartialPmsRelatedByUserTo($v = true)
    {
        $this->collPmsRelatedByUserToPartial = $v;
    }

    /**
     * Initializes the collPmsRelatedByUserTo collection.
     *
     * By default this just sets the collPmsRelatedByUserTo collection to an empty array (like clearcollPmsRelatedByUserTo());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPmsRelatedByUserTo($overrideExisting = true)
    {
        if (null !== $this->collPmsRelatedByUserTo && !$overrideExisting) {
            return;
        }

        $collectionClassName = PmTableMap::getTableMap()->getCollectionClassName();

        $this->collPmsRelatedByUserTo = new $collectionClassName;
        $this->collPmsRelatedByUserTo->setModel('\Tekstove\ApiBundle\Model\User\Pm');
    }

    /**
     * Gets an array of Pm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Pm[] List of Pm objects
     * @throws PropelException
     */
    public function getPmsRelatedByUserTo(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPmsRelatedByUserToPartial && !$this->isNew();
        if (null === $this->collPmsRelatedByUserTo || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPmsRelatedByUserTo) {
                // return empty collection
                $this->initPmsRelatedByUserTo();
            } else {
                $collPmsRelatedByUserTo = PmQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserTo($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPmsRelatedByUserToPartial && count($collPmsRelatedByUserTo)) {
                        $this->initPmsRelatedByUserTo(false);

                        foreach ($collPmsRelatedByUserTo as $obj) {
                            if (false == $this->collPmsRelatedByUserTo->contains($obj)) {
                                $this->collPmsRelatedByUserTo->append($obj);
                            }
                        }

                        $this->collPmsRelatedByUserToPartial = true;
                    }

                    return $collPmsRelatedByUserTo;
                }

                if ($partial && $this->collPmsRelatedByUserTo) {
                    foreach ($this->collPmsRelatedByUserTo as $obj) {
                        if ($obj->isNew()) {
                            $collPmsRelatedByUserTo[] = $obj;
                        }
                    }
                }

                $this->collPmsRelatedByUserTo = $collPmsRelatedByUserTo;
                $this->collPmsRelatedByUserToPartial = false;
            }
        }

        return $this->collPmsRelatedByUserTo;
    }

    /**
     * Sets a collection of Pm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $pmsRelatedByUserTo A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPmsRelatedByUserTo(Collection $pmsRelatedByUserTo, ConnectionInterface $con = null)
    {
        /** @var Pm[] $pmsRelatedByUserToToDelete */
        $pmsRelatedByUserToToDelete = $this->getPmsRelatedByUserTo(new Criteria(), $con)->diff($pmsRelatedByUserTo);


        $this->pmsRelatedByUserToScheduledForDeletion = $pmsRelatedByUserToToDelete;

        foreach ($pmsRelatedByUserToToDelete as $pmRelatedByUserToRemoved) {
            $pmRelatedByUserToRemoved->setUserRelatedByUserTo(null);
        }

        $this->collPmsRelatedByUserTo = null;
        foreach ($pmsRelatedByUserTo as $pmRelatedByUserTo) {
            $this->addPmRelatedByUserTo($pmRelatedByUserTo);
        }

        $this->collPmsRelatedByUserTo = $pmsRelatedByUserTo;
        $this->collPmsRelatedByUserToPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePm objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePm objects.
     * @throws PropelException
     */
    public function countPmsRelatedByUserTo(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPmsRelatedByUserToPartial && !$this->isNew();
        if (null === $this->collPmsRelatedByUserTo || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPmsRelatedByUserTo) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPmsRelatedByUserTo());
            }

            $query = PmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserTo($this)
                ->count($con);
        }

        return count($this->collPmsRelatedByUserTo);
    }

    /**
     * Method called to associate a Pm object to this object
     * through the Pm foreign key attribute.
     *
     * @param  Pm $l Pm
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addPmRelatedByUserTo(Pm $l)
    {
        if ($this->collPmsRelatedByUserTo === null) {
            $this->initPmsRelatedByUserTo();
            $this->collPmsRelatedByUserToPartial = true;
        }

        if (!$this->collPmsRelatedByUserTo->contains($l)) {
            $this->doAddPmRelatedByUserTo($l);

            if ($this->pmsRelatedByUserToScheduledForDeletion and $this->pmsRelatedByUserToScheduledForDeletion->contains($l)) {
                $this->pmsRelatedByUserToScheduledForDeletion->remove($this->pmsRelatedByUserToScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Pm $pmRelatedByUserTo The Pm object to add.
     */
    protected function doAddPmRelatedByUserTo(Pm $pmRelatedByUserTo)
    {
        $this->collPmsRelatedByUserTo[]= $pmRelatedByUserTo;
        $pmRelatedByUserTo->setUserRelatedByUserTo($this);
    }

    /**
     * @param  Pm $pmRelatedByUserTo The Pm object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePmRelatedByUserTo(Pm $pmRelatedByUserTo)
    {
        if ($this->getPmsRelatedByUserTo()->contains($pmRelatedByUserTo)) {
            $pos = $this->collPmsRelatedByUserTo->search($pmRelatedByUserTo);
            $this->collPmsRelatedByUserTo->remove($pos);
            if (null === $this->pmsRelatedByUserToScheduledForDeletion) {
                $this->pmsRelatedByUserToScheduledForDeletion = clone $this->collPmsRelatedByUserTo;
                $this->pmsRelatedByUserToScheduledForDeletion->clear();
            }
            $this->pmsRelatedByUserToScheduledForDeletion[]= clone $pmRelatedByUserTo;
            $pmRelatedByUserTo->setUserRelatedByUserTo(null);
        }

        return $this;
    }

    /**
     * Clears out the collPmsRelatedByUserFrom collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPmsRelatedByUserFrom()
     */
    public function clearPmsRelatedByUserFrom()
    {
        $this->collPmsRelatedByUserFrom = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPmsRelatedByUserFrom collection loaded partially.
     */
    public function resetPartialPmsRelatedByUserFrom($v = true)
    {
        $this->collPmsRelatedByUserFromPartial = $v;
    }

    /**
     * Initializes the collPmsRelatedByUserFrom collection.
     *
     * By default this just sets the collPmsRelatedByUserFrom collection to an empty array (like clearcollPmsRelatedByUserFrom());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPmsRelatedByUserFrom($overrideExisting = true)
    {
        if (null !== $this->collPmsRelatedByUserFrom && !$overrideExisting) {
            return;
        }

        $collectionClassName = PmTableMap::getTableMap()->getCollectionClassName();

        $this->collPmsRelatedByUserFrom = new $collectionClassName;
        $this->collPmsRelatedByUserFrom->setModel('\Tekstove\ApiBundle\Model\User\Pm');
    }

    /**
     * Gets an array of Pm objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Pm[] List of Pm objects
     * @throws PropelException
     */
    public function getPmsRelatedByUserFrom(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPmsRelatedByUserFromPartial && !$this->isNew();
        if (null === $this->collPmsRelatedByUserFrom || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPmsRelatedByUserFrom) {
                // return empty collection
                $this->initPmsRelatedByUserFrom();
            } else {
                $collPmsRelatedByUserFrom = PmQuery::create(null, $criteria)
                    ->filterByUserRelatedByUserFrom($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPmsRelatedByUserFromPartial && count($collPmsRelatedByUserFrom)) {
                        $this->initPmsRelatedByUserFrom(false);

                        foreach ($collPmsRelatedByUserFrom as $obj) {
                            if (false == $this->collPmsRelatedByUserFrom->contains($obj)) {
                                $this->collPmsRelatedByUserFrom->append($obj);
                            }
                        }

                        $this->collPmsRelatedByUserFromPartial = true;
                    }

                    return $collPmsRelatedByUserFrom;
                }

                if ($partial && $this->collPmsRelatedByUserFrom) {
                    foreach ($this->collPmsRelatedByUserFrom as $obj) {
                        if ($obj->isNew()) {
                            $collPmsRelatedByUserFrom[] = $obj;
                        }
                    }
                }

                $this->collPmsRelatedByUserFrom = $collPmsRelatedByUserFrom;
                $this->collPmsRelatedByUserFromPartial = false;
            }
        }

        return $this->collPmsRelatedByUserFrom;
    }

    /**
     * Sets a collection of Pm objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $pmsRelatedByUserFrom A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPmsRelatedByUserFrom(Collection $pmsRelatedByUserFrom, ConnectionInterface $con = null)
    {
        /** @var Pm[] $pmsRelatedByUserFromToDelete */
        $pmsRelatedByUserFromToDelete = $this->getPmsRelatedByUserFrom(new Criteria(), $con)->diff($pmsRelatedByUserFrom);


        $this->pmsRelatedByUserFromScheduledForDeletion = $pmsRelatedByUserFromToDelete;

        foreach ($pmsRelatedByUserFromToDelete as $pmRelatedByUserFromRemoved) {
            $pmRelatedByUserFromRemoved->setUserRelatedByUserFrom(null);
        }

        $this->collPmsRelatedByUserFrom = null;
        foreach ($pmsRelatedByUserFrom as $pmRelatedByUserFrom) {
            $this->addPmRelatedByUserFrom($pmRelatedByUserFrom);
        }

        $this->collPmsRelatedByUserFrom = $pmsRelatedByUserFrom;
        $this->collPmsRelatedByUserFromPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePm objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePm objects.
     * @throws PropelException
     */
    public function countPmsRelatedByUserFrom(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPmsRelatedByUserFromPartial && !$this->isNew();
        if (null === $this->collPmsRelatedByUserFrom || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPmsRelatedByUserFrom) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPmsRelatedByUserFrom());
            }

            $query = PmQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUserRelatedByUserFrom($this)
                ->count($con);
        }

        return count($this->collPmsRelatedByUserFrom);
    }

    /**
     * Method called to associate a Pm object to this object
     * through the Pm foreign key attribute.
     *
     * @param  Pm $l Pm
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addPmRelatedByUserFrom(Pm $l)
    {
        if ($this->collPmsRelatedByUserFrom === null) {
            $this->initPmsRelatedByUserFrom();
            $this->collPmsRelatedByUserFromPartial = true;
        }

        if (!$this->collPmsRelatedByUserFrom->contains($l)) {
            $this->doAddPmRelatedByUserFrom($l);

            if ($this->pmsRelatedByUserFromScheduledForDeletion and $this->pmsRelatedByUserFromScheduledForDeletion->contains($l)) {
                $this->pmsRelatedByUserFromScheduledForDeletion->remove($this->pmsRelatedByUserFromScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Pm $pmRelatedByUserFrom The Pm object to add.
     */
    protected function doAddPmRelatedByUserFrom(Pm $pmRelatedByUserFrom)
    {
        $this->collPmsRelatedByUserFrom[]= $pmRelatedByUserFrom;
        $pmRelatedByUserFrom->setUserRelatedByUserFrom($this);
    }

    /**
     * @param  Pm $pmRelatedByUserFrom The Pm object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePmRelatedByUserFrom(Pm $pmRelatedByUserFrom)
    {
        if ($this->getPmsRelatedByUserFrom()->contains($pmRelatedByUserFrom)) {
            $pos = $this->collPmsRelatedByUserFrom->search($pmRelatedByUserFrom);
            $this->collPmsRelatedByUserFrom->remove($pos);
            if (null === $this->pmsRelatedByUserFromScheduledForDeletion) {
                $this->pmsRelatedByUserFromScheduledForDeletion = clone $this->collPmsRelatedByUserFrom;
                $this->pmsRelatedByUserFromScheduledForDeletion->clear();
            }
            $this->pmsRelatedByUserFromScheduledForDeletion[]= clone $pmRelatedByUserFrom;
            $pmRelatedByUserFrom->setUserRelatedByUserFrom(null);
        }

        return $this;
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

        $collectionClassName = PermissionGroupUserTableMap::getTableMap()->getCollectionClassName();

        $this->collPermissionGroupUsers = new $collectionClassName;
        $this->collPermissionGroupUsers->setModel('\Tekstove\ApiBundle\Model\Acl\PermissionGroupUser');
    }

    /**
     * Gets an array of PermissionGroupUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|PermissionGroupUser[] List of PermissionGroupUser objects
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
                $collPermissionGroupUsers = PermissionGroupUserQuery::create(null, $criteria)
                    ->filterByUser($this)
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
     * Sets a collection of PermissionGroupUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $permissionGroupUsers A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPermissionGroupUsers(Collection $permissionGroupUsers, ConnectionInterface $con = null)
    {
        /** @var PermissionGroupUser[] $permissionGroupUsersToDelete */
        $permissionGroupUsersToDelete = $this->getPermissionGroupUsers(new Criteria(), $con)->diff($permissionGroupUsers);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->permissionGroupUsersScheduledForDeletion = clone $permissionGroupUsersToDelete;

        foreach ($permissionGroupUsersToDelete as $permissionGroupUserRemoved) {
            $permissionGroupUserRemoved->setUser(null);
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
     * Returns the number of related BasePermissionGroupUser objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePermissionGroupUser objects.
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

            $query = PermissionGroupUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPermissionGroupUsers);
    }

    /**
     * Method called to associate a PermissionGroupUser object to this object
     * through the PermissionGroupUser foreign key attribute.
     *
     * @param  PermissionGroupUser $l PermissionGroupUser
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addPermissionGroupUser(PermissionGroupUser $l)
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
     * @param PermissionGroupUser $permissionGroupUser The PermissionGroupUser object to add.
     */
    protected function doAddPermissionGroupUser(PermissionGroupUser $permissionGroupUser)
    {
        $this->collPermissionGroupUsers[]= $permissionGroupUser;
        $permissionGroupUser->setUser($this);
    }

    /**
     * @param  PermissionGroupUser $permissionGroupUser The PermissionGroupUser object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePermissionGroupUser(PermissionGroupUser $permissionGroupUser)
    {
        if ($this->getPermissionGroupUsers()->contains($permissionGroupUser)) {
            $pos = $this->collPermissionGroupUsers->search($permissionGroupUser);
            $this->collPermissionGroupUsers->remove($pos);
            if (null === $this->permissionGroupUsersScheduledForDeletion) {
                $this->permissionGroupUsersScheduledForDeletion = clone $this->collPermissionGroupUsers;
                $this->permissionGroupUsersScheduledForDeletion->clear();
            }
            $this->permissionGroupUsersScheduledForDeletion[]= clone $permissionGroupUser;
            $permissionGroupUser->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related PermissionGroupUsers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|PermissionGroupUser[] List of PermissionGroupUser objects
     */
    public function getPermissionGroupUsersJoinPermissionGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PermissionGroupUserQuery::create(null, $criteria);
        $query->joinWith('PermissionGroup', $joinBehavior);

        return $this->getPermissionGroupUsers($query, $con);
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
     * Reset is the collLyrics collection loaded partially.
     */
    public function resetPartialLyrics($v = true)
    {
        $this->collLyricsPartial = $v;
    }

    /**
     * Initializes the collLyrics collection.
     *
     * By default this just sets the collLyrics collection to an empty array (like clearcollLyrics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLyrics($overrideExisting = true)
    {
        if (null !== $this->collLyrics && !$overrideExisting) {
            return;
        }

        $collectionClassName = LyricTableMap::getTableMap()->getCollectionClassName();

        $this->collLyrics = new $collectionClassName;
        $this->collLyrics->setModel('\Tekstove\ApiBundle\Model\Lyric');
    }

    /**
     * Gets an array of ChildLyric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildLyric[] List of ChildLyric objects
     * @throws PropelException
     */
    public function getLyrics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricsPartial && !$this->isNew();
        if (null === $this->collLyrics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLyrics) {
                // return empty collection
                $this->initLyrics();
            } else {
                $collLyrics = ChildLyricQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLyricsPartial && count($collLyrics)) {
                        $this->initLyrics(false);

                        foreach ($collLyrics as $obj) {
                            if (false == $this->collLyrics->contains($obj)) {
                                $this->collLyrics->append($obj);
                            }
                        }

                        $this->collLyricsPartial = true;
                    }

                    return $collLyrics;
                }

                if ($partial && $this->collLyrics) {
                    foreach ($this->collLyrics as $obj) {
                        if ($obj->isNew()) {
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
     * Sets a collection of ChildLyric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lyrics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLyrics(Collection $lyrics, ConnectionInterface $con = null)
    {
        /** @var ChildLyric[] $lyricsToDelete */
        $lyricsToDelete = $this->getLyrics(new Criteria(), $con)->diff($lyrics);


        $this->lyricsScheduledForDeletion = $lyricsToDelete;

        foreach ($lyricsToDelete as $lyricRemoved) {
            $lyricRemoved->setUser(null);
        }

        $this->collLyrics = null;
        foreach ($lyrics as $lyric) {
            $this->addLyric($lyric);
        }

        $this->collLyrics = $lyrics;
        $this->collLyricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Lyric objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Lyric objects.
     * @throws PropelException
     */
    public function countLyrics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricsPartial && !$this->isNew();
        if (null === $this->collLyrics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLyrics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLyrics());
            }

            $query = ChildLyricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLyrics);
    }

    /**
     * Method called to associate a ChildLyric object to this object
     * through the ChildLyric foreign key attribute.
     *
     * @param  ChildLyric $l ChildLyric
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addLyric(ChildLyric $l)
    {
        if ($this->collLyrics === null) {
            $this->initLyrics();
            $this->collLyricsPartial = true;
        }

        if (!$this->collLyrics->contains($l)) {
            $this->doAddLyric($l);

            if ($this->lyricsScheduledForDeletion and $this->lyricsScheduledForDeletion->contains($l)) {
                $this->lyricsScheduledForDeletion->remove($this->lyricsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildLyric $lyric The ChildLyric object to add.
     */
    protected function doAddLyric(ChildLyric $lyric)
    {
        $this->collLyrics[]= $lyric;
        $lyric->setUser($this);
    }

    /**
     * @param  ChildLyric $lyric The ChildLyric object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLyric(ChildLyric $lyric)
    {
        if ($this->getLyrics()->contains($lyric)) {
            $pos = $this->collLyrics->search($lyric);
            $this->collLyrics->remove($pos);
            if (null === $this->lyricsScheduledForDeletion) {
                $this->lyricsScheduledForDeletion = clone $this->collLyrics;
                $this->lyricsScheduledForDeletion->clear();
            }
            $this->lyricsScheduledForDeletion[]= $lyric;
            $lyric->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collLyricTranslations collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLyricTranslations()
     */
    public function clearLyricTranslations()
    {
        $this->collLyricTranslations = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLyricTranslations collection loaded partially.
     */
    public function resetPartialLyricTranslations($v = true)
    {
        $this->collLyricTranslationsPartial = $v;
    }

    /**
     * Initializes the collLyricTranslations collection.
     *
     * By default this just sets the collLyricTranslations collection to an empty array (like clearcollLyricTranslations());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLyricTranslations($overrideExisting = true)
    {
        if (null !== $this->collLyricTranslations && !$overrideExisting) {
            return;
        }

        $collectionClassName = LyricTranslationTableMap::getTableMap()->getCollectionClassName();

        $this->collLyricTranslations = new $collectionClassName;
        $this->collLyricTranslations->setModel('\Tekstove\ApiBundle\Model\Lyric\LyricTranslation');
    }

    /**
     * Gets an array of LyricTranslation objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|LyricTranslation[] List of LyricTranslation objects
     * @throws PropelException
     */
    public function getLyricTranslations(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricTranslationsPartial && !$this->isNew();
        if (null === $this->collLyricTranslations || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLyricTranslations) {
                // return empty collection
                $this->initLyricTranslations();
            } else {
                $collLyricTranslations = LyricTranslationQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLyricTranslationsPartial && count($collLyricTranslations)) {
                        $this->initLyricTranslations(false);

                        foreach ($collLyricTranslations as $obj) {
                            if (false == $this->collLyricTranslations->contains($obj)) {
                                $this->collLyricTranslations->append($obj);
                            }
                        }

                        $this->collLyricTranslationsPartial = true;
                    }

                    return $collLyricTranslations;
                }

                if ($partial && $this->collLyricTranslations) {
                    foreach ($this->collLyricTranslations as $obj) {
                        if ($obj->isNew()) {
                            $collLyricTranslations[] = $obj;
                        }
                    }
                }

                $this->collLyricTranslations = $collLyricTranslations;
                $this->collLyricTranslationsPartial = false;
            }
        }

        return $this->collLyricTranslations;
    }

    /**
     * Sets a collection of LyricTranslation objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lyricTranslations A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLyricTranslations(Collection $lyricTranslations, ConnectionInterface $con = null)
    {
        /** @var LyricTranslation[] $lyricTranslationsToDelete */
        $lyricTranslationsToDelete = $this->getLyricTranslations(new Criteria(), $con)->diff($lyricTranslations);


        $this->lyricTranslationsScheduledForDeletion = $lyricTranslationsToDelete;

        foreach ($lyricTranslationsToDelete as $lyricTranslationRemoved) {
            $lyricTranslationRemoved->setUser(null);
        }

        $this->collLyricTranslations = null;
        foreach ($lyricTranslations as $lyricTranslation) {
            $this->addLyricTranslation($lyricTranslation);
        }

        $this->collLyricTranslations = $lyricTranslations;
        $this->collLyricTranslationsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseLyricTranslation objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseLyricTranslation objects.
     * @throws PropelException
     */
    public function countLyricTranslations(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricTranslationsPartial && !$this->isNew();
        if (null === $this->collLyricTranslations || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLyricTranslations) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLyricTranslations());
            }

            $query = LyricTranslationQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLyricTranslations);
    }

    /**
     * Method called to associate a LyricTranslation object to this object
     * through the LyricTranslation foreign key attribute.
     *
     * @param  LyricTranslation $l LyricTranslation
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addLyricTranslation(LyricTranslation $l)
    {
        if ($this->collLyricTranslations === null) {
            $this->initLyricTranslations();
            $this->collLyricTranslationsPartial = true;
        }

        if (!$this->collLyricTranslations->contains($l)) {
            $this->doAddLyricTranslation($l);

            if ($this->lyricTranslationsScheduledForDeletion and $this->lyricTranslationsScheduledForDeletion->contains($l)) {
                $this->lyricTranslationsScheduledForDeletion->remove($this->lyricTranslationsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param LyricTranslation $lyricTranslation The LyricTranslation object to add.
     */
    protected function doAddLyricTranslation(LyricTranslation $lyricTranslation)
    {
        $this->collLyricTranslations[]= $lyricTranslation;
        $lyricTranslation->setUser($this);
    }

    /**
     * @param  LyricTranslation $lyricTranslation The LyricTranslation object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLyricTranslation(LyricTranslation $lyricTranslation)
    {
        if ($this->getLyricTranslations()->contains($lyricTranslation)) {
            $pos = $this->collLyricTranslations->search($lyricTranslation);
            $this->collLyricTranslations->remove($pos);
            if (null === $this->lyricTranslationsScheduledForDeletion) {
                $this->lyricTranslationsScheduledForDeletion = clone $this->collLyricTranslations;
                $this->lyricTranslationsScheduledForDeletion->clear();
            }
            $this->lyricTranslationsScheduledForDeletion[]= $lyricTranslation;
            $lyricTranslation->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related LyricTranslations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricTranslation[] List of LyricTranslation objects
     */
    public function getLyricTranslationsJoinLyric(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricTranslationQuery::create(null, $criteria);
        $query->joinWith('Lyric', $joinBehavior);

        return $this->getLyricTranslations($query, $con);
    }

    /**
     * Clears out the collLyricVotes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLyricVotes()
     */
    public function clearLyricVotes()
    {
        $this->collLyricVotes = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collLyricVotes collection loaded partially.
     */
    public function resetPartialLyricVotes($v = true)
    {
        $this->collLyricVotesPartial = $v;
    }

    /**
     * Initializes the collLyricVotes collection.
     *
     * By default this just sets the collLyricVotes collection to an empty array (like clearcollLyricVotes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initLyricVotes($overrideExisting = true)
    {
        if (null !== $this->collLyricVotes && !$overrideExisting) {
            return;
        }

        $collectionClassName = LyricVoteTableMap::getTableMap()->getCollectionClassName();

        $this->collLyricVotes = new $collectionClassName;
        $this->collLyricVotes->setModel('\Tekstove\ApiBundle\Model\Lyric\LyricVote');
    }

    /**
     * Gets an array of LyricVote objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|LyricVote[] List of LyricVote objects
     * @throws PropelException
     */
    public function getLyricVotes(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricVotesPartial && !$this->isNew();
        if (null === $this->collLyricVotes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collLyricVotes) {
                // return empty collection
                $this->initLyricVotes();
            } else {
                $collLyricVotes = LyricVoteQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collLyricVotesPartial && count($collLyricVotes)) {
                        $this->initLyricVotes(false);

                        foreach ($collLyricVotes as $obj) {
                            if (false == $this->collLyricVotes->contains($obj)) {
                                $this->collLyricVotes->append($obj);
                            }
                        }

                        $this->collLyricVotesPartial = true;
                    }

                    return $collLyricVotes;
                }

                if ($partial && $this->collLyricVotes) {
                    foreach ($this->collLyricVotes as $obj) {
                        if ($obj->isNew()) {
                            $collLyricVotes[] = $obj;
                        }
                    }
                }

                $this->collLyricVotes = $collLyricVotes;
                $this->collLyricVotesPartial = false;
            }
        }

        return $this->collLyricVotes;
    }

    /**
     * Sets a collection of LyricVote objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $lyricVotes A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setLyricVotes(Collection $lyricVotes, ConnectionInterface $con = null)
    {
        /** @var LyricVote[] $lyricVotesToDelete */
        $lyricVotesToDelete = $this->getLyricVotes(new Criteria(), $con)->diff($lyricVotes);


        $this->lyricVotesScheduledForDeletion = $lyricVotesToDelete;

        foreach ($lyricVotesToDelete as $lyricVoteRemoved) {
            $lyricVoteRemoved->setUser(null);
        }

        $this->collLyricVotes = null;
        foreach ($lyricVotes as $lyricVote) {
            $this->addLyricVote($lyricVote);
        }

        $this->collLyricVotes = $lyricVotes;
        $this->collLyricVotesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseLyricVote objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseLyricVote objects.
     * @throws PropelException
     */
    public function countLyricVotes(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLyricVotesPartial && !$this->isNew();
        if (null === $this->collLyricVotes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLyricVotes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getLyricVotes());
            }

            $query = LyricVoteQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collLyricVotes);
    }

    /**
     * Method called to associate a LyricVote object to this object
     * through the LyricVote foreign key attribute.
     *
     * @param  LyricVote $l LyricVote
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addLyricVote(LyricVote $l)
    {
        if ($this->collLyricVotes === null) {
            $this->initLyricVotes();
            $this->collLyricVotesPartial = true;
        }

        if (!$this->collLyricVotes->contains($l)) {
            $this->doAddLyricVote($l);

            if ($this->lyricVotesScheduledForDeletion and $this->lyricVotesScheduledForDeletion->contains($l)) {
                $this->lyricVotesScheduledForDeletion->remove($this->lyricVotesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param LyricVote $lyricVote The LyricVote object to add.
     */
    protected function doAddLyricVote(LyricVote $lyricVote)
    {
        $this->collLyricVotes[]= $lyricVote;
        $lyricVote->setUser($this);
    }

    /**
     * @param  LyricVote $lyricVote The LyricVote object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeLyricVote(LyricVote $lyricVote)
    {
        if ($this->getLyricVotes()->contains($lyricVote)) {
            $pos = $this->collLyricVotes->search($lyricVote);
            $this->collLyricVotes->remove($pos);
            if (null === $this->lyricVotesScheduledForDeletion) {
                $this->lyricVotesScheduledForDeletion = clone $this->collLyricVotes;
                $this->lyricVotesScheduledForDeletion->clear();
            }
            $this->lyricVotesScheduledForDeletion[]= $lyricVote;
            $lyricVote->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related LyricVotes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricVote[] List of LyricVote objects
     */
    public function getLyricVotesJoinLyric(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricVoteQuery::create(null, $criteria);
        $query->joinWith('Lyric', $joinBehavior);

        return $this->getLyricVotes($query, $con);
    }

    /**
     * Clears out the collArtists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addArtists()
     */
    public function clearArtists()
    {
        $this->collArtists = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collArtists collection loaded partially.
     */
    public function resetPartialArtists($v = true)
    {
        $this->collArtistsPartial = $v;
    }

    /**
     * Initializes the collArtists collection.
     *
     * By default this just sets the collArtists collection to an empty array (like clearcollArtists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArtists($overrideExisting = true)
    {
        if (null !== $this->collArtists && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArtistTableMap::getTableMap()->getCollectionClassName();

        $this->collArtists = new $collectionClassName;
        $this->collArtists->setModel('\Tekstove\ApiBundle\Model\Artist');
    }

    /**
     * Gets an array of ChildArtist objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArtist[] List of ChildArtist objects
     * @throws PropelException
     */
    public function getArtists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistsPartial && !$this->isNew();
        if (null === $this->collArtists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArtists) {
                // return empty collection
                $this->initArtists();
            } else {
                $collArtists = ChildArtistQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArtistsPartial && count($collArtists)) {
                        $this->initArtists(false);

                        foreach ($collArtists as $obj) {
                            if (false == $this->collArtists->contains($obj)) {
                                $this->collArtists->append($obj);
                            }
                        }

                        $this->collArtistsPartial = true;
                    }

                    return $collArtists;
                }

                if ($partial && $this->collArtists) {
                    foreach ($this->collArtists as $obj) {
                        if ($obj->isNew()) {
                            $collArtists[] = $obj;
                        }
                    }
                }

                $this->collArtists = $collArtists;
                $this->collArtistsPartial = false;
            }
        }

        return $this->collArtists;
    }

    /**
     * Sets a collection of ChildArtist objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $artists A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setArtists(Collection $artists, ConnectionInterface $con = null)
    {
        /** @var ChildArtist[] $artistsToDelete */
        $artistsToDelete = $this->getArtists(new Criteria(), $con)->diff($artists);


        $this->artistsScheduledForDeletion = $artistsToDelete;

        foreach ($artistsToDelete as $artistRemoved) {
            $artistRemoved->setUser(null);
        }

        $this->collArtists = null;
        foreach ($artists as $artist) {
            $this->addArtist($artist);
        }

        $this->collArtists = $artists;
        $this->collArtistsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Artist objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Artist objects.
     * @throws PropelException
     */
    public function countArtists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistsPartial && !$this->isNew();
        if (null === $this->collArtists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArtists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArtists());
            }

            $query = ChildArtistQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collArtists);
    }

    /**
     * Method called to associate a ChildArtist object to this object
     * through the ChildArtist foreign key attribute.
     *
     * @param  ChildArtist $l ChildArtist
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addArtist(ChildArtist $l)
    {
        if ($this->collArtists === null) {
            $this->initArtists();
            $this->collArtistsPartial = true;
        }

        if (!$this->collArtists->contains($l)) {
            $this->doAddArtist($l);

            if ($this->artistsScheduledForDeletion and $this->artistsScheduledForDeletion->contains($l)) {
                $this->artistsScheduledForDeletion->remove($this->artistsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArtist $artist The ChildArtist object to add.
     */
    protected function doAddArtist(ChildArtist $artist)
    {
        $this->collArtists[]= $artist;
        $artist->setUser($this);
    }

    /**
     * @param  ChildArtist $artist The ChildArtist object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeArtist(ChildArtist $artist)
    {
        if ($this->getArtists()->contains($artist)) {
            $pos = $this->collArtists->search($artist);
            $this->collArtists->remove($pos);
            if (null === $this->artistsScheduledForDeletion) {
                $this->artistsScheduledForDeletion = clone $this->collArtists;
                $this->artistsScheduledForDeletion->clear();
            }
            $this->artistsScheduledForDeletion[]= $artist;
            $artist->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collAlbums collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAlbums()
     */
    public function clearAlbums()
    {
        $this->collAlbums = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAlbums collection loaded partially.
     */
    public function resetPartialAlbums($v = true)
    {
        $this->collAlbumsPartial = $v;
    }

    /**
     * Initializes the collAlbums collection.
     *
     * By default this just sets the collAlbums collection to an empty array (like clearcollAlbums());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAlbums($overrideExisting = true)
    {
        if (null !== $this->collAlbums && !$overrideExisting) {
            return;
        }

        $collectionClassName = AlbumTableMap::getTableMap()->getCollectionClassName();

        $this->collAlbums = new $collectionClassName;
        $this->collAlbums->setModel('\Tekstove\ApiBundle\Model\Album');
    }

    /**
     * Gets an array of ChildAlbum objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAlbum[] List of ChildAlbum objects
     * @throws PropelException
     */
    public function getAlbums(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumsPartial && !$this->isNew();
        if (null === $this->collAlbums || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAlbums) {
                // return empty collection
                $this->initAlbums();
            } else {
                $collAlbums = ChildAlbumQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAlbumsPartial && count($collAlbums)) {
                        $this->initAlbums(false);

                        foreach ($collAlbums as $obj) {
                            if (false == $this->collAlbums->contains($obj)) {
                                $this->collAlbums->append($obj);
                            }
                        }

                        $this->collAlbumsPartial = true;
                    }

                    return $collAlbums;
                }

                if ($partial && $this->collAlbums) {
                    foreach ($this->collAlbums as $obj) {
                        if ($obj->isNew()) {
                            $collAlbums[] = $obj;
                        }
                    }
                }

                $this->collAlbums = $collAlbums;
                $this->collAlbumsPartial = false;
            }
        }

        return $this->collAlbums;
    }

    /**
     * Sets a collection of ChildAlbum objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $albums A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setAlbums(Collection $albums, ConnectionInterface $con = null)
    {
        /** @var ChildAlbum[] $albumsToDelete */
        $albumsToDelete = $this->getAlbums(new Criteria(), $con)->diff($albums);


        $this->albumsScheduledForDeletion = $albumsToDelete;

        foreach ($albumsToDelete as $albumRemoved) {
            $albumRemoved->setUser(null);
        }

        $this->collAlbums = null;
        foreach ($albums as $album) {
            $this->addAlbum($album);
        }

        $this->collAlbums = $albums;
        $this->collAlbumsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Album objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Album objects.
     * @throws PropelException
     */
    public function countAlbums(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAlbumsPartial && !$this->isNew();
        if (null === $this->collAlbums || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAlbums) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAlbums());
            }

            $query = ChildAlbumQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collAlbums);
    }

    /**
     * Method called to associate a ChildAlbum object to this object
     * through the ChildAlbum foreign key attribute.
     *
     * @param  ChildAlbum $l ChildAlbum
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addAlbum(ChildAlbum $l)
    {
        if ($this->collAlbums === null) {
            $this->initAlbums();
            $this->collAlbumsPartial = true;
        }

        if (!$this->collAlbums->contains($l)) {
            $this->doAddAlbum($l);

            if ($this->albumsScheduledForDeletion and $this->albumsScheduledForDeletion->contains($l)) {
                $this->albumsScheduledForDeletion->remove($this->albumsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAlbum $album The ChildAlbum object to add.
     */
    protected function doAddAlbum(ChildAlbum $album)
    {
        $this->collAlbums[]= $album;
        $album->setUser($this);
    }

    /**
     * @param  ChildAlbum $album The ChildAlbum object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeAlbum(ChildAlbum $album)
    {
        if ($this->getAlbums()->contains($album)) {
            $pos = $this->collAlbums->search($album);
            $this->collAlbums->remove($pos);
            if (null === $this->albumsScheduledForDeletion) {
                $this->albumsScheduledForDeletion = clone $this->collAlbums;
                $this->albumsScheduledForDeletion->clear();
            }
            $this->albumsScheduledForDeletion[]= $album;
            $album->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collTopics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTopics()
     */
    public function clearTopics()
    {
        $this->collTopics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTopics collection loaded partially.
     */
    public function resetPartialTopics($v = true)
    {
        $this->collTopicsPartial = $v;
    }

    /**
     * Initializes the collTopics collection.
     *
     * By default this just sets the collTopics collection to an empty array (like clearcollTopics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTopics($overrideExisting = true)
    {
        if (null !== $this->collTopics && !$overrideExisting) {
            return;
        }

        $collectionClassName = TopicTableMap::getTableMap()->getCollectionClassName();

        $this->collTopics = new $collectionClassName;
        $this->collTopics->setModel('\Tekstove\ApiBundle\Model\Forum\Topic');
    }

    /**
     * Gets an array of Topic objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Topic[] List of Topic objects
     * @throws PropelException
     */
    public function getTopics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicsPartial && !$this->isNew();
        if (null === $this->collTopics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTopics) {
                // return empty collection
                $this->initTopics();
            } else {
                $collTopics = TopicQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTopicsPartial && count($collTopics)) {
                        $this->initTopics(false);

                        foreach ($collTopics as $obj) {
                            if (false == $this->collTopics->contains($obj)) {
                                $this->collTopics->append($obj);
                            }
                        }

                        $this->collTopicsPartial = true;
                    }

                    return $collTopics;
                }

                if ($partial && $this->collTopics) {
                    foreach ($this->collTopics as $obj) {
                        if ($obj->isNew()) {
                            $collTopics[] = $obj;
                        }
                    }
                }

                $this->collTopics = $collTopics;
                $this->collTopicsPartial = false;
            }
        }

        return $this->collTopics;
    }

    /**
     * Sets a collection of Topic objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $topics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setTopics(Collection $topics, ConnectionInterface $con = null)
    {
        /** @var Topic[] $topicsToDelete */
        $topicsToDelete = $this->getTopics(new Criteria(), $con)->diff($topics);


        $this->topicsScheduledForDeletion = $topicsToDelete;

        foreach ($topicsToDelete as $topicRemoved) {
            $topicRemoved->setUser(null);
        }

        $this->collTopics = null;
        foreach ($topics as $topic) {
            $this->addTopic($topic);
        }

        $this->collTopics = $topics;
        $this->collTopicsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseTopic objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseTopic objects.
     * @throws PropelException
     */
    public function countTopics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTopicsPartial && !$this->isNew();
        if (null === $this->collTopics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTopics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTopics());
            }

            $query = TopicQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collTopics);
    }

    /**
     * Method called to associate a Topic object to this object
     * through the Topic foreign key attribute.
     *
     * @param  Topic $l Topic
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addTopic(Topic $l)
    {
        if ($this->collTopics === null) {
            $this->initTopics();
            $this->collTopicsPartial = true;
        }

        if (!$this->collTopics->contains($l)) {
            $this->doAddTopic($l);

            if ($this->topicsScheduledForDeletion and $this->topicsScheduledForDeletion->contains($l)) {
                $this->topicsScheduledForDeletion->remove($this->topicsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Topic $topic The Topic object to add.
     */
    protected function doAddTopic(Topic $topic)
    {
        $this->collTopics[]= $topic;
        $topic->setUser($this);
    }

    /**
     * @param  Topic $topic The Topic object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeTopic(Topic $topic)
    {
        if ($this->getTopics()->contains($topic)) {
            $pos = $this->collTopics->search($topic);
            $this->collTopics->remove($pos);
            if (null === $this->topicsScheduledForDeletion) {
                $this->topicsScheduledForDeletion = clone $this->collTopics;
                $this->topicsScheduledForDeletion->clear();
            }
            $this->topicsScheduledForDeletion[]= $topic;
            $topic->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Topics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Topic[] List of Topic objects
     */
    public function getTopicsJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = TopicQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getTopics($query, $con);
    }

    /**
     * Clears out the collPosts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPosts()
     */
    public function clearPosts()
    {
        $this->collPosts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPosts collection loaded partially.
     */
    public function resetPartialPosts($v = true)
    {
        $this->collPostsPartial = $v;
    }

    /**
     * Initializes the collPosts collection.
     *
     * By default this just sets the collPosts collection to an empty array (like clearcollPosts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPosts($overrideExisting = true)
    {
        if (null !== $this->collPosts && !$overrideExisting) {
            return;
        }

        $collectionClassName = PostTableMap::getTableMap()->getCollectionClassName();

        $this->collPosts = new $collectionClassName;
        $this->collPosts->setModel('\Tekstove\ApiBundle\Model\Forum\Post');
    }

    /**
     * Gets an array of Post objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Post[] List of Post objects
     * @throws PropelException
     */
    public function getPosts(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPostsPartial && !$this->isNew();
        if (null === $this->collPosts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPosts) {
                // return empty collection
                $this->initPosts();
            } else {
                $collPosts = PostQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPostsPartial && count($collPosts)) {
                        $this->initPosts(false);

                        foreach ($collPosts as $obj) {
                            if (false == $this->collPosts->contains($obj)) {
                                $this->collPosts->append($obj);
                            }
                        }

                        $this->collPostsPartial = true;
                    }

                    return $collPosts;
                }

                if ($partial && $this->collPosts) {
                    foreach ($this->collPosts as $obj) {
                        if ($obj->isNew()) {
                            $collPosts[] = $obj;
                        }
                    }
                }

                $this->collPosts = $collPosts;
                $this->collPostsPartial = false;
            }
        }

        return $this->collPosts;
    }

    /**
     * Sets a collection of Post objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $posts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPosts(Collection $posts, ConnectionInterface $con = null)
    {
        /** @var Post[] $postsToDelete */
        $postsToDelete = $this->getPosts(new Criteria(), $con)->diff($posts);


        $this->postsScheduledForDeletion = $postsToDelete;

        foreach ($postsToDelete as $postRemoved) {
            $postRemoved->setUser(null);
        }

        $this->collPosts = null;
        foreach ($posts as $post) {
            $this->addPost($post);
        }

        $this->collPosts = $posts;
        $this->collPostsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BasePost objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BasePost objects.
     * @throws PropelException
     */
    public function countPosts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPostsPartial && !$this->isNew();
        if (null === $this->collPosts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPosts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPosts());
            }

            $query = PostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPosts);
    }

    /**
     * Method called to associate a Post object to this object
     * through the Post foreign key attribute.
     *
     * @param  Post $l Post
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addPost(Post $l)
    {
        if ($this->collPosts === null) {
            $this->initPosts();
            $this->collPostsPartial = true;
        }

        if (!$this->collPosts->contains($l)) {
            $this->doAddPost($l);

            if ($this->postsScheduledForDeletion and $this->postsScheduledForDeletion->contains($l)) {
                $this->postsScheduledForDeletion->remove($this->postsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Post $post The Post object to add.
     */
    protected function doAddPost(Post $post)
    {
        $this->collPosts[]= $post;
        $post->setUser($this);
    }

    /**
     * @param  Post $post The Post object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePost(Post $post)
    {
        if ($this->getPosts()->contains($post)) {
            $pos = $this->collPosts->search($post);
            $this->collPosts->remove($pos);
            if (null === $this->postsScheduledForDeletion) {
                $this->postsScheduledForDeletion = clone $this->collPosts;
                $this->postsScheduledForDeletion->clear();
            }
            $this->postsScheduledForDeletion[]= $post;
            $post->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Posts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|Post[] List of Post objects
     */
    public function getPostsJoinTopic(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = PostQuery::create(null, $criteria);
        $query->joinWith('Topic', $joinBehavior);

        return $this->getPosts($query, $con);
    }

    /**
     * Clears out the collMessages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMessages()
     */
    public function clearMessages()
    {
        $this->collMessages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMessages collection loaded partially.
     */
    public function resetPartialMessages($v = true)
    {
        $this->collMessagesPartial = $v;
    }

    /**
     * Initializes the collMessages collection.
     *
     * By default this just sets the collMessages collection to an empty array (like clearcollMessages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMessages($overrideExisting = true)
    {
        if (null !== $this->collMessages && !$overrideExisting) {
            return;
        }

        $collectionClassName = MessageTableMap::getTableMap()->getCollectionClassName();

        $this->collMessages = new $collectionClassName;
        $this->collMessages->setModel('\Tekstove\ApiBundle\Model\Chat\Message');
    }

    /**
     * Gets an array of Message objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Message[] List of Message objects
     * @throws PropelException
     */
    public function getMessages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMessagesPartial && !$this->isNew();
        if (null === $this->collMessages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMessages) {
                // return empty collection
                $this->initMessages();
            } else {
                $collMessages = MessageQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMessagesPartial && count($collMessages)) {
                        $this->initMessages(false);

                        foreach ($collMessages as $obj) {
                            if (false == $this->collMessages->contains($obj)) {
                                $this->collMessages->append($obj);
                            }
                        }

                        $this->collMessagesPartial = true;
                    }

                    return $collMessages;
                }

                if ($partial && $this->collMessages) {
                    foreach ($this->collMessages as $obj) {
                        if ($obj->isNew()) {
                            $collMessages[] = $obj;
                        }
                    }
                }

                $this->collMessages = $collMessages;
                $this->collMessagesPartial = false;
            }
        }

        return $this->collMessages;
    }

    /**
     * Sets a collection of Message objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $messages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setMessages(Collection $messages, ConnectionInterface $con = null)
    {
        /** @var Message[] $messagesToDelete */
        $messagesToDelete = $this->getMessages(new Criteria(), $con)->diff($messages);


        $this->messagesScheduledForDeletion = $messagesToDelete;

        foreach ($messagesToDelete as $messageRemoved) {
            $messageRemoved->setUser(null);
        }

        $this->collMessages = null;
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        $this->collMessages = $messages;
        $this->collMessagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseMessage objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseMessage objects.
     * @throws PropelException
     */
    public function countMessages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMessagesPartial && !$this->isNew();
        if (null === $this->collMessages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMessages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMessages());
            }

            $query = MessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collMessages);
    }

    /**
     * Method called to associate a Message object to this object
     * through the Message foreign key attribute.
     *
     * @param  Message $l Message
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addMessage(Message $l)
    {
        if ($this->collMessages === null) {
            $this->initMessages();
            $this->collMessagesPartial = true;
        }

        if (!$this->collMessages->contains($l)) {
            $this->doAddMessage($l);

            if ($this->messagesScheduledForDeletion and $this->messagesScheduledForDeletion->contains($l)) {
                $this->messagesScheduledForDeletion->remove($this->messagesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Message $message The Message object to add.
     */
    protected function doAddMessage(Message $message)
    {
        $this->collMessages[]= $message;
        $message->setUser($this);
    }

    /**
     * @param  Message $message The Message object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeMessage(Message $message)
    {
        if ($this->getMessages()->contains($message)) {
            $pos = $this->collMessages->search($message);
            $this->collMessages->remove($pos);
            if (null === $this->messagesScheduledForDeletion) {
                $this->messagesScheduledForDeletion = clone $this->collMessages;
                $this->messagesScheduledForDeletion->clear();
            }
            $this->messagesScheduledForDeletion[]= $message;
            $message->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collOnlines collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addOnlines()
     */
    public function clearOnlines()
    {
        $this->collOnlines = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collOnlines collection loaded partially.
     */
    public function resetPartialOnlines($v = true)
    {
        $this->collOnlinesPartial = $v;
    }

    /**
     * Initializes the collOnlines collection.
     *
     * By default this just sets the collOnlines collection to an empty array (like clearcollOnlines());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initOnlines($overrideExisting = true)
    {
        if (null !== $this->collOnlines && !$overrideExisting) {
            return;
        }

        $collectionClassName = OnlineTableMap::getTableMap()->getCollectionClassName();

        $this->collOnlines = new $collectionClassName;
        $this->collOnlines->setModel('\Tekstove\ApiBundle\Model\Chat\Online');
    }

    /**
     * Gets an array of Online objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|Online[] List of Online objects
     * @throws PropelException
     */
    public function getOnlines(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collOnlinesPartial && !$this->isNew();
        if (null === $this->collOnlines || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collOnlines) {
                // return empty collection
                $this->initOnlines();
            } else {
                $collOnlines = OnlineQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collOnlinesPartial && count($collOnlines)) {
                        $this->initOnlines(false);

                        foreach ($collOnlines as $obj) {
                            if (false == $this->collOnlines->contains($obj)) {
                                $this->collOnlines->append($obj);
                            }
                        }

                        $this->collOnlinesPartial = true;
                    }

                    return $collOnlines;
                }

                if ($partial && $this->collOnlines) {
                    foreach ($this->collOnlines as $obj) {
                        if ($obj->isNew()) {
                            $collOnlines[] = $obj;
                        }
                    }
                }

                $this->collOnlines = $collOnlines;
                $this->collOnlinesPartial = false;
            }
        }

        return $this->collOnlines;
    }

    /**
     * Sets a collection of Online objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $onlines A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setOnlines(Collection $onlines, ConnectionInterface $con = null)
    {
        /** @var Online[] $onlinesToDelete */
        $onlinesToDelete = $this->getOnlines(new Criteria(), $con)->diff($onlines);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->onlinesScheduledForDeletion = clone $onlinesToDelete;

        foreach ($onlinesToDelete as $onlineRemoved) {
            $onlineRemoved->setUser(null);
        }

        $this->collOnlines = null;
        foreach ($onlines as $online) {
            $this->addOnline($online);
        }

        $this->collOnlines = $onlines;
        $this->collOnlinesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BaseOnline objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related BaseOnline objects.
     * @throws PropelException
     */
    public function countOnlines(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collOnlinesPartial && !$this->isNew();
        if (null === $this->collOnlines || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collOnlines) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getOnlines());
            }

            $query = OnlineQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collOnlines);
    }

    /**
     * Method called to associate a Online object to this object
     * through the Online foreign key attribute.
     *
     * @param  Online $l Online
     * @return $this|\Tekstove\ApiBundle\Model\User The current object (for fluent API support)
     */
    public function addOnline(Online $l)
    {
        if ($this->collOnlines === null) {
            $this->initOnlines();
            $this->collOnlinesPartial = true;
        }

        if (!$this->collOnlines->contains($l)) {
            $this->doAddOnline($l);

            if ($this->onlinesScheduledForDeletion and $this->onlinesScheduledForDeletion->contains($l)) {
                $this->onlinesScheduledForDeletion->remove($this->onlinesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param Online $online The Online object to add.
     */
    protected function doAddOnline(Online $online)
    {
        $this->collOnlines[]= $online;
        $online->setUser($this);
    }

    /**
     * @param  Online $online The Online object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeOnline(Online $online)
    {
        if ($this->getOnlines()->contains($online)) {
            $pos = $this->collOnlines->search($online);
            $this->collOnlines->remove($pos);
            if (null === $this->onlinesScheduledForDeletion) {
                $this->onlinesScheduledForDeletion = clone $this->collOnlines;
                $this->onlinesScheduledForDeletion->clear();
            }
            $this->onlinesScheduledForDeletion[]= clone $online;
            $online->setUser(null);
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
        $this->username = null;
        $this->password = null;
        $this->api_key = null;
        $this->mail = null;
        $this->avatar = null;
        $this->about = null;
        $this->autoplay = null;
        $this->terms_accepted = null;
        $this->status = null;
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
            if ($this->collPmsRelatedByUserTo) {
                foreach ($this->collPmsRelatedByUserTo as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPmsRelatedByUserFrom) {
                foreach ($this->collPmsRelatedByUserFrom as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPermissionGroupUsers) {
                foreach ($this->collPermissionGroupUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLyrics) {
                foreach ($this->collLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLyricTranslations) {
                foreach ($this->collLyricTranslations as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLyricVotes) {
                foreach ($this->collLyricVotes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArtists) {
                foreach ($this->collArtists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAlbums) {
                foreach ($this->collAlbums as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTopics) {
                foreach ($this->collTopics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPosts) {
                foreach ($this->collPosts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMessages) {
                foreach ($this->collMessages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collOnlines) {
                foreach ($this->collOnlines as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPmsRelatedByUserTo = null;
        $this->collPmsRelatedByUserFrom = null;
        $this->collPermissionGroupUsers = null;
        $this->collLyrics = null;
        $this->collLyricTranslations = null;
        $this->collLyricVotes = null;
        $this->collArtists = null;
        $this->collAlbums = null;
        $this->collTopics = null;
        $this->collPosts = null;
        $this->collMessages = null;
        $this->collOnlines = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string The value of the 'username' column
     */
    public function __toString()
    {
        return (string) $this->getUsername();
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
        $metadata->addPropertyConstraint('mail', new NotBlank());
        $metadata->addPropertyConstraint('mail', new Email());
        $metadata->addPropertyConstraint('mail', new Unique());
        $metadata->addPropertyConstraint('username', new NotBlank());
        $metadata->addPropertyConstraint('username', new Unique());
        $metadata->addPropertyConstraint('password', new NotBlank());
        $metadata->addPropertyConstraint('api_key', new NotBlank());
        $metadata->addPropertyConstraint('avatar', new Length(array ('max' => 100,)));
        $metadata->addPropertyConstraint('about', new Length(array ('max' => 65000,)));
        $metadata->addPropertyConstraint('terms_accepted', new GreaterThanOrEqual(array ('value' => '2018-05-04',)));
        $metadata->addPropertyConstraint('terms_accepted', new NotBlank());
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


            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collPmsRelatedByUserTo) {
                foreach ($this->collPmsRelatedByUserTo as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collPmsRelatedByUserFrom) {
                foreach ($this->collPmsRelatedByUserFrom as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collPermissionGroupUsers) {
                foreach ($this->collPermissionGroupUsers as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collLyrics) {
                foreach ($this->collLyrics as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collLyricTranslations) {
                foreach ($this->collLyricTranslations as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collLyricVotes) {
                foreach ($this->collLyricVotes as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collArtists) {
                foreach ($this->collArtists as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collAlbums) {
                foreach ($this->collAlbums as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collTopics) {
                foreach ($this->collTopics as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collPosts) {
                foreach ($this->collPosts as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collMessages) {
                foreach ($this->collMessages as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collOnlines) {
                foreach ($this->collOnlines as $referrerFK) {
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
