<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Propel\Runtime\ActiveQuery\Criteria;
use Tekstove\ApiBundle\Model\LyricQuery;

class UpdateCensorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstoveApi:updateCensor')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = new LyricQuery();
        $query->filterBycacheCensorUpdated(
            (new \DateTime('@' . (time() - 60*60*24*30)))->format('Y-m-d'),
            Criteria::LESS_THAN
        );
        $query->orderBy('cache_censor_updated');
        $query->limit(1000 * 10);
        $lyrics = $query->find();
        $lyricRepo = $this->getContainer()->get('tekstove.lyric.repository');
        foreach ($lyrics as $lyric) {
            $output->writeln("Processing {$lyric->getId()}");
            $lyricRepo->save($lyric);
        }
    }
}
