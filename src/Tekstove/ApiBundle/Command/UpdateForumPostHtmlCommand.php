<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tekstove\ApiBundle\Model\Forum\Map\PostTableMap;

class UpdateForumPostHtmlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstoveApi:update-forum-post-html')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $con = \Propel\Runtime\Propel::getWriteConnection(\Tekstove\ApiBundle\Model\Map\LyricTableMap::DATABASE_NAME);

        $countSql = "
            SELECT
                COUNT(id)
            FROM
                forum_post
        ";

        $countStm = $con->prepare($countSql);
        $countStm->execute();
        $countData = $countStm->fetch();
        $count = $countData[0];

        $sql = "
            SELECT
                id
            FROM
                forum_post
            ORDER BY
                id
        ";
        $stm = $con->prepare($sql);
        $stm->execute();

        $progress = new \Symfony\Component\Console\Helper\ProgressBar($output, $count);

        $postRepo = $this->getContainer()->get('tekstove.forum.post.repository');

        while ($row = $stm->fetch()) {
            $output->writeln("Processing post#" . $row['id'], OutputInterface::VERBOSITY_DEBUG);

            $postQuery = new \Tekstove\ApiBundle\Model\Forum\PostQuery();
            $post = $postQuery->requireOneById($row['id']);
            $postRepo->save($post);

            $progress->advance();
        }

        $output->writeln('');
        $output->writeln('done!');
    }
}
