<?php
namespace Adfab\Gdpr\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\AbstractResource;

class Sale extends AbstractResourcePlugin
{

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