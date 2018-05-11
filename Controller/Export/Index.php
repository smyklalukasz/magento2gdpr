<?php
namespace Adfab\Gdpr\Controller\Export;

/**
 * Class Index
 * @package Adfab\Gdpr\Controller\Export
 */
class Index extends \Adfab\Gdpr\Controller\Privacy
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        parent::__construct($context, $customerSession);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $fileName = 'personal_data.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;

        $customer = $this->_customerSession->getCustomer();
        $personalData = $this->getPresonalData($customer);

        $this->csvProcessor
            ->setDelimiter(';')
            ->setEnclosure('"')
            ->saveData(
                $filePath,
                $personalData
            );

        return $this->fileFactory->create(
            $fileName,
            [
                'type' => "filename",
                'value' => $fileName,
                'rm' => true,
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @return array
     */
    protected function getPresonalData(\Magento\Customer\Model\Customer $customer)
    {
        $result = [];

        $customerData = $customer->getData();

        $result[] = [
            'address_id',
            'firstname',
            'middlename',
            'lastname',
            'email',
            'company',
            'street',
            'telephone',
            'fax',
        ];

        $result[] = [
            null,
            $customerData['firstname'],
            $customerData['middlename'],
            $customerData['lastname'],
            $customerData['email'],
            null,
            null,
            null,
            null,
        ];

        $addressId = 1;
        foreach ($customer->getAddresses() as $address) {
            $result[] = [
                $addressId,
                $address['firstname'],
                $address['middlename'],
                $address['lastname'],
                null,
                $address['company'],
                $address['street'],
                $address['telephone'],
                $address['fax'],
            ];
            $addressId++;
        }

        return $result;
    }
}
