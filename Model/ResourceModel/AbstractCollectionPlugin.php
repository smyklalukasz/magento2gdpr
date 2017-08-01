<?php
namespace Adfab\Gdpr\Model\ResourceModel;

use Adfab\Gdpr\Helper\Cipher;
use Adfab\Gdpr\Model\AbstractPlugin;
use Magento\Framework\App\ResourceConnection\SourceProviderInterface;
use Magento\Framework\DataObject;

abstract class AbstractCollectionPlugin extends AbstractPlugin
{

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
        }
        $this->isRunning = false;
        return $result;
    }
}