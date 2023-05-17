<?php

namespace App\Command;

use App\Entity\Product;
use App\Service\ImportProductsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-products',
    description: 'Import products and its numbers from a CSV file into the database',
)]
class ImportProductsCommand extends Command
{
    private ImportProductsService $importProductsService;

    /**
     * @param ImportProductsService $importProductsService
     */
    public function __construct(ImportProductsService $importProductsService)
    {
        $this->importProductsService = $importProductsService;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File with products data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if (!$file) {
            $io->note(sprintf('You passed an argument: %s', $file));
        }
        $filePath = 'public/csv/'.$file;

        $io->success($this->importProductsService->createProductsFromFile($filePath));


        return Command::SUCCESS;
    }
}
