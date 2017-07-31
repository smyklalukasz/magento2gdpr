<?php
namespace Adfab\Gdpr\Model;

use Magento\Framework\Validator\AbstractValidator;

class Validator extends AbstractPlugin
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
        $objectsToProcess = [];
        if (
            (
                ( $value instanceof \Magento\Customer\Model\Customer ) ||
                ( $value instanceof \Magento\Quote\Model\Quote ) ||
                ( $value instanceof \Magento\Sales\Model\Order ) ||
                ( $value instanceof \Magento\Quote\Model\Quote\Address ) ||
                ( $value instanceof \Magento\Sales\Model\Order\Address )
            ) &&
            $value->hasData('is_ciphered')
        ) {
            $this->decipher($value);
            $objectsToProcess[] = $value;
        }
        if (
            ( $value instanceof \Magento\Quote\Model\Quote\Address ) &&
            $value->getQuote()->hasData('is_ciphered')
        ) {
            $quote = $value->getQuote();
            $this->decipher($quote);
            if ( empty($objectsToProcess) ) {
                $objectsToProcess[] = $value;
            }
            $objectsToProcess[] = $quote;
        }
        else if (
            ( $value instanceof \Magento\Sales\Model\Order\Address ) &&
            $value->getOrder()->hasData('is_ciphered')
        ) {
            $order = $value->getQuote();
            $this->decipher($order);
            if ( empty($objectsToProcess) ) {
                $objectsToProcess[] = $value;
            }
            $objectsToProcess[] = $order;
        }
        $return = $proceed($value);
        if ( !empty($objectsToProcess) ) {
            foreach( $objectsToProcess as $object ) {
                $this->cipher($object);
            }
        }
        return $return;
    }
}