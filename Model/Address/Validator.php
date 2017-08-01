<?php
namespace Adfab\Gdpr\Model\Address;

use Adfab\Gdpr\Model\AbstractValidatorPlugin;
use Adfab\Gdpr\Model\AbstractPlugin;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Model\Order\Address\Validator as OrderAddressValidator;

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

    /**
     *
     * @param AbstractValidator $validator
     * @param callable $proceed
     * @param AbstractModel $value
     * @return unknown
     */
    public function aroundValidate(OrderAddressValidator $validator, callable $proceed, AbstractModel $value ) {
        return $this->processValidation($validator, $proceed, $value);
    }
}