<?php

namespace Adfab\Gdpr\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Cipher helper
 */
class Cipher extends AbstractHelper
{

    const XML_PATH_CUSTOMER_CIPHER_ACTIVE = 'customer/privacy/cipher';
    const XML_PATH_CUSTOMER_CIPHER_FILENAME = 'customer/privacy/cipher_filename';
    const METHOD = 'aes-256-cbc';

    /**
     *
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     *
     * @var boolean
     */
    protected $cipherActive;

    /**
     *
     * @var boolean
     */
    protected $isIndexing = false;

    /**
     *
     * @var array
     */
    private $keyAndIv;

    /**
     *
     * @var string
     */
    private $base64Pattern = '/^[a-zA-Z0-9\/\r\n+]*={0,2}$/';

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList
    ) {
        parent::__construct($context);
        $this->directoryList= $directoryList;
    }

    /**
     *
     * @return boolean
     */
    public function isActive() {
        if ( ! isset( $this->cipherActive ) ) {
            $this->cipherActive = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_CIPHER_ACTIVE) ? true : false;
        }
        return $this->cipherActive;
    }

    /**
     * @return array
     */
    private function getKeyAndIv() {
        if ( isset( $this->keyAndIv) ) {
            return $this->keyAndIv;
        }
        $file = $this->directoryList->getPath('var')
            .'/keys/'.
            $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_CIPHER_FILENAME).
            '.php'
        ;
        if ( ! is_file($file) ) {
            return null;
        }
        $this->keyAndIv= include( $file );
        return $this->keyAndIv;
    }

    /**
     *
     * @param string $key
     * @param string $iv
     */
    public function setKeyAndIv($key,$iv) {
        $this->keyAndIv = [
            'key' => (string) $key,
            'iv' => (string) $iv,
        ];
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public function cipher( $string ) {
        if ( ( $string === null ) || ! is_string( $string ) || ! $this->isActive() ) {
            return $string;
        }
        $keyAndIv = $this->getKeyAndIv();
        $ciphered = openssl_encrypt($string, self::METHOD, $keyAndIv['key'], 0, $keyAndIv['iv']);
        return $ciphered !== false ? $ciphered : $string;
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public function decipher( $string ) {
        if ( ( $string === null ) || ! is_string($string) || ! preg_match( $this->base64Pattern, $string ) ) {
            return $string;
        }
        $keyAndIv = $this->getKeyAndIv();
        for ( $i = 0 ; $i < 3 ; $i++ ) {
            $clear = openssl_decrypt( $string, self::METHOD, $keyAndIv['key'], 0, $keyAndIv['iv'] );
            if  ( $clear !== false ) {
                $string = $clear;
            }
            else {
                break;
            }
        }
        return $string;
    }

    /**
     *
     * @return boolean
     */
    public function getIsIndexing()
    {
        return $this->isIndexing;
    }

    /**
     *
     * @param boolean $isIndexing
     * @return \Adfab\Gdpr\Helper\Cipher
     */
    public function setIsIndexing($isIndexing)
    {
        $this->isIndexing = $isIndexing;
        return $this;
    }

}
