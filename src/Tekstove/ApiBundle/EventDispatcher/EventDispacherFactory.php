<?php

namespace Tekstove\ApiBundle\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tekstove\ApiBundle\EventListener\Model\Lyric\LyricTitleCacheSubscriber;
use Tekstove\ApiBundle\EventListener\Model\Lyric\LyricUploadedBySubscriber;

/**
 * Description of EventDispacherFactory
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class EventDispacherFactory
{
    public static function createDispacher(ContainerInterface $container)
    {
        $dispacher = new EventDispacher();
        // add events!
        $titleCacheSubscriber = new LyricTitleCacheSubscriber();

        $securityTokenStorage = $container->get('security.token_storage');
        $authzChecker = $container->get('security.authorization_checker');
        
        $uploadedBySubscriber = new LyricUploadedBySubscriber(
            $securityTokenStorage,
            $authzChecker
        );
       
        $dispacher->addSubscriber($titleCacheSubscriber);
        $dispacher->addSubscriber($uploadedBySubscriber);
        $dispacher->addSubscriber(self::createContentChecker($container));
        return $dispacher;
    }
    
    static function createContentChecker(ContainerInterface $container)
    {
        // THIS IS UGLY!!!
        
        $kernelPath = $container->get('kernel')->getRootDir();
        $dictionariesDir = $kernelPath . '/../vendor/tekstove/content-checker/Dictionaries/';
        
        $checker = new \Tekstove\ContentChecker\Checker\RegExpChecker([]);
        foreach (['Bg/Data.txt', 'En/Data.txt'] as $relativeDictionaryPath) {
            $dictionaryText = trim(file_get_contents($dictionariesDir . $relativeDictionaryPath));
            $words = explode("\n", $dictionaryText);
            $dictionary = new \Tekstove\ContentChecker\Dictionary\SimpleDictionary($words);
            $checker->addDictionary($dictionary);
        }
        return new \Tekstove\ApiBundle\EventListener\Model\Lyric\LyricCensorCacheSubscriber($checker);
    }
}
