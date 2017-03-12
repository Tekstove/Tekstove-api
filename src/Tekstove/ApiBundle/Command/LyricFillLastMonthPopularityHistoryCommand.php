<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\Lyric\LyricTopPopularity;

class LyricFillLastMonthPopularityHistoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstoveApi:lyric:fillLastMonthPopularityHistory')
            ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lyricQuery = new \Tekstove\ApiBundle\Model\LyricQuery();
        $lyricQuery->setLimit(100);
        $lyricQuery->orderByPopularity(Criteria::DESC);
        $lyricQuery->orderById(Criteria::DESC);

        $topLyrics = $lyricQuery->find();

        $date = new \DateTime('0:00 first day of previous month');

        foreach ($topLyrics as $lyric) {
            /* @var $lyric \Tekstove\ApiBundle\Model\Lyric */
            $popularityHistory = new LyricTopPopularity();
            $popularityHistory->setLyric($lyric);
            $popularityHistory->setPopularity($lyric->getPopularity());
            $popularityHistory->setDate($date);
            $popularityHistory->save();
        }

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
            $output->writeln('Command result.');
        }
    }
}
