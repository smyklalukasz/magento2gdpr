<?php
namespace Adfab\Gdpr\Model;

use Adfab\Gdpr\Helper\Cipher;
use Magento\Framework\DataObject;

abstract class AbstractPlugin
{
    /**
     *
     * @var Cipher
     */
    protected $cipher;

    /**
     *
     * @var array
     */
    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
        'street',
        'telephone',
        'fax',
        'customer_firstname',
        'customer_middlename',
        'customer_lastname',
        'customer_email',
        'customer_company',
        'customer_street',
        'customer_telephone',
        'customer_fax',
        'billing_firstname',
        'billing_middlename',
        'billing_lastname',
        'billing_email',
        'billing_company',
        'billing_street',
        'billing_telephone',
        'billing_fax',
        'shipping_firstname',
        'shipping_middlename',
        'shipping_lastname',
        'shipping_email',
        'shipping_company',
        'shipping_street',
        'shipping_telephone',
        'shipping_fax',
    ];

    /**
     *
     * @var string[]
     */
    protected $formatFields = [
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
        'billing_firstname',
        'billing_middlename',
        'billing_lastname',
        'billing_email',
        'billing_company',
        'shipping_firstname',
        'shipping_middlename',
        'shipping_lastname',
        'shipping_email',
        'shipping_company',
    ];

    /**
     *
     * @var string[]
     */
    protected $specialFields = [
        'name',
        'billing_name',
        'billing_full',
        'shipping_name',
        'shipping_full',
    ];

    /**
     *
     * @param string $field
     * @param string $string
     * @return string
     */
    protected function format($field, $string) {
        $string = mb_strtolower( $string );
        if ( strpos( $field, 'email' ) === false ) {
            $string = $this->multibyteCapitalizeWords($string);
        }
        return $string;
    }

    /**
     *
     * @param string $string
     * @return string
     */
    protected function multibyteCapitalizeWords($string) {
        $parts = [];
        foreach( explode(' ', $string) as $part ) {
            $firstChar = mb_substr($part, 0, 1);
            $then = mb_substr($part, 1);
            $parts[] = mb_strtoupper($firstChar) . $then;
        }
        return implode(' ', $parts);
    }


    /**
     *
     * @param DataObject $object
     */
    protected function formatDataObject( DataObject $object ) {
        if ( $object->hasData('is_ciphered') ) {
            return ;
        }
        foreach( $object->getData() as $field => $value ) {
            if ( in_array( $field, $this->formatFields )  ) {
                $object->setData( $field, $this->format($field, $value ));
            }
        }
    }

    /**
     *
     * @param Cipher $cipher
     */
    public function __construct( Cipher $cipher ) {
        $this->cipher = $cipher;
    }

    /**
     *
     * @param DataObject $object
     */
    protected function cipher( DataObject $object ) {
        if ( $object->hasData('is_ciphered') ) {
            return ;
        }
        $this->decipher( $object );
        $ciphered = false;
        foreach( $object->getData() as $key => & $value ) {
            if ( in_array( $key, $this->fields ) && ( $value !== '' ) ) {
                $object->setData($key, $this->cipher->cipher( $value ) );
                $ciphered = true;
            }
        }
        if ( $ciphered ) {
            $object->setData('is_ciphered', true);
        }
    }

    /**
     *
     * @param DataObject $object
     */
    protected function decipher( DataObject $object ) {
        foreach( $object->getData() as $key => & $value ) {
            if ( in_array( $key, $this->fields ) ) {
                $object->setData($key, $this->cipher->decipher( $value ) );
            }
            else if ( in_array( $key, $this->specialFields ) ) {
                $parts = explode(' ', $object->getData( $key) );
                foreach( $parts as & $part ) {
                    $clear = $this->cipher->decipher($part);
                    if ( strlen( $clear ) ) {
                        $part = $clear;
                    }
                }
                $object->setData($key, implode(' ',$parts) );
            }
        }
        $object->unsetData('is_ciphered');
    }
}