<?php
namespace Adfab\Gdpr\Model\ResourceModel;

class Customer extends AbstractResourcePlugin
{
    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
    ];

    protected $formatFields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
    ];
}