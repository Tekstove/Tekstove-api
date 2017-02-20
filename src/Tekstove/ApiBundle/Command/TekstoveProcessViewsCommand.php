<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
                " . \Tekstove\ApiBundle\Model\Map\LyricTableMap::COL_VIEWS . " = " . \Tekstove\ApiBundle\Model\Map\LyricTableMap::COL_VIEWS . " + :viewCount
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
