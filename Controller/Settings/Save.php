<?php
namespace Adfab\Gdpr\Controller\Settings;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;

class Save extends \Adfab\Gdpr\Controller\Privacy
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CustomerRepository $customerRepository
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CustomerRepository $customerRepository
    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->customerRepository = $customerRepository;
        $this->storeManager= $storeManager;
        parent::__construct($context, $customerSession);
    }

    /**
     * Save newsletter subscription preference action
     *
     * @return void|null
     */
    public function execute()
    {
        $request = $this->getRequest();
        if (!$this->formKeyValidator->validate($request)) {
            return $this->_redirect('privacy/settings/');
        }
        $customerId = $this->_customerSession->getCustomerId();
        if ($customerId === null) {
            $this->messageManager->addError(__('Something went wrong while saving your privacy settings.'));
        } else {
            try {
                $customer = $this->customerRepository->getById($customerId);
                /* @var $customer \Magento\Customer\Model\Data\Customer */
                $storeId = $this->storeManager->getStore()->getId();
                $customer->setStoreId($storeId);
                $customer->setCustomAttribute('personnalized_suggestions', $request->getParam('personnalized_suggestions', 0 ) ? 1 : 0 );
                $customer->setCustomAttribute('third_party', $request->getParam('third_party', 0 ) ? 1 : 0 );
                $this->customerRepository->save($customer);
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong while saving your privacy settings.'));
            }
        }
        $this->_redirect('privacy/settings/');
    }
}
