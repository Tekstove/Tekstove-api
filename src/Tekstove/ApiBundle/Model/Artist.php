<?php

namespace Tekstove\ApiBundle\Model;

use Tekstove\ApiBundle\Model\Base\Artist as BaseArtist;
use Tekstove\ApiBundle\Model\Artist\Exception\ArtistHumanReadableException;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'artist' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Artist extends BaseArtist implements Acl\EditableInterface, Acl\AutoAclSerializableInterface
{
    use AclTrait;
    use \Tekstove\ApiBundle\Validator\ValidationAwareTrait;
    use \Tekstove\ApiBundle\EventDispatcher\EventDispatcherAwareTrait;

    public function preSave(ConnectionInterface $con = null)
    {
        if (!$this->validate($this->getValidator())) {
            $errors = $this->getValidationFailures();
            $exception = new ArtistHumanReadableException('Validation failed.');
            foreach ($errors as $error) {
                /* @var $error \Symfony\Component\Validator\ConstraintViolationInterface */
                $exception->addError($error->getPropertyPath(), $error->getMessage());
            }
            throw $exception;
        }

        // $this->notifyPreSave($this);

        return parent::preSave($con);
    }


    /**
     * @return Album[]
     */
    public function getAlbums()
    {
        $return = [];
        foreach ($this->getAlbumArtists() as $albumArtist) {
            $return[] = $albumArtist->getAlbum();
        }
        return $return;
    }
}
