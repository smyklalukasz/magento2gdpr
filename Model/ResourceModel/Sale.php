<?php
namespace Adfab\Gdpr\Model\ResourceModel;

class Sale extends AbstractResourcePlugin
{
    protected $fields = [
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
        'customer_email',
        'customer_company',
    ];

    protected $formatFields = [
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
        'customer_email',
        'customer_company',
    ];
}