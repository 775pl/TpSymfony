<?php

namespace App\Command;

use App\Repository\TourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'tour:edit',
    description: 'To edit a tour with his id',
)]
class TourModifyCommand extends Command
{
    private $tourRepository;
    private $entityManager;

    public function __construct(TourRepository $tourRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->tourRepository = $tourRepository;
        $this->entityManager = $entityManager;
    }
    protected function configure(): void
    {
        $this
            ->addOption('tourId', null, InputOption::VALUE_REQUIRED, 'modify tour by this id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $tourRepo = $this->tourRepository;

        $tourId = $input->getOption('tourId');
        $tour = $tourRepo->find($tourId);

        if($tour){
            $newMainEvent = $io->ask("New main event :", $tour->getMainEvent());
            $tour->setMainEvent($newMainEvent);


            $this->entityManager->flush();

        }else{
            $io->error("No tour");
        }

        $io->success('The tour has been successfully edited');
        return Command::SUCCESS;

    }
}
