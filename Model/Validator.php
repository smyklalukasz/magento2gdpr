<?php
namespace Adfab\Gdpr\Model;

use Magento\Framework\Validator\AbstractValidator;

class Validator extends AbstractValidatorPlugin
{
    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
        'customer_email',
        'customer_company',
    ];

    public function aroundIsValid( AbstractValidator $validator, callable $proceed, $value ) {
        return $this->processValidation($validator, $proceed, $value);
    }
}