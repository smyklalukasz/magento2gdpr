<?php
namespace Adfab\Gdpr\Model;

use Magento\Customer\Model\Customer as BaseCustomer;

class Customer extends AbstractModelPlugin
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

    /**
     *
     * @param BaseCustomer $customer
     * @param string $email
     * @return string[]
     */
    public function beforeLoadByEmail(BaseCustomer $customer, $email)
    {
        if (in_array('email', $this->fields)) {
            return [
                $this->cipher->cipher( $this->format('email', $email) )
            ];
        }
        return [
            $email
        ];
    }
}