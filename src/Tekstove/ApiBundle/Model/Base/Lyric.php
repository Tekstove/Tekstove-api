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
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tekstove\ApiBundle\Model\AlbumLyric as ChildAlbumLyric;
use Tekstove\ApiBundle\Model\AlbumLyricQuery as ChildAlbumLyricQuery;
use Tekstove\ApiBundle\Model\Artist as ChildArtist;
use Tekstove\ApiBundle\Model\ArtistQuery as ChildArtistQuery;
use Tekstove\ApiBundle\Model\Language as ChildLanguage;
use Tekstove\ApiBundle\Model\LanguageQuery as ChildLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric as ChildLyric;
use Tekstove\ApiBundle\Model\LyricQuery as ChildLyricQuery;
use Tekstove\ApiBundle\Model\User as ChildUser;
use Tekstove\ApiBundle\Model\UserQuery as ChildUserQuery;
use Tekstove\ApiBundle\Model\Artist\ArtistLyric;
use Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery;
use Tekstove\ApiBundle\Model\Artist\Base\ArtistLyric as BaseArtistLyric;
use Tekstove\ApiBundle\Model\Artist\Map\ArtistLyricTableMap;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\LyricTranslationQuery;
use Tekstove\ApiBundle\Model\Lyric\LyricVote;
use Tekstove\ApiBundle\Model\Lyric\LyricVoteQuery;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricLanguage as BaseLyricLanguage;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricTranslation as BaseLyricTranslation;
use Tekstove\ApiBundle\Model\Lyric\Base\LyricVote as BaseLyricVote;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricLanguageTableMap;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricTranslationTableMap;
use Tekstove\ApiBundle\Model\Lyric\Map\LyricVoteTableMap;
use Tekstove\ApiBundle\Model\Map\AlbumLyricTableMap;
use Tekstove\ApiBundle\Model\Map\LyricTableMap;

/**
 * Base class that represents a row from the 'lyric' table.
 *
 *
 *
 * @package    propel.generator.src.Tekstove.ApiBundle.Model.Base
 */
abstract class Lyric implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Tekstove\\ApiBundle\\Model\\Map\\LyricTableMap';


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
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the text field.
     *
     * @var        string
     */
    protected $text;

    /**
     * The value for the text_bg field.
     *
     * @var        string
     */
    protected $text_bg;

    /**
     * The value for the text_bg_added field.
     *
     * @var        DateTime
     */
    protected $text_bg_added;

    /**
     * The value for the extra_info field.
     *
     * @var        string
     */
    protected $extra_info;

    /**
     * The value for the send_by field.
     *
     * @var        int
     */
    protected $send_by;

    /**
     * The value for the cache_title_short field.
     *
     * @var        string
     */
    protected $cache_title_short;

    /**
     * The value for the cache_censor field.
     *
     * @var        boolean
     */
    protected $cache_censor;

    /**
     * The value for the cache_censor_updated field.
     *
     * @var        DateTime
     */
    protected $cache_censor_updated;

    /**
     * The value for the views field.
     *
     * @var        int
     */
    protected $views;

    /**
     * The value for the popularity field.
     *
     * @var        int
     */
    protected $popularity;

    /**
     * The value for the votes_count field.
     *
     * @var        int
     */
    protected $votes_count;

    /**
     * The value for the video_youtube field.
     *
     * @var        string
     */
    protected $video_youtube;

    /**
     * The value for the video_vbox7 field.
     *
     * @var        string
     */
    protected $video_vbox7;

    /**
     * The value for the video_metacafe field.
     *
     * @var        string
     */
    protected $video_metacafe;

    /**
     * The value for the download field.
     *
     * @var        string
     */
    protected $download;

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
     * @var        ObjectCollection|LyricLanguage[] Collection to store aggregation of LyricLanguage objects.
     */
    protected $collLyricLanguages;
    protected $collLyricLanguagesPartial;

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
     * @var        ObjectCollection|ChildAlbumLyric[] Collection to store aggregation of ChildAlbumLyric objects.
     */
    protected $collAlbumLyrics;
    protected $collAlbumLyricsPartial;

    /**
     * @var        ObjectCollection|ChildArtist[] Cross Collection to store aggregation of ChildArtist objects.
     */
    protected $collArtists;

    /**
     * @var bool
     */
    protected $collArtistsPartial;

    /**
     * @var        ObjectCollection|ChildLanguage[] Cross Collection to store aggregation of ChildLanguage objects.
     */
    protected $collLanguages;

    /**
     * @var bool
     */
    protected $collLanguagesPartial;

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
     * @var ObjectCollection|ChildArtist[]
     */
    protected $artistsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildLanguage[]
     */
    protected $languagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ArtistLyric[]
     */
    protected $artistLyricsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|LyricLanguage[]
     */
    protected $lyricLanguagesScheduledForDeletion = null;

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
     * @var ObjectCollection|ChildAlbumLyric[]
     */
    protected $albumLyricsScheduledForDeletion = null;

    /**
     * Initializes internal state of Tekstove\ApiBundle\Model\Base\Lyric object.
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
     * Compares this with another <code>Lyric</code> instance.  If
     * <code>obj</code> is an instance of <code>Lyric</code>, delegates to
     * <code>equals(Lyric)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Lyric The current object, for fluid interface
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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [text] column value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the [text_bg] column value.
     *
     * @return string
     */
    public function gettextBg()
    {
        return $this->text_bg;
    }

    /**
     * Get the [optionally formatted] temporal [text_bg_added] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function gettextBgAdded($format = NULL)
    {
        if ($format === null) {
            return $this->text_bg_added;
        } else {
            return $this->text_bg_added instanceof \DateTimeInterface ? $this->text_bg_added->format($format) : null;
        }
    }

    /**
     * Get the [extra_info] column value.
     *
     * @return string
     */
    public function getextraInfo()
    {
        return $this->extra_info;
    }

    /**
     * Get the [send_by] column value.
     *
     * @return int
     */
    public function getsendBy()
    {
        return $this->send_by;
    }

    /**
     * Get the [cache_title_short] column value.
     *
     * @return string
     */
    public function getcacheTitleShort()
    {
        return $this->cache_title_short;
    }

    /**
     * Get the [cache_censor] column value.
     *
     * @return boolean
     */
    public function getcacheCensor()
    {
        return $this->cache_censor;
    }

    /**
     * Get the [cache_censor] column value.
     *
     * @return boolean
     */
    public function isCacheCensor()
    {
        return $this->getcacheCensor();
    }

    /**
     * Get the [optionally formatted] temporal [cache_censor_updated] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getcacheCensorUpdated($format = NULL)
    {
        if ($format === null) {
            return $this->cache_censor_updated;
        } else {
            return $this->cache_censor_updated instanceof \DateTimeInterface ? $this->cache_censor_updated->format($format) : null;
        }
    }

    /**
     * Get the [views] column value.
     *
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Get the [popularity] column value.
     *
     * @return int
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Get the [votes_count] column value.
     *
     * @return int
     */
    public function getvotesCount()
    {
        return $this->votes_count;
    }

    /**
     * Get the [video_youtube] column value.
     *
     * @return string
     */
    public function getvideoYoutube()
    {
        return $this->video_youtube;
    }

    /**
     * Get the [video_vbox7] column value.
     *
     * @return string
     */
    public function getvideoVbox7()
    {
        return $this->video_vbox7;
    }

    /**
     * Get the [video_metacafe] column value.
     *
     * @return string
     */
    public function getvideoMetacafe()
    {
        return $this->video_metacafe;
    }

    /**
     * Get the [download] column value.
     *
     * @return string
     */
    public function getdownload()
    {
        return $this->download;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[LyricTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[LyricTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Set the value of [text] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setText($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text !== $v) {
            $this->text = $v;
            $this->modifiedColumns[LyricTableMap::COL_TEXT] = true;
        }

        return $this;
    } // setText()

    /**
     * Set the value of [text_bg] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function settextBg($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text_bg !== $v) {
            $this->text_bg = $v;
            $this->modifiedColumns[LyricTableMap::COL_TEXT_BG] = true;
        }

        return $this;
    } // settextBg()

    /**
     * Sets the value of [text_bg_added] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function settextBgAdded($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->text_bg_added !== null || $dt !== null) {
            if ($this->text_bg_added === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->text_bg_added->format("Y-m-d H:i:s.u")) {
                $this->text_bg_added = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LyricTableMap::COL_TEXT_BG_ADDED] = true;
            }
        } // if either are not null

        return $this;
    } // settextBgAdded()

    /**
     * Set the value of [extra_info] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setextraInfo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->extra_info !== $v) {
            $this->extra_info = $v;
            $this->modifiedColumns[LyricTableMap::COL_EXTRA_INFO] = true;
        }

        return $this;
    } // setextraInfo()

    /**
     * Set the value of [send_by] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setsendBy($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->send_by !== $v) {
            $this->send_by = $v;
            $this->modifiedColumns[LyricTableMap::COL_SEND_BY] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setsendBy()

    /**
     * Set the value of [cache_title_short] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setcacheTitleShort($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->cache_title_short !== $v) {
            $this->cache_title_short = $v;
            $this->modifiedColumns[LyricTableMap::COL_CACHE_TITLE_SHORT] = true;
        }

        return $this;
    } // setcacheTitleShort()

    /**
     * Sets the value of the [cache_censor] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setcacheCensor($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->cache_censor !== $v) {
            $this->cache_censor = $v;
            $this->modifiedColumns[LyricTableMap::COL_CACHE_CENSOR] = true;
        }

        return $this;
    } // setcacheCensor()

    /**
     * Sets the value of [cache_censor_updated] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setcacheCensorUpdated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->cache_censor_updated !== null || $dt !== null) {
            if ($this->cache_censor_updated === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->cache_censor_updated->format("Y-m-d H:i:s.u")) {
                $this->cache_censor_updated = $dt === null ? null : clone $dt;
                $this->modifiedColumns[LyricTableMap::COL_CACHE_CENSOR_UPDATED] = true;
            }
        } // if either are not null

        return $this;
    } // setcacheCensorUpdated()

    /**
     * Set the value of [views] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setViews($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->views !== $v) {
            $this->views = $v;
            $this->modifiedColumns[LyricTableMap::COL_VIEWS] = true;
        }

        return $this;
    } // setViews()

    /**
     * Set the value of [popularity] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setPopularity($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->popularity !== $v) {
            $this->popularity = $v;
            $this->modifiedColumns[LyricTableMap::COL_POPULARITY] = true;
        }

        return $this;
    } // setPopularity()

    /**
     * Set the value of [votes_count] column.
     *
     * @param int $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setvotesCount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->votes_count !== $v) {
            $this->votes_count = $v;
            $this->modifiedColumns[LyricTableMap::COL_VOTES_COUNT] = true;
        }

        return $this;
    } // setvotesCount()

    /**
     * Set the value of [video_youtube] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setvideoYoutube($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->video_youtube !== $v) {
            $this->video_youtube = $v;
            $this->modifiedColumns[LyricTableMap::COL_VIDEO_YOUTUBE] = true;
        }

        return $this;
    } // setvideoYoutube()

    /**
     * Set the value of [video_vbox7] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setvideoVbox7($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->video_vbox7 !== $v) {
            $this->video_vbox7 = $v;
            $this->modifiedColumns[LyricTableMap::COL_VIDEO_VBOX7] = true;
        }

        return $this;
    } // setvideoVbox7()

    /**
     * Set the value of [video_metacafe] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setvideoMetacafe($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->video_metacafe !== $v) {
            $this->video_metacafe = $v;
            $this->modifiedColumns[LyricTableMap::COL_VIDEO_METACAFE] = true;
        }

        return $this;
    } // setvideoMetacafe()

    /**
     * Set the value of [download] column.
     *
     * @param string $v new value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
     */
    public function setdownload($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->download !== $v) {
            $this->download = $v;
            $this->modifiedColumns[LyricTableMap::COL_DOWNLOAD] = true;
        }

        return $this;
    } // setdownload()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : LyricTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : LyricTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : LyricTableMap::translateFieldName('Text', TableMap::TYPE_PHPNAME, $indexType)];
            $this->text = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : LyricTableMap::translateFieldName('textBg', TableMap::TYPE_PHPNAME, $indexType)];
            $this->text_bg = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : LyricTableMap::translateFieldName('textBgAdded', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->text_bg_added = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : LyricTableMap::translateFieldName('extraInfo', TableMap::TYPE_PHPNAME, $indexType)];
            $this->extra_info = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : LyricTableMap::translateFieldName('sendBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->send_by = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : LyricTableMap::translateFieldName('cacheTitleShort', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cache_title_short = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : LyricTableMap::translateFieldName('cacheCensor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cache_censor = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : LyricTableMap::translateFieldName('cacheCensorUpdated', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->cache_censor_updated = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : LyricTableMap::translateFieldName('Views', TableMap::TYPE_PHPNAME, $indexType)];
            $this->views = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : LyricTableMap::translateFieldName('Popularity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->popularity = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : LyricTableMap::translateFieldName('votesCount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->votes_count = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : LyricTableMap::translateFieldName('videoYoutube', TableMap::TYPE_PHPNAME, $indexType)];
            $this->video_youtube = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : LyricTableMap::translateFieldName('videoVbox7', TableMap::TYPE_PHPNAME, $indexType)];
            $this->video_vbox7 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : LyricTableMap::translateFieldName('videoMetacafe', TableMap::TYPE_PHPNAME, $indexType)];
            $this->video_metacafe = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : LyricTableMap::translateFieldName('download', TableMap::TYPE_PHPNAME, $indexType)];
            $this->download = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 17; // 17 = LyricTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Tekstove\\ApiBundle\\Model\\Lyric'), 0, $e);
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
        if ($this->aUser !== null && $this->send_by !== $this->aUser->getId()) {
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
            $con = Propel::getServiceContainer()->getReadConnection(LyricTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildLyricQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collArtistLyrics = null;

            $this->collLyricLanguages = null;

            $this->collLyricTranslations = null;

            $this->collLyricVotes = null;

            $this->collAlbumLyrics = null;

            $this->collArtists = null;
            $this->collLanguages = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Lyric::setDeleted()
     * @see Lyric::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildLyricQuery::create()
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

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(LyricTableMap::DATABASE_NAME);
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
                LyricTableMap::addInstanceToPool($this);
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

            if ($this->artistsScheduledForDeletion !== null) {
                if (!$this->artistsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->artistsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Tekstove\ApiBundle\Model\Artist\ArtistLyricQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->artistsScheduledForDeletion = null;
                }

            }

            if ($this->collArtists) {
                foreach ($this->collArtists as $artist) {
                    if (!$artist->isDeleted() && ($artist->isNew() || $artist->isModified())) {
                        $artist->save($con);
                    }
                }
            }


            if ($this->languagesScheduledForDeletion !== null) {
                if (!$this->languagesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->languagesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->languagesScheduledForDeletion = null;
                }

            }

            if ($this->collLanguages) {
                foreach ($this->collLanguages as $language) {
                    if (!$language->isDeleted() && ($language->isNew() || $language->isModified())) {
                        $language->save($con);
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

            if ($this->lyricLanguagesScheduledForDeletion !== null) {
                if (!$this->lyricLanguagesScheduledForDeletion->isEmpty()) {
                    \Tekstove\ApiBundle\Model\Lyric\LyricLanguageQuery::create()
                        ->filterByPrimaryKeys($this->lyricLanguagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->lyricLanguagesScheduledForDeletion = null;
                }
            }

            if ($this->collLyricLanguages !== null) {
                foreach ($this->collLyricLanguages as $referrerFK) {
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

            if ($this->albumLyricsScheduledForDeletion !== null) {
                if (!$this->albumLyricsScheduledForDeletion->isEmpty()) {
                    foreach ($this->albumLyricsScheduledForDeletion as $albumLyric) {
                        // need to save related object because we set the relation to null
                        $albumLyric->save($con);
                    }
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

        $this->modifiedColumns[LyricTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . LyricTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(LyricTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`title`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT)) {
            $modifiedColumns[':p' . $index++]  = '`text`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT_BG)) {
            $modifiedColumns[':p' . $index++]  = '`text_bg`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT_BG_ADDED)) {
            $modifiedColumns[':p' . $index++]  = '`text_bg_added`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_EXTRA_INFO)) {
            $modifiedColumns[':p' . $index++]  = '`extra_info`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_SEND_BY)) {
            $modifiedColumns[':p' . $index++]  = '`send_by`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_TITLE_SHORT)) {
            $modifiedColumns[':p' . $index++]  = '`cache_title_short`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_CENSOR)) {
            $modifiedColumns[':p' . $index++]  = '`cache_censor`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_CENSOR_UPDATED)) {
            $modifiedColumns[':p' . $index++]  = '`cache_censor_updated`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIEWS)) {
            $modifiedColumns[':p' . $index++]  = '`views`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_POPULARITY)) {
            $modifiedColumns[':p' . $index++]  = '`popularity`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_VOTES_COUNT)) {
            $modifiedColumns[':p' . $index++]  = '`votes_count`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_YOUTUBE)) {
            $modifiedColumns[':p' . $index++]  = '`video_youtube`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_VBOX7)) {
            $modifiedColumns[':p' . $index++]  = '`video_vbox7`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_METACAFE)) {
            $modifiedColumns[':p' . $index++]  = '`video_metacafe`';
        }
        if ($this->isColumnModified(LyricTableMap::COL_DOWNLOAD)) {
            $modifiedColumns[':p' . $index++]  = '`download`';
        }

        $sql = sprintf(
            'INSERT INTO `lyric` (%s) VALUES (%s)',
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
                    case '`title`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`text`':
                        $stmt->bindValue($identifier, $this->text, PDO::PARAM_STR);
                        break;
                    case '`text_bg`':
                        $stmt->bindValue($identifier, $this->text_bg, PDO::PARAM_STR);
                        break;
                    case '`text_bg_added`':
                        $stmt->bindValue($identifier, $this->text_bg_added ? $this->text_bg_added->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`extra_info`':
                        $stmt->bindValue($identifier, $this->extra_info, PDO::PARAM_STR);
                        break;
                    case '`send_by`':
                        $stmt->bindValue($identifier, $this->send_by, PDO::PARAM_INT);
                        break;
                    case '`cache_title_short`':
                        $stmt->bindValue($identifier, $this->cache_title_short, PDO::PARAM_STR);
                        break;
                    case '`cache_censor`':
                        $stmt->bindValue($identifier, (int) $this->cache_censor, PDO::PARAM_INT);
                        break;
                    case '`cache_censor_updated`':
                        $stmt->bindValue($identifier, $this->cache_censor_updated ? $this->cache_censor_updated->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case '`views`':
                        $stmt->bindValue($identifier, $this->views, PDO::PARAM_INT);
                        break;
                    case '`popularity`':
                        $stmt->bindValue($identifier, $this->popularity, PDO::PARAM_INT);
                        break;
                    case '`votes_count`':
                        $stmt->bindValue($identifier, $this->votes_count, PDO::PARAM_INT);
                        break;
                    case '`video_youtube`':
                        $stmt->bindValue($identifier, $this->video_youtube, PDO::PARAM_STR);
                        break;
                    case '`video_vbox7`':
                        $stmt->bindValue($identifier, $this->video_vbox7, PDO::PARAM_STR);
                        break;
                    case '`video_metacafe`':
                        $stmt->bindValue($identifier, $this->video_metacafe, PDO::PARAM_STR);
                        break;
                    case '`download`':
                        $stmt->bindValue($identifier, $this->download, PDO::PARAM_STR);
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
        $pos = LyricTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTitle();
                break;
            case 2:
                return $this->getText();
                break;
            case 3:
                return $this->gettextBg();
                break;
            case 4:
                return $this->gettextBgAdded();
                break;
            case 5:
                return $this->getextraInfo();
                break;
            case 6:
                return $this->getsendBy();
                break;
            case 7:
                return $this->getcacheTitleShort();
                break;
            case 8:
                return $this->getcacheCensor();
                break;
            case 9:
                return $this->getcacheCensorUpdated();
                break;
            case 10:
                return $this->getViews();
                break;
            case 11:
                return $this->getPopularity();
                break;
            case 12:
                return $this->getvotesCount();
                break;
            case 13:
                return $this->getvideoYoutube();
                break;
            case 14:
                return $this->getvideoVbox7();
                break;
            case 15:
                return $this->getvideoMetacafe();
                break;
            case 16:
                return $this->getdownload();
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

        if (isset($alreadyDumpedObjects['Lyric'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Lyric'][$this->hashCode()] = true;
        $keys = LyricTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getText(),
            $keys[3] => $this->gettextBg(),
            $keys[4] => $this->gettextBgAdded(),
            $keys[5] => $this->getextraInfo(),
            $keys[6] => $this->getsendBy(),
            $keys[7] => $this->getcacheTitleShort(),
            $keys[8] => $this->getcacheCensor(),
            $keys[9] => $this->getcacheCensorUpdated(),
            $keys[10] => $this->getViews(),
            $keys[11] => $this->getPopularity(),
            $keys[12] => $this->getvotesCount(),
            $keys[13] => $this->getvideoYoutube(),
            $keys[14] => $this->getvideoVbox7(),
            $keys[15] => $this->getvideoMetacafe(),
            $keys[16] => $this->getdownload(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[9]] instanceof \DateTime) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

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
     * @return $this|\Tekstove\ApiBundle\Model\Lyric
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = LyricTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Tekstove\ApiBundle\Model\Lyric
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setText($value);
                break;
            case 3:
                $this->settextBg($value);
                break;
            case 4:
                $this->settextBgAdded($value);
                break;
            case 5:
                $this->setextraInfo($value);
                break;
            case 6:
                $this->setsendBy($value);
                break;
            case 7:
                $this->setcacheTitleShort($value);
                break;
            case 8:
                $this->setcacheCensor($value);
                break;
            case 9:
                $this->setcacheCensorUpdated($value);
                break;
            case 10:
                $this->setViews($value);
                break;
            case 11:
                $this->setPopularity($value);
                break;
            case 12:
                $this->setvotesCount($value);
                break;
            case 13:
                $this->setvideoYoutube($value);
                break;
            case 14:
                $this->setvideoVbox7($value);
                break;
            case 15:
                $this->setvideoMetacafe($value);
                break;
            case 16:
                $this->setdownload($value);
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
        $keys = LyricTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setText($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->settextBg($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->settextBgAdded($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setextraInfo($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setsendBy($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setcacheTitleShort($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setcacheCensor($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setcacheCensorUpdated($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setViews($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setPopularity($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setvotesCount($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setvideoYoutube($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setvideoVbox7($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setvideoMetacafe($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setdownload($arr[$keys[16]]);
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
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object, for fluid interface
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
        $criteria = new Criteria(LyricTableMap::DATABASE_NAME);

        if ($this->isColumnModified(LyricTableMap::COL_ID)) {
            $criteria->add(LyricTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(LyricTableMap::COL_TITLE)) {
            $criteria->add(LyricTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT)) {
            $criteria->add(LyricTableMap::COL_TEXT, $this->text);
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT_BG)) {
            $criteria->add(LyricTableMap::COL_TEXT_BG, $this->text_bg);
        }
        if ($this->isColumnModified(LyricTableMap::COL_TEXT_BG_ADDED)) {
            $criteria->add(LyricTableMap::COL_TEXT_BG_ADDED, $this->text_bg_added);
        }
        if ($this->isColumnModified(LyricTableMap::COL_EXTRA_INFO)) {
            $criteria->add(LyricTableMap::COL_EXTRA_INFO, $this->extra_info);
        }
        if ($this->isColumnModified(LyricTableMap::COL_SEND_BY)) {
            $criteria->add(LyricTableMap::COL_SEND_BY, $this->send_by);
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_TITLE_SHORT)) {
            $criteria->add(LyricTableMap::COL_CACHE_TITLE_SHORT, $this->cache_title_short);
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_CENSOR)) {
            $criteria->add(LyricTableMap::COL_CACHE_CENSOR, $this->cache_censor);
        }
        if ($this->isColumnModified(LyricTableMap::COL_CACHE_CENSOR_UPDATED)) {
            $criteria->add(LyricTableMap::COL_CACHE_CENSOR_UPDATED, $this->cache_censor_updated);
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIEWS)) {
            $criteria->add(LyricTableMap::COL_VIEWS, $this->views);
        }
        if ($this->isColumnModified(LyricTableMap::COL_POPULARITY)) {
            $criteria->add(LyricTableMap::COL_POPULARITY, $this->popularity);
        }
        if ($this->isColumnModified(LyricTableMap::COL_VOTES_COUNT)) {
            $criteria->add(LyricTableMap::COL_VOTES_COUNT, $this->votes_count);
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_YOUTUBE)) {
            $criteria->add(LyricTableMap::COL_VIDEO_YOUTUBE, $this->video_youtube);
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_VBOX7)) {
            $criteria->add(LyricTableMap::COL_VIDEO_VBOX7, $this->video_vbox7);
        }
        if ($this->isColumnModified(LyricTableMap::COL_VIDEO_METACAFE)) {
            $criteria->add(LyricTableMap::COL_VIDEO_METACAFE, $this->video_metacafe);
        }
        if ($this->isColumnModified(LyricTableMap::COL_DOWNLOAD)) {
            $criteria->add(LyricTableMap::COL_DOWNLOAD, $this->download);
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
        $criteria = ChildLyricQuery::create();
        $criteria->add(LyricTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \Tekstove\ApiBundle\Model\Lyric (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setText($this->getText());
        $copyObj->settextBg($this->gettextBg());
        $copyObj->settextBgAdded($this->gettextBgAdded());
        $copyObj->setextraInfo($this->getextraInfo());
        $copyObj->setsendBy($this->getsendBy());
        $copyObj->setcacheTitleShort($this->getcacheTitleShort());
        $copyObj->setcacheCensor($this->getcacheCensor());
        $copyObj->setcacheCensorUpdated($this->getcacheCensorUpdated());
        $copyObj->setViews($this->getViews());
        $copyObj->setPopularity($this->getPopularity());
        $copyObj->setvotesCount($this->getvotesCount());
        $copyObj->setvideoYoutube($this->getvideoYoutube());
        $copyObj->setvideoVbox7($this->getvideoVbox7());
        $copyObj->setvideoMetacafe($this->getvideoMetacafe());
        $copyObj->setdownload($this->getdownload());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArtistLyrics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArtistLyric($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getLyricLanguages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addLyricLanguage($relObj->copy($deepCopy));
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
     * @return \Tekstove\ApiBundle\Model\Lyric Clone of current object.
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
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
            $v->addLyric($this);
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
        if ($this->aUser === null && ($this->send_by !== null)) {
            $this->aUser = ChildUserQuery::create()->findPk($this->send_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addLyrics($this);
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
            return $this->initArtistLyrics();
        }
        if ('LyricLanguage' == $relationName) {
            return $this->initLyricLanguages();
        }
        if ('LyricTranslation' == $relationName) {
            return $this->initLyricTranslations();
        }
        if ('LyricVote' == $relationName) {
            return $this->initLyricVotes();
        }
        if ('AlbumLyric' == $relationName) {
            return $this->initAlbumLyrics();
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
     * If this ChildLyric is new, it will return
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
                    ->filterByLyric($this)
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
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $artistLyricRemoved->setLyric(null);
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
                ->filterByLyric($this)
                ->count($con);
        }

        return count($this->collArtistLyrics);
    }

    /**
     * Method called to associate a ArtistLyric object to this object
     * through the ArtistLyric foreign key attribute.
     *
     * @param  ArtistLyric $l ArtistLyric
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
        $artistLyric->setLyric($this);
    }

    /**
     * @param  ArtistLyric $artistLyric The ArtistLyric object to remove.
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $artistLyric->setLyric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lyric is new, it will return
     * an empty collection; or if this Lyric has previously
     * been saved, it will retrieve related ArtistLyrics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lyric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ArtistLyric[] List of ArtistLyric objects
     */
    public function getArtistLyricsJoinArtist(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ArtistLyricQuery::create(null, $criteria);
        $query->joinWith('Artist', $joinBehavior);

        return $this->getArtistLyrics($query, $con);
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
     * If this ChildLyric is new, it will return
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
                    ->filterByLyric($this)
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
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $lyricLanguageRemoved->setLyric(null);
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
                ->filterByLyric($this)
                ->count($con);
        }

        return count($this->collLyricLanguages);
    }

    /**
     * Method called to associate a LyricLanguage object to this object
     * through the LyricLanguage foreign key attribute.
     *
     * @param  LyricLanguage $l LyricLanguage
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
        $lyricLanguage->setLyric($this);
    }

    /**
     * @param  LyricLanguage $lyricLanguage The LyricLanguage object to remove.
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $lyricLanguage->setLyric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lyric is new, it will return
     * an empty collection; or if this Lyric has previously
     * been saved, it will retrieve related LyricLanguages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lyric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricLanguage[] List of LyricLanguage objects
     */
    public function getLyricLanguagesJoinLanguage(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricLanguageQuery::create(null, $criteria);
        $query->joinWith('Language', $joinBehavior);

        return $this->getLyricLanguages($query, $con);
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
     * If this ChildLyric is new, it will return
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
                    ->filterByLyric($this)
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
     * @return $this|ChildLyric The current object (for fluent API support)
     */
    public function setLyricTranslations(Collection $lyricTranslations, ConnectionInterface $con = null)
    {
        /** @var LyricTranslation[] $lyricTranslationsToDelete */
        $lyricTranslationsToDelete = $this->getLyricTranslations(new Criteria(), $con)->diff($lyricTranslations);


        $this->lyricTranslationsScheduledForDeletion = $lyricTranslationsToDelete;

        foreach ($lyricTranslationsToDelete as $lyricTranslationRemoved) {
            $lyricTranslationRemoved->setLyric(null);
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
                ->filterByLyric($this)
                ->count($con);
        }

        return count($this->collLyricTranslations);
    }

    /**
     * Method called to associate a LyricTranslation object to this object
     * through the LyricTranslation foreign key attribute.
     *
     * @param  LyricTranslation $l LyricTranslation
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
        $lyricTranslation->setLyric($this);
    }

    /**
     * @param  LyricTranslation $lyricTranslation The LyricTranslation object to remove.
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $lyricTranslation->setLyric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lyric is new, it will return
     * an empty collection; or if this Lyric has previously
     * been saved, it will retrieve related LyricTranslations from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lyric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricTranslation[] List of LyricTranslation objects
     */
    public function getLyricTranslationsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricTranslationQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

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
     * If this ChildLyric is new, it will return
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
                    ->filterByLyric($this)
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
     * @return $this|ChildLyric The current object (for fluent API support)
     */
    public function setLyricVotes(Collection $lyricVotes, ConnectionInterface $con = null)
    {
        /** @var LyricVote[] $lyricVotesToDelete */
        $lyricVotesToDelete = $this->getLyricVotes(new Criteria(), $con)->diff($lyricVotes);


        $this->lyricVotesScheduledForDeletion = $lyricVotesToDelete;

        foreach ($lyricVotesToDelete as $lyricVoteRemoved) {
            $lyricVoteRemoved->setLyric(null);
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
                ->filterByLyric($this)
                ->count($con);
        }

        return count($this->collLyricVotes);
    }

    /**
     * Method called to associate a LyricVote object to this object
     * through the LyricVote foreign key attribute.
     *
     * @param  LyricVote $l LyricVote
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
        $lyricVote->setLyric($this);
    }

    /**
     * @param  LyricVote $lyricVote The LyricVote object to remove.
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $lyricVote->setLyric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lyric is new, it will return
     * an empty collection; or if this Lyric has previously
     * been saved, it will retrieve related LyricVotes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lyric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|LyricVote[] List of LyricVote objects
     */
    public function getLyricVotesJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = LyricVoteQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getLyricVotes($query, $con);
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
     * If this ChildLyric is new, it will return
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
                    ->filterByLyric($this)
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
     * @return $this|ChildLyric The current object (for fluent API support)
     */
    public function setAlbumLyrics(Collection $albumLyrics, ConnectionInterface $con = null)
    {
        /** @var ChildAlbumLyric[] $albumLyricsToDelete */
        $albumLyricsToDelete = $this->getAlbumLyrics(new Criteria(), $con)->diff($albumLyrics);


        $this->albumLyricsScheduledForDeletion = $albumLyricsToDelete;

        foreach ($albumLyricsToDelete as $albumLyricRemoved) {
            $albumLyricRemoved->setLyric(null);
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
                ->filterByLyric($this)
                ->count($con);
        }

        return count($this->collAlbumLyrics);
    }

    /**
     * Method called to associate a ChildAlbumLyric object to this object
     * through the ChildAlbumLyric foreign key attribute.
     *
     * @param  ChildAlbumLyric $l ChildAlbumLyric
     * @return $this|\Tekstove\ApiBundle\Model\Lyric The current object (for fluent API support)
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
        $albumLyric->setLyric($this);
    }

    /**
     * @param  ChildAlbumLyric $albumLyric The ChildAlbumLyric object to remove.
     * @return $this|ChildLyric The current object (for fluent API support)
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
            $this->albumLyricsScheduledForDeletion[]= $albumLyric;
            $albumLyric->setLyric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Lyric is new, it will return
     * an empty collection; or if this Lyric has previously
     * been saved, it will retrieve related AlbumLyrics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Lyric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildAlbumLyric[] List of ChildAlbumLyric objects
     */
    public function getAlbumLyricsJoinAlbum(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildAlbumLyricQuery::create(null, $criteria);
        $query->joinWith('Album', $joinBehavior);

        return $this->getAlbumLyrics($query, $con);
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
     * Initializes the collArtists crossRef collection.
     *
     * By default this just sets the collArtists collection to an empty collection (like clearArtists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initArtists()
    {
        $collectionClassName = ArtistLyricTableMap::getTableMap()->getCollectionClassName();

        $this->collArtists = new $collectionClassName;
        $this->collArtistsPartial = true;
        $this->collArtists->setModel('\Tekstove\ApiBundle\Model\Artist');
    }

    /**
     * Checks if the collArtists collection is loaded.
     *
     * @return bool
     */
    public function isArtistsLoaded()
    {
        return null !== $this->collArtists;
    }

    /**
     * Gets a collection of ChildArtist objects related by a many-to-many relationship
     * to the current object by way of the artist_lyric cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLyric is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildArtist[] List of ChildArtist objects
     */
    public function getArtists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistsPartial && !$this->isNew();
        if (null === $this->collArtists || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collArtists) {
                    $this->initArtists();
                }
            } else {

                $query = ChildArtistQuery::create(null, $criteria)
                    ->filterByLyric($this);
                $collArtists = $query->find($con);
                if (null !== $criteria) {
                    return $collArtists;
                }

                if ($partial && $this->collArtists) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collArtists as $obj) {
                        if (!$collArtists->contains($obj)) {
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
     * Sets a collection of Artist objects related by a many-to-many relationship
     * to the current object by way of the artist_lyric cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $artists A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLyric The current object (for fluent API support)
     */
    public function setArtists(Collection $artists, ConnectionInterface $con = null)
    {
        $this->clearArtists();
        $currentArtists = $this->getArtists();

        $artistsScheduledForDeletion = $currentArtists->diff($artists);

        foreach ($artistsScheduledForDeletion as $toDelete) {
            $this->removeArtist($toDelete);
        }

        foreach ($artists as $artist) {
            if (!$currentArtists->contains($artist)) {
                $this->doAddArtist($artist);
            }
        }

        $this->collArtistsPartial = false;
        $this->collArtists = $artists;

        return $this;
    }

    /**
     * Gets the number of Artist objects related by a many-to-many relationship
     * to the current object by way of the artist_lyric cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Artist objects
     */
    public function countArtists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collArtistsPartial && !$this->isNew();
        if (null === $this->collArtists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArtists) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getArtists());
                }

                $query = ChildArtistQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLyric($this)
                    ->count($con);
            }
        } else {
            return count($this->collArtists);
        }
    }

    /**
     * Associate a ChildArtist to this object
     * through the artist_lyric cross reference table.
     *
     * @param ChildArtist $artist
     * @return ChildLyric The current object (for fluent API support)
     */
    public function addArtist(ChildArtist $artist)
    {
        if ($this->collArtists === null) {
            $this->initArtists();
        }

        if (!$this->getArtists()->contains($artist)) {
            // only add it if the **same** object is not already associated
            $this->collArtists->push($artist);
            $this->doAddArtist($artist);
        }

        return $this;
    }

    /**
     *
     * @param ChildArtist $artist
     */
    protected function doAddArtist(ChildArtist $artist)
    {
        $artistLyric = new ArtistLyric();

        $artistLyric->setArtist($artist);

        $artistLyric->setLyric($this);

        $this->addArtistLyric($artistLyric);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$artist->isLyricsLoaded()) {
            $artist->initLyrics();
            $artist->getLyrics()->push($this);
        } elseif (!$artist->getLyrics()->contains($this)) {
            $artist->getLyrics()->push($this);
        }

    }

    /**
     * Remove artist of this object
     * through the artist_lyric cross reference table.
     *
     * @param ChildArtist $artist
     * @return ChildLyric The current object (for fluent API support)
     */
    public function removeArtist(ChildArtist $artist)
    {
        if ($this->getArtists()->contains($artist)) {
            $artistLyric = new ArtistLyric();
            $artistLyric->setArtist($artist);
            if ($artist->isLyricsLoaded()) {
                //remove the back reference if available
                $artist->getLyrics()->removeObject($this);
            }

            $artistLyric->setLyric($this);
            $this->removeArtistLyric(clone $artistLyric);
            $artistLyric->clear();

            $this->collArtists->remove($this->collArtists->search($artist));

            if (null === $this->artistsScheduledForDeletion) {
                $this->artistsScheduledForDeletion = clone $this->collArtists;
                $this->artistsScheduledForDeletion->clear();
            }

            $this->artistsScheduledForDeletion->push($artist);
        }


        return $this;
    }

    /**
     * Clears out the collLanguages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addLanguages()
     */
    public function clearLanguages()
    {
        $this->collLanguages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collLanguages crossRef collection.
     *
     * By default this just sets the collLanguages collection to an empty collection (like clearLanguages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initLanguages()
    {
        $collectionClassName = LyricLanguageTableMap::getTableMap()->getCollectionClassName();

        $this->collLanguages = new $collectionClassName;
        $this->collLanguagesPartial = true;
        $this->collLanguages->setModel('\Tekstove\ApiBundle\Model\Language');
    }

    /**
     * Checks if the collLanguages collection is loaded.
     *
     * @return bool
     */
    public function isLanguagesLoaded()
    {
        return null !== $this->collLanguages;
    }

    /**
     * Gets a collection of ChildLanguage objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildLyric is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildLanguage[] List of ChildLanguage objects
     */
    public function getLanguages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguagesPartial && !$this->isNew();
        if (null === $this->collLanguages || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collLanguages) {
                    $this->initLanguages();
                }
            } else {

                $query = ChildLanguageQuery::create(null, $criteria)
                    ->filterByLyric($this);
                $collLanguages = $query->find($con);
                if (null !== $criteria) {
                    return $collLanguages;
                }

                if ($partial && $this->collLanguages) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collLanguages as $obj) {
                        if (!$collLanguages->contains($obj)) {
                            $collLanguages[] = $obj;
                        }
                    }
                }

                $this->collLanguages = $collLanguages;
                $this->collLanguagesPartial = false;
            }
        }

        return $this->collLanguages;
    }

    /**
     * Sets a collection of Language objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $languages A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildLyric The current object (for fluent API support)
     */
    public function setLanguages(Collection $languages, ConnectionInterface $con = null)
    {
        $this->clearLanguages();
        $currentLanguages = $this->getLanguages();

        $languagesScheduledForDeletion = $currentLanguages->diff($languages);

        foreach ($languagesScheduledForDeletion as $toDelete) {
            $this->removeLanguage($toDelete);
        }

        foreach ($languages as $language) {
            if (!$currentLanguages->contains($language)) {
                $this->doAddLanguage($language);
            }
        }

        $this->collLanguagesPartial = false;
        $this->collLanguages = $languages;

        return $this;
    }

    /**
     * Gets the number of Language objects related by a many-to-many relationship
     * to the current object by way of the lyric_language cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Language objects
     */
    public function countLanguages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collLanguagesPartial && !$this->isNew();
        if (null === $this->collLanguages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collLanguages) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getLanguages());
                }

                $query = ChildLanguageQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByLyric($this)
                    ->count($con);
            }
        } else {
            return count($this->collLanguages);
        }
    }

    /**
     * Associate a ChildLanguage to this object
     * through the lyric_language cross reference table.
     *
     * @param ChildLanguage $language
     * @return ChildLyric The current object (for fluent API support)
     */
    public function addLanguage(ChildLanguage $language)
    {
        if ($this->collLanguages === null) {
            $this->initLanguages();
        }

        if (!$this->getLanguages()->contains($language)) {
            // only add it if the **same** object is not already associated
            $this->collLanguages->push($language);
            $this->doAddLanguage($language);
        }

        return $this;
    }

    /**
     *
     * @param ChildLanguage $language
     */
    protected function doAddLanguage(ChildLanguage $language)
    {
        $lyricLanguage = new LyricLanguage();

        $lyricLanguage->setLanguage($language);

        $lyricLanguage->setLyric($this);

        $this->addLyricLanguage($lyricLanguage);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$language->isLyricsLoaded()) {
            $language->initLyrics();
            $language->getLyrics()->push($this);
        } elseif (!$language->getLyrics()->contains($this)) {
            $language->getLyrics()->push($this);
        }

    }

    /**
     * Remove language of this object
     * through the lyric_language cross reference table.
     *
     * @param ChildLanguage $language
     * @return ChildLyric The current object (for fluent API support)
     */
    public function removeLanguage(ChildLanguage $language)
    {
        if ($this->getLanguages()->contains($language)) {
            $lyricLanguage = new LyricLanguage();
            $lyricLanguage->setLanguage($language);
            if ($language->isLyricsLoaded()) {
                //remove the back reference if available
                $language->getLyrics()->removeObject($this);
            }

            $lyricLanguage->setLyric($this);
            $this->removeLyricLanguage(clone $lyricLanguage);
            $lyricLanguage->clear();

            $this->collLanguages->remove($this->collLanguages->search($language));

            if (null === $this->languagesScheduledForDeletion) {
                $this->languagesScheduledForDeletion = clone $this->collLanguages;
                $this->languagesScheduledForDeletion->clear();
            }

            $this->languagesScheduledForDeletion->push($language);
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
            $this->aUser->removeLyric($this);
        }
        $this->id = null;
        $this->title = null;
        $this->text = null;
        $this->text_bg = null;
        $this->text_bg_added = null;
        $this->extra_info = null;
        $this->send_by = null;
        $this->cache_title_short = null;
        $this->cache_censor = null;
        $this->cache_censor_updated = null;
        $this->views = null;
        $this->popularity = null;
        $this->votes_count = null;
        $this->video_youtube = null;
        $this->video_vbox7 = null;
        $this->video_metacafe = null;
        $this->download = null;
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
            if ($this->collLyricLanguages) {
                foreach ($this->collLyricLanguages as $o) {
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
            if ($this->collAlbumLyrics) {
                foreach ($this->collAlbumLyrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArtists) {
                foreach ($this->collArtists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collLanguages) {
                foreach ($this->collLanguages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArtistLyrics = null;
        $this->collLyricLanguages = null;
        $this->collLyricTranslations = null;
        $this->collLyricVotes = null;
        $this->collAlbumLyrics = null;
        $this->collArtists = null;
        $this->collLanguages = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(LyricTableMap::DEFAULT_STRING_FORMAT);
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
        $metadata->addPropertyConstraint('text', new NotBlank());
        $metadata->addPropertyConstraint('text', new Length(array ('min' => 10,)));
        $metadata->addPropertyConstraint('title', new NotBlank());
        $metadata->addPropertyConstraint('title', new Length(array ('max' => 60,)));
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
            if (null !== $this->collLyricLanguages) {
                foreach ($this->collLyricLanguages as $referrerFK) {
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
            if (null !== $this->collAlbumLyrics) {
                foreach ($this->collAlbumLyrics as $referrerFK) {
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
