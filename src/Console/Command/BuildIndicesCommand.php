<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Console\Command;

use LarsNieuwenhuizen\EsConnector\Service\IndexManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class BuildIndicesCommand extends Command
{

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('search:build-indices')
            ->setDescription('(Re)construct indices for Elasticsearch for all index eligible modules');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
        $output->title('Create elasticsearch indices');

        $logger = new ConsoleLogger($output);

        $indexManager = new IndexManager($logger);
        $indexManager->createIndices(time() . '-');
    }
}
