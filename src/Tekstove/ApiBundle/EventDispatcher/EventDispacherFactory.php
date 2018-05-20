<?php

namespace Tekstove\ApiBundle\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tekstove\ApiBundle\EventListener\Model\Lyric\LyricTitleCacheSubscriber;
use Tekstove\ApiBundle\EventListener\Model\Lyric\LyricUploadedBySubscriber;
use Tekstove\ApiBundle\EventListener\Model\Lyric\VideoParserSubscriber;
use Tekstove\ApiBundle\EventListener\Model\Chat\MessageValidateSafeTextSubscriber;

/**
 * @author po_taka <angel.koilov@gmail.com>
 */
class EventDispacherFactory
{
    public function registerSubscribers(\Symfony\Component\DependencyInjection\Container $container)
    {
        $dispacher = $container->get('event_dispatcher');

        // add events!
        $titleCacheSubscriber = new LyricTitleCacheSubscriber();

        $securityTokenStorage = $container->get('security.token_storage');

        $uploadedBySubscriber = new LyricUploadedBySubscriber($securityTokenStorage);

        $dispacher->addSubscriber($titleCacheSubscriber);
        $dispacher->addSubscriber($uploadedBySubscriber);
        $dispacher->addSubscriber(new \Tekstove\ApiBundle\EventListener\Model\Lyric\LyricAntiSpamSubscriber());
        $dispacher->addSubscriber(self::createContentChecker($container));
        $dispacher->addSubscriber(new VideoParserSubscriber());

        $messageHtmlSubscriber= $container->get('app.chat.message_html_subscriber');
        $dispacher->addSubscriber($messageHtmlSubscriber);

        $dispacher->addSubscriber(new MessageValidateSafeTextSubscriber(self::createChatContentChecker($container)));
        $dispacher->addSubscriber(
            $container->get('app.lyric.count_subscriber')
        );
        $dispacher->addSubscriber(
            $container->get('app.forum.post.html_subscriber')
        );

        $dispacher->addSubscriber(
            new \Tekstove\ApiBundle\EventListener\Model\Forum\PostTopicLastActionSubscriber(
                $container
            )
        );
        return $dispacher;
    }

    protected static function createChatContentChecker(ContainerInterface $container)
    {
        $kernelPath = $container->get('kernel')->getRootDir();
        $dictionariesDir = $kernelPath . '/../vendor/tekstove/content-checker/Dictionaries/';

        $checker = new \Tekstove\ContentChecker\Checker\ExactChecker();
        foreach (['sites.txt'] as $relativeDictionaryPath) {
            $dictionaryText = trim(file_get_contents($dictionariesDir . $relativeDictionaryPath));
            $words = explode("\n", $dictionaryText);
            $dictionary = new \Tekstove\ContentChecker\Dictionary\SimpleDictionary($words);
            $checker->addDictionary($dictionary);
        }

        return $checker;
    }

    protected static function createContentChecker(ContainerInterface $container)
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
