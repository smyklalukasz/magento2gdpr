<?php
namespace Adfab\Gdpr\Model;

use Magento\Framework\Api\Filter;
use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\FilterApplierInterface;
use Adfab\Gdpr\Helper\Cipher;

class FulltextFilter extends AbstractPlugin
{
    protected $fields = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'company',
    ];

    protected $currentObjects = [];

    /**
     *
     * @param FilterApplierInterface $filter
     * @param Collection $collection
     * @param Filter $filter
     * @param callable $proceed
     * @return void
     */
    public function aroundApply( FilterApplierInterface $filterApplier, callable $proceed, Collection $collection, Filter $filter ) {
        $return = $proceed($collection, $filter);
        $match = [];
        if ( ! preg_match( '/MATCH\([^\)]+\) AGAINST/ims', $collection->getSelect()->__toString(), $match ) ) {
            return $return;
        }
        $collection->getSelect()->orWhere(
            $match[0].'(?)',
            $this->cipher->cipher( $filter->getValue() )
        );
        return $return;
    }
}