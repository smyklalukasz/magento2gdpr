<?php
namespace Adfab\Gdpr\Controller\Delete;

/**
 * Class Index
 * @package Adfab\Gdpr\Controller\Delete
 */
class Index extends \Adfab\Gdpr\Controller\Privacy
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Registry $registry
    ) {
        $this->customerRepository = $customerRepository;
        $this->registry = $registry;
        parent::__construct($context, $customerSession);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $this->registry->register('isSecureArea', true);
        $customerId = $this->_customerSession->getCustomerId();
        $this->_customerSession->logout();
        $this->customerRepository->deleteById($customerId);
        $this->registry->unregister('isSecureArea');
        $this->messageManager->addSuccessMessage('Your account was deleted.');
        return $this->resultRedirectFactory->create()->setUrl('/');
    }
}
