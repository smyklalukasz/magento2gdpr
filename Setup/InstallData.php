<?php

namespace Adfab\Gdpr\Setup;

use Adfab\Gdpr\Helper\Cipher;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    /**
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     *
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     *
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     *
     * @var Config
     */
    protected $eavConfig;

    /**
     *
     * @var array
     */
    protected $customerFields = [
        'personnalized_suggestions' => 'Personnalized suggestions',
        'third_party' => 'Third party',
    ];

    /**
     * Initialize dependencies.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     * @param DirectoryList $directoryList
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList,
        DirectoryList $directoryList,
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter= $configWriter;
        $this->cacheTypeList = $cacheTypeList;
        $this->directoryList= $directoryList;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $dir = $this->directoryList->getPath('var').'/keys';
        if ( ! is_dir( $dir ) ) {
            mkdir( $dir, 0700, true );
            file_put_contents($dir.'/.htaccess','<IfModule !mod_authz_core.c>
    Order deny,allow
    Deny from all
</IfModule>
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>');
        }
        $fileName = $this->scopeConfig->getValue( Cipher::XML_PATH_CUSTOMER_CIPHER_FILENAME );
        if ( strlen( $fileName ) == 0 ) {
            $fileName = md5(microtime(true).rand(0,10000));
            $this->configWriter->save( Cipher::XML_PATH_CUSTOMER_CIPHER_FILENAME, $fileName);
            $this->cacheTypeList->cleanType('config');
        }
        $file = $dir.'/'.$fileName.'.php';
        if ( ! is_file( $file ) ) {
            $isCryptoStrong = false;
            $password = openssl_random_pseudo_bytes( 64, $isCryptoStrong );
            $ivlen = openssl_cipher_iv_length( Cipher::METHOD );
            $isCryptoStrong = false;
            $iv = openssl_random_pseudo_bytes( $ivlen, $isCryptoStrong );
            if( ! $isCryptoStrong ) {
                throw new Exception('Non-cryptographically strong algorithm used for iv generation. This IV is not safe to use.');
            }
            file_put_contents( $file, '<?php return [\'key\'=>\''.md5($password).'\', \'iv\'=>\''.strtr($iv,['\''=>'\\\'']).'\'];' );
            chmod($file, 0700);
        }
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        /* @var $eavSetup \Magento\Eav\Setup\EavSetup */
        foreach( $this->customerFields as $field => $label ) {
            $eavSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                $field,
                [
                    'label' => $label,
                    'default' => 0,
                    'group' => 'Privacy',
                    'input' => 'boolean',
                    'position' => 200,
                    'required' => false,
                    'sort_order' => 200,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'system' => false,
                    'used_in_forms', ['adminhtml_customer', 'customer_account_create'],
                    'tab_group_code' => 'privacy',
                    'type' => 'int',
                ]
            );
            $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, $field);
            $attribute->setData(
                'used_in_forms',
                ['adminhtml_customer', 'checkout_register', 'customer_account_create']
            );
            $attribute->save();
        }
        $setup->endSetup();
    }
}