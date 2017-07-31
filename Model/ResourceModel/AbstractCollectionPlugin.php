<?php
namespace Adfab\Gdpr\Model\ResourceModel;

use Adfab\Gdpr\Helper\Cipher;
use Adfab\Gdpr\Model\AbstractPlugin;
use Magento\Framework\App\ResourceConnection\SourceProviderInterface;
use Magento\Framework\DataObject;

abstract class AbstractCollectionPlugin extends AbstractPlugin
{
    protected $specialFields = [];

    protected $isRunning = false;

    /**
     *
     * @param SourceProviderInterface $collection
     * @param DataObject $item
     * @return DataObject
     */
    public function afterLoad( SourceProviderInterface $collection, $result ) {
        if ( $this->cipher->getIsIndexing() || $this->isRunning ) {
            return $result;
        }
        $this->isRunning = true;
        foreach( $collection->getItems() as $item ) {
           $this->decipher($item);
            foreach( $this->specialFields as $field ) {
                if ( $item->hasData( $field ) ) {
                    $parts = explode(' ', $item->getData( $field ) );
                    foreach( $parts as & $part ) {
                        $clear = $this->cipher->decipher($part);
                        if ( strlen( $clear ) ) {
                            $part = $clear;
                        }
                    }
                    $item->setData( $field, implode(' ',$parts) );
                }
            }
        }
        $this->isRunning = false;
        return $result;
    }
}