<?php

namespace Tekstove\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TekstoveApiPropelModelFixCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tekstoveApi:propelModelFix')
            ->setDescription('Fix propel model generation bug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = __DIR__ . '/../../../../';
        $projectHome = realpath($dir);
        $output->writeln($projectHome);
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->in($projectHome . '/Tekstove');
        $finder->name('*.php');
        $finder->path('/\/Base\//')->path('/\/Map\//');
        $files = $finder->files();
        
        $replaceBasePath = $projectHome .  '/src/';
        
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        
        foreach ($files as $file) {
            /* @var $file \SplFileInfo */
            $path = $file->getPathname();
            $relativePath = preg_replace('#^.*/(Tekstove/.*)$#', '$1', $path);
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
                $output->writeln("Relative: {$relativePath}");
                $output->writeln($path);
            }
            
            $replacePath = $replaceBasePath . $relativePath;
            if (is_file($replacePath)) {
                $output->writeln("<comment>{$replacePath} will be replaced</comment>");
            } else {
                $output->writeln("<info>{$replacePath} will be created</info>");
            }
            
            $fileDir = dirname($replacePath);
            if (!$fs->exists($fileDir)) {
                $fs->mkdir($fileDir);
            }
            $fs->rename($path, $replacePath, true);
        }
        
        $fs->remove($projectHome . '/Tekstove');
        $output->writeln('<info>Command result.</info>');
    }
}
