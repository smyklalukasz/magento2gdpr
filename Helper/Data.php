<?php

namespace Adfab\Gdpr\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Cipher helper
 */
class Data extends AbstractHelper
{

    const XML_PATH_CUSTOMER_PERSONNALIZED_SUGGESTIONS = 'customer/privacy/personnalized_suggestions';
    const XML_PATH_CUSTOMER_THIRD_PARTY = 'customer/privacy/third_party';
    const XML_PATH_CUSTOMER_ACCOUNT_DELETION = 'customer/privacy/account_deletion';
    const XML_PATH_CUSTOMER_EXPORT = 'customer/privacy/export';

    /**
     *
     * @var bool
     */
    protected $thirdPartyActive;

    /**
     *
     * @var bool
     */
    protected $personnalizedSuggestionsActive;

    /**
     *
     * @var bool
     */
    protected $accountDeletionActive;

    /**
     *
     * @var bool
     */
    protected $exportActive;


    /**
     *
     * @return boolean
     */
    public function getThirdPartyActive()
    {
        if ( ! isset( $this->thirdPartyActive) ) {
            $this->thirdPartyActive = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_PERSONNALIZED_SUGGESTIONS) ? true : false;
        }
        return $this->thirdPartyActive;
    }

    /**
     *
     * @return boolean
     */
    public function getPersonnalizedSuggestionsActive()
    {
        if ( ! isset( $this->personnalizedSuggestionsActive) ) {
            $this->personnalizedSuggestionsActive = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_THIRD_PARTY) ? true : false;
        }
        return $this->personnalizedSuggestionsActive;
    }

    /**
     *
     * @return boolean
     */
    public function getAccountDeletionActive()
    {
        if ( ! isset( $this->accountDeletionActive) ) {
            $this->accountDeletionActive = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_ACCOUNT_DELETION) ? true : false;
        }
        return $this->accountDeletionActive;
    }

    /**
     *
     * @return boolean
     */
    public function getExportActive()
    {
        if ( ! isset( $this->exportActive) ) {
            $this->exportActive = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_EXPORT) ? true : false;
        }
        return $this->exportActive;
    }
}
