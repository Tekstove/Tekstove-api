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
        $desctiption = 'When entity namespace is explicity set.' .
                'Then source is generated in root.' .
                'This fix the issue'
        ;
        
        $this
            ->setName('tekstoveApi:propelModelFix')
            ->setDescription($desctiption)
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
        
        $this->handleFinder($finder, $output, $projectHome);
        
        $finderNewFiles = new \Symfony\Component\Finder\Finder();
        $finderNewFiles->in($projectHome . '/Tekstove');
        $finderNewFiles->name('*.php');
        $this->handleFinder($finderNewFiles, $output, $projectHome, false);
        
        
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove($projectHome . '/Tekstove');
        $output->writeln('<info>Command result.</info>');
    }
    
    private function handleFinder($finder, $output, $projectHome, $replace = true)
    {
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
                if ($replace) {
                    $output->writeln("<comment>{$replacePath} will be replaced</comment>");
                    $this->moveFile($path, $replacePath);
                } elseif ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE) {
                    $output->writeln("Skipping {$replacePath}");
                }
            } else {
                $output->writeln("<info>{$replacePath} will be created</info>");
                $this->moveFile($path, $replacePath);
            }
        }
    }
    
    protected function moveFile($original, $destination)
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem;
        
        $fileDir = dirname($destination);
        if (!$fs->exists($fileDir)) {
            $fs->mkdir($fileDir);
        }
        $fs->rename($original, $destination, true);
    }
}
