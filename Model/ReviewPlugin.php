<?php

namespace Adfab\Gdpr\Model;

use Faker\Factory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Review\Model\Review;

class ReviewPlugin {

    const XML_PATH_CUSTOMER_USE_PSEUDO = 'customer/privacy/use_pseudo';

    /**
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *
     * @var boolean
     */
    protected $active;

    public function __construct(ScopeConfigInterface $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     *
     * @return boolean
     */
    protected function isActive() {
        if ( ! isset($this->active) ) {
            $this->active = $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_USE_PSEUDO);
        }
        return $this->active;
    }

    /**
     *
     * @return \Faker\Generator
     */
    protected function getFaker() {
        if ( ! isset($this->faker) ) {
            $om = \Magento\Framework\App\ObjectManager::getInstance();
            $resolver = $om->get('Magento\Framework\Locale\Resolver');
            $this->faker = Factory::create($resolver->getLocale());
        }
        return $this->faker;
    }

    /**
     *
     * @param Review $review
     * @param string $nickname
     * @return string
     */
    public function aroundGetData( Review $review, callable $proceed, $key = '', $index = null) {
        $result = $proceed($key, $index);
        if ( $this->isActive() && $key== 'nickname') {
            $faker = $this->getFaker();
            return $faker->firstName;
        }
        return $result;
    }
}