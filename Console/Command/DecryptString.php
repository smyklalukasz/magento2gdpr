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
use Adfab\Gdpr\Helper\Cipher;

class DecryptString extends Command
{

    /**
     *
     * @var Cipher
     */
    protected $cipher;

    /**
     *
     * @param Cipher $cipher
     */
    public function __construct( Cipher $cipher ) {
        $this->cipher= $cipher;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $string = $input->getArgument('string');
        $clear = $this->cipher->decipher($string);
        $output->writeln($string.' : '.$clear);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("gdpr:decryptstring");
        $this->setDescription("Decrypt a string");
        $this->setDefinition([
            new InputArgument('string', InputArgument::REQUIRED)
        ]);
        parent::configure();
    }
}
