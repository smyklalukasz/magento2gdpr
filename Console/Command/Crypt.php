<?php
/**
 * Copyright (C) 2017 Adfab
 *
 * This file is part of Adfab/Gdpr.
 */

namespace Adfab\Gdpr\Console\Command;

/**
 * Class Crypt
 * @package Adfab\Gdpr\Console\Command
 */
class Crypt extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $quoteCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Address\CollectionFactory
     */
    protected $addressCollectionFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * Crypt constructor.
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $addressCollectionFactory
     * @param \Magento\Framework\App\State $state
     * @param string|null $name
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $addressCollectionFactory,
        \Magento\Framework\App\State $state,
        string $name = null
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->addressCollectionFactory = $addressCollectionFactory;
        $this->state = $state;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $output->writeln('Encrypting personal data...');
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        foreach ($this->getPrivateCollections() as $collection) {
            $output->writeln('Encrypting collection: ' . $collection->getItemObjectClass());
            $collection->save();
        }
        $output->writeln('Encryption finished.');
    }

    /**
     * @return array
     */
    protected function getPrivateCollections()
    {
        $collections = [];
        $collections[] = $this->orderCollectionFactory->create();
        $collections[] = $this->addressCollectionFactory->create();
        $collections[] = $this->quoteCollectionFactory->create();
        $collections[] = $this->customerCollectionFactory->create();
        return $collections;
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
