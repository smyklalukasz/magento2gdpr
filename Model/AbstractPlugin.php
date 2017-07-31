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
    protected $fields = [];

    /**
     *
     * @var array
     */
    protected $formatFields = [];

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
                $get = 'get'.ucfirst($key);
                $set = 'set'.ucfirst($key);
                if ( false && method_exists( $object, $get ) && method_exists( $object, $set) ) {
                    $object->$set( $this->cipher->decipher( $object->$get() ) );
                }
                else {
                    $object->setData($key, $this->cipher->decipher( $value ) );
                }
            }
        }
        $object->unsetData('is_ciphered');
    }
}