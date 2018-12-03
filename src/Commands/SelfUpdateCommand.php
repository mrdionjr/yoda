<?php

namespace Yoda\Commands;

use Humbug\SelfUpdate\Updater;
use PHAR;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class SelfUpdateCommand extends Command
{
    private $updater;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->updater = new Updater(dirname(__DIR__, 2) . '/bin/yoda.phar');
        $this->updater->setStrategy(Updater::STRATEGY_GITHUB);
        $this->updater->getStrategy()->setPackageName('mrdion/yoda');
        $this->updater->getStrategy()->setPharName('yoda.phar');
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this
            ->setName('self-update')
            ->setDescription(sprintf(
                'Update %s to most recent stable build.',
                $this->getLocalPharName()
            ))
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->updater->update();

        if ($result) {
            $io->success(
                sprintf(
                    'Your PHAR has been updated from "%s" to "%s".',
                    $this->updater->getOldVersion(),
                    $this->updater->getNewVersion()
                )
            );
        } else {
            $io->success('Your PHAR is already up to date.');
        }

        return 0;
    }

    private function getLocalPharName(): string
    {
        return basename(PHAR::running());
    }
}
