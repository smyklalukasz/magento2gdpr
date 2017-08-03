<?php

namespace Adfab\Gdpr\Block\Form;

use Adfab\Gdpr\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Optin extends \Magento\Framework\View\Element\Template
{
    /**
     *
     * @var bool
     */
    protected $personnalizedSuggestions = false;

    /**
     *
     * @var bool
     */
    protected $thirdParty = false;

    /**
     *
     * @var Data
     */
    protected $helper;

    /**
     *
     * @param Context $context
     * @param array $data
     * @param
     */
    public function __construct(Context $context, array $data = [], Data $helper) {
        $this->helper = $helper;
        parent::__construct($context, $data);
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
        return $this->helper->getPersonnalizedSuggestionsActive();
    }

    /**
     *
     * @return boolean
     */
    public function getPersonnalizedSuggestions()
    {
        return $this->personnalizedSuggestions;
    }

    /**
     *
     * @param boolean $personnalizedSuggestions
     * @return \Adfab\Gdpr\Block\Form\Optin
     */
    public function setPersonnalizedSuggestions($personnalizedSuggestions)
    {
        $this->personnalizedSuggestions = $personnalizedSuggestions;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getThirdParty()
    {
        return $this->thirdParty;
    }

    /**
     *
     * @param boolean $thirdParty
     * @return \Adfab\Gdpr\Block\Form\Optin
     */
    public function setThirdParty($thirdParty)
    {
        $this->thirdParty = $thirdParty;
        return $this;
    }

}