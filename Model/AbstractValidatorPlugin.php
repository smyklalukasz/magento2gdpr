<?php
namespace Adfab\Gdpr\Model;

use Magento\Framework\Validator\AbstractValidator;

abstract class AbstractValidatorPlugin extends AbstractPlugin
{
    protected function processValidation( $validator, callable $proceed, $value ) {
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
            ( $value instanceof \Magento\Quote\Model\Quote ) ||
            ( $value instanceof \Magento\Sales\Model\Order )
        ) {
            foreach( $value->getAddressesCollection() as $address ) {
                if ( $address->hasData('is_ciphered') ) {
                    $this->decipher($address);
                    $objectsToProcess[] = $address;
                }
            }
        }
        else if (
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
            $order = $value->getOrder();
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