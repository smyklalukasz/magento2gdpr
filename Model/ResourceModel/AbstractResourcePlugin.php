<?php
namespace Adfab\Gdpr\Model\ResourceModel;

use Adfab\Gdpr\Helper\Cipher;
use Adfab\Gdpr\Model\AbstractPlugin;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;

abstract class AbstractResourcePlugin extends AbstractPlugin
{
    /**
     *
     * @param AbstractModel $object
     * @param callable $proceed
     * @return AbstractModel
     */
    public function aroundSave( AbstractResource $resource, callable $proceed, AbstractModel $object ) {
        $this->formatDataObject($object);
        $this->cipher($object);
        $return = $proceed($object);
        $this->decipher($object);
        return $return;
    }

    /**
     *
     * @param AbstractDb $resource
     * @param callable $proceed
     * @param AbstractModel $object
     * @param unknown $value
     * @param unknown $field
     * @return unknown
     */
    public function aroundLoad( AbstractResource $resource, callable $proceed, AbstractModel $object, $value, $field = null) {
        $return = $proceed($object, $value, $field);
        $this->decipher($object);
        return $return;
    }
}