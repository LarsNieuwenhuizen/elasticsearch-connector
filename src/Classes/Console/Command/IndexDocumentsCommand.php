<?php
declare(strict_types = 1);

namespace LarsNieuwenhuizen\EsConnector\Console\Command;

use LarsNieuwenhuizen\EsConnector\Service\DocumentIndexer;
use LarsNieuwenhuizen\EsConnector\Service\IndexManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class IndexDocumentsCommand extends Command
{

    /**
     * @var DocumentIndexer
     */
    private $documentNodeIndexer;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('search:index-documents')
            ->setDescription('Index all created documents');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $cliOutput = new SymfonyStyle($input, $output);
        $cliOutput->title('Index documents');
        $logger = new ConsoleLogger($output);

        $indexManager = new IndexManager($logger);

        try {
            $now = new \DateTimeImmutable();
            $indexPrefix = (string)$now->getTimestamp() . '-';

            $logger->info('Create fresh set of indices');
            $indexManager->createIndices($indexPrefix);

            $documentNodeIndexer = new DocumentIndexer($indexPrefix, $logger);
            $logger->info('Index documents');
            $documentNodeIndexer->indexDocuments();

            $logger->info('Switch aliases');
            $indexManager->switchAliases($indexPrefix);

            $cliOutput->success('Done');
        } catch (\Exception $exception) {
            $cliOutput->error('Something went wrong, check the logs for details');
        }

        $logger->info('Purge stale indices');
        $indexManager->purgeStaleIndices();
    }
}
