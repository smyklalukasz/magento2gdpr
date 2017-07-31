<?php
namespace Adfab\Gdpr\Model\ResourceModel;

class Address extends AbstractResourcePlugin
{
    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
        'street',
        'telephone',
        'fax',
    ];

    protected $formatFields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
    ];
}