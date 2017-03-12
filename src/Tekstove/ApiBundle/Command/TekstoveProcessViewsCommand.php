<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tekstove\ApiBundle\Model\Map\LyricTableMap;

class TekstoveProcessViewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstove:process:views')
            ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $redis = $this->getContainer()->get('tekstove.api.lyric.count.redis');
        /* @var $redis \Predis\Client */

        $con = \Propel\Runtime\Propel::getWriteConnection(\Tekstove\ApiBundle\Model\Map\LyricTableMap::DATABASE_NAME);
        $sql = "
            UPDATE
                lyric
            SET
                " . LyricTableMap::COL_VIEWS . " = " . LyricTableMap::COL_VIEWS . " + :viewCount,
                " . LyricTableMap::COL_POPULARITY . " = " . LyricTableMap::COL_POPULARITY . " + :popularityCount
            WHERE
                id = :lyricId
        ";
        $stm = $con->prepare($sql);

        do {
            $lyricsToProcess = $redis->spop('lyric.views', 50);
            if (empty($lyricsToProcess)) {
                break;
            }

            $keysToDelete = [];
            foreach ($lyricsToProcess as $lyricId) {
                if ($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                    $output->writeln("Processing {$lyricId}");
                }

                $viewsCountKey = 'lyric.views.' . $lyricId;
                $viewsCount = $redis->scard($viewsCountKey);

                $stm->execute([
                    'viewCount' => $viewsCount,
                    'popularityCount' => $viewsCount,
                    'lyricId' => $lyricId,
                ]);

                $keysToDelete[] = $viewsCountKey;
            }

            if (!empty($keysToDelete)) {
                $redis->del($keysToDelete);
            }
        } while (!empty($lyricsToProcess));

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
            $output->writeln('Exit 0');
        }
    }
}
