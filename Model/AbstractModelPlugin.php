<?php
namespace Adfab\Gdpr\Model;

use Adfab\Gdpr\Helper\Cipher;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;

abstract class AbstractModelPlugin extends AbstractPlugin
{

    /**
     *
     * @param AbstractModel $object
     * @param callable $proceed
     * @return unknown
     */
    public function aroundReindex( AbstractModel $object, callable $proceed ) {
        $this->cipher($object);
        $this->cipher->setIsIndexing(true);
        $return = $proceed();
        $this->cipher->setIsIndexing(false);
        $this->decipher($object);
        return $return;
    }
}