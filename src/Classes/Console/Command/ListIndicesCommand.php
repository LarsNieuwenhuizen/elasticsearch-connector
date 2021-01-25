<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Console\Command;

use LarsNieuwenhuizen\EsConnector\Service\Elasticsearch;
use LarsNieuwenhuizen\EsConnector\Service\IndexManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListIndicesCommand extends Command
{

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('search:list-indices')
            ->setDescription('List Elasticsearch indices');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cliOutput = new SymfonyStyle($input, $output);
        $cliOutput->title('List Elasticsearch indices');

        $table = new Table($output);
        $table->setHeaders(['index', 'aliases', 'document count']);

        $indexManager = new IndexManager();
        $elasticsearch = new Elasticsearch();

        foreach ($indexManager->getIndices() as $indexName => $index) {
            $count = $elasticsearch->getClient()->count(['index' => $indexName]);
            $table->addRow([
                $indexName,
                \implode(
                    ', ',
                    \array_keys(
                        $index['aliases']
                    )
                ),
                $count['count']
            ]);
        }

        $table->render();
    }
}
