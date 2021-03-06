<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 27.11.2018 10:18
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateAssetsCommand extends Command
{
    private $applicationName = '';

    private $assets = [];

    protected function configure()
    {
        $this
            ->setName('update:assets')
            ->setDescription('move assets files to public folder from vendor.')
            ->setHelp('move assets files to public folder from vendor.')
        ;
    }

    protected function setup(SymfonyStyle $io)
    {
        $initPath = getcwd().'/init.php';
        if (!@file_exists($initPath)) {
            $io->error("It must be the init.php file in root folder. Look: README.md");
            exit(1);
        }
        $settings = include $initPath;
        $this->assets = $settings['update-assets'];
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == "") {
            $io->error("It must write project name in the init.php file. For example: `application-name'=>'PROJECT_NAME' `
             Look: README.md");
            exit(1);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('update-assets');

        $this->setup($io);

        foreach ($this->assets as $name => $asset) {
            $io->section($name);
            copyFiles($asset['files'], $asset['public'], $asset['vendor']);
            $io->success('ok');
        }
    }
}
