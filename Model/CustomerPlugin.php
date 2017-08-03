<?php
namespace Adfab\Gdpr\Model;

use Magento\Customer\Model\Customer;

class CustomerPlugin extends ModelPlugin
{
    /**
     *
     * @param Customer $customer
     * @param string $email
     * @return string[]
     */
    public function beforeLoadByEmail(Customer $customer, $email)
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