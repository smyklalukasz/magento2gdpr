<?php
/**
 * Copyright (C) 2017 Adfab
 *
 * This file is part of Adfab/Gdpr.
 */

namespace Adfab\Gdpr\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Crypt extends Command
{


    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        foreach ( [] as $collection ) {
            $collection->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("gdpr:crypt");
        $this->setDescription("Crypt all personnal data");
        $this->setDefinition([
        ]);
        parent::configure();
    }
}
