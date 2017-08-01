<?php
namespace Adfab\Gdpr\Model;

use Magento\Customer\Model\Customer as BaseCustomer;

class Customer extends AbstractModelPlugin
{

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