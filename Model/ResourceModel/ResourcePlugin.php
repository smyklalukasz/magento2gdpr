<?php
namespace Adfab\Gdpr\Model\ResourceModel;

use Adfab\Gdpr\Helper\Cipher;
use Adfab\Gdpr\Model\AbstractPlugin;
use Magento\Framework\DataObject;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;

class ResourcePlugin extends AbstractPlugin
{
    /**
     *
     * @param AbstractModel $object
     * @param callable $proceed
     * @return AbstractModel
     */
    public function aroundSave( AbstractResource $resource, callable $proceed, AbstractModel $object ) {
        $this->decipher($object);
        if (
            ( $object instanceof \Magento\Customer\Model\Customer ) ||
            ( $object instanceof \Magento\Customer\Model\Address )
        ) {
            $this->formatDataObject($object);
        }
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


    /**
     *
     * @param AbstractResource $resource
     * @param callable $proceed
     * @param unknown $object
     * @param unknown $customerId
     * @return unknown
     */
    public function aroundLoadByCustomerId(AbstractResource $resource, callable $proceed, $object, $customerId)
    {
        $return = $proceed($object, $customerId);
        $this->decipher($object);
        return $return;
    }

    /**
     *
     * @param AbstractResource $resource
     * @param callable $proceed
     * @param unknown $object
     * @param unknown $quoteId
     * @return unknown
     */
    public function aroundLoadActive(AbstractResource $resource, callable $proceed, $object, $quoteId)
    {
        $return = $proceed($object, $quoteId);
        $this->decipher($object);
        return $return;
    }

    /**
     *
     * @param AbstractResource $resource
     * @param callable $proceed
     * @param unknown $object
     * @param unknown $quoteId
     * @return unknown
     */
    public function aroundLoadByIdWithoutStore(AbstractResource $resource, callable $proceed, $object, $quoteId)
    {
        $return = $proceed($object, $quoteId);
        $this->decipher($object);
        return $return;
    }
}