<?php
namespace Adfab\Gdpr\Test\Unit;

class CipherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Adfab\Gdpr\Helper\Cipher
     */
    protected $cipherHelper;

    /**
     *
     * @var string
     */
    protected $clearMessage = 'Hello, Dave. You\'re looking well today';

    /**
     *
     * @var string
     */
    protected $cryptedMessage = 'mQvPpY82VJFaCSlc6aXH0GAxlhy6WOfDArhSrC0RiupcjEMqh7czmtafyAd2X4lc';

    /**
     *
     * @var string
     */
    protected $key = '9fc9ab2f465560d1d761dcb0c5ff2f50';

    /**
     *
     * @var string
     */
    protected $initialisationVector = 'aeBMCkqREo7sncE3T6uk4A==';

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->cipherHelper = $objectManager->getObject('Adfab\Gdpr\Helper\Cipher');
        $this->cipherHelper->setKeyAndIv($this->key, base64_decode( $this->initialisationVector ) );
    }

    /**
     *
     */
    public function testCrypt()
    {
        $this->assertEquals($this->cryptedMessage, $this->cipherHelper->cipher( $this->clearMessage ));
    }

    /**
     *
     */
    public function testDerypt()
    {
        $this->assertEquals($this->clearMessage, $this->cipherHelper->decipher( $this->cryptedMessage));
    }
}