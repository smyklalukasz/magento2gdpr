<?php
namespace Adfab\Gdpr\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Sales\Model\Order\Address\Validator as OrderAddressValidator;

class ValidatorPlugin extends AbstractPlugin
{
    /**
     *
     * @param AbstractValidator $validator
     * @param callable $proceed
     * @param AbstractModel $value
     * @return unknown
     */
    public function aroundIsValid( AbstractValidator $validator, callable $proceed, $value ) {
        return $this->processValidation($validator, $proceed, $value);
    }

    /**
     *
     * @param OrderAddressValidator $validator
     * @param callable $proceed
     * @param AbstractModel $value
     * @return unknown
     */
    public function aroundValidate( OrderAddressValidator $validator, callable $proceed, AbstractModel $value ) {
        return $this->processValidation($validator, $proceed, $value);
    }

    /**
     *
     * @param OrderAddressValidator|AbstractValidator $validator
     * @param callable $proceed
     * @param unknown $value
     * @return unknown
     */
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