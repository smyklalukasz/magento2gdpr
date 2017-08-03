<?php

namespace Adfab\Gdpr\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    /**
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context

DELETE FROM `eav_attribute` WHERE `eav_attribute`.`attribute_code` IN('personnalized_suggestions','third_party');
DELETE FROM `setup_module` WHERE `setup_module`.`module` = 'Adfab_Gdpr';

     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $setup->endSetup();
    }
}