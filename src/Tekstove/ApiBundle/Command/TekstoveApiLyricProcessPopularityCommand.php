<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TekstoveApiLyricProcessPopularityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstoveApi:lyric:process-popularity')
            ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $con = \Propel\Runtime\Propel::getWriteConnection(\Tekstove\ApiBundle\Model\Map\LyricTableMap::DATABASE_NAME);
        $sql = "
            UPDATE
                lyric
            SET
                popularity = popularity / 2
            WHERE
                popularity > 0
        ";
        $stm = $con->prepare($sql);
        $stm->execute();

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $output->writeln("Status: ok");
        }
    }
}
