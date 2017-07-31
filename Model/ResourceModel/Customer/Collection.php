<?php
namespace Adfab\Gdpr\Model\ResourceModel\Customer;

use Adfab\Gdpr\Model\ResourceModel\AbstractCollectionPlugin;
use Magento\Framework\App\ResourceConnection\SourceProviderInterface;
use Magento\Framework\DataObject;

class Collection extends AbstractCollectionPlugin {

    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
        'billing_firstname',
        'billing_lastname',
        'billing_telephone',
        'billing_postcode',
        'billing_street',
        'billing_company',
    ];

    protected $specialFields = [
        'name',
        'shipping_full',
        'billing_full',
    ];
}