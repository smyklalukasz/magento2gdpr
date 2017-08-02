<?php

namespace Adfab\Gdpr\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;


    /**
     * tables and columns to alter
     * @var array
     */
    protected $tables = [
        'quote' => [
            'customer_middlename'
        ],
        'quote_address' => [
            'firstname',
            'middlename',
            'lastname',
            'telephone',
            'fax',
        ],
        'sales_order' => [
            'customer_firstname',
            'customer_middlename',
            'customer_lastname',
            'customer_email',
        ],
    ];

    /**
     * UpgradeData constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Magento\Framework\Setup\InstallSchemaInterface::install()
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        //$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        /* @var $eavSetup \Magento\Eav\Setup\EavSetup */

        foreach( $this->tables as $table => $columns ) {
            $tableName = $setup->getTable($table);
            foreach( $columns as $column ) {
                $setup->getConnection()->changeColumn(
                    $tableName,
                    $column,
                    $column,
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                    ]
                );
            }
        }
        $setup->endSetup();
    }
}