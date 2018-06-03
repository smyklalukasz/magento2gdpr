<?php
namespace Adfab\Gdpr\Block;

use Adfab\Gdpr\Helper\Data;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Account\Dashboard;
use Magento\Framework\View\Element\Template\Context;

/**
 * Customer front privacy settings block
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Settings extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * @var string
     */
    protected $_template = 'gdpr/account/settings.phtml';

    /**
     *
     * @var Data
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = [], Data $helper) {
        $this->helper = $helper;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Magento\Framework\View\Element\AbstractBlock::_prepareLayout()
     */
    protected function _prepareLayout() {
        $block = $this->getChildBlock('privacy.settings.optin');
        $customerId = $this->customerSession->getCustomerId();
        $customer = $this->customerRepository->getById($customerId);
        /* @var $customer \Magento\Customer\Model\Data\Customer */
        $thirdParty = $customer->getCustomAttribute('third_party') ? $customer->getCustomAttribute('third_party')->getValue(): 0;
        $suggestions = $customer->getCustomAttribute('personnalized_suggestions')? $customer->getCustomAttribute('personnalized_suggestions')->getValue(): 0;
        $block->setThirdParty($thirdParty);
        $block->setPersonnalizedSuggestions($suggestions);
        return parent::_prepareLayout();
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsThirdParty()
    {
        return false;
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsPrivacySettings()
    {
        return false;
    }

    /**
     * Return the save action Url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('privacy/settings/save');
    }

    /**
     *
     * @return boolean
     */
    public function displayPersonnalizedSuggestions() {
        return $this->helper->getPersonnalizedSuggestionsActive();
    }

    /**
     *
     * @return boolean
     */
    public function displayThirdParty() {
        return $this->helper->getThirdPartyActive();
    }

    /**
     *
     * @return boolean
     */
    public function displayAccountDeletion() {
        return $this->helper->getAccountDeletionActive();
    }

    /**
     *
     * @return boolean
     */
    public function displayExport() {
        return $this->helper->getExportActive();
    }
}
