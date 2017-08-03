<?php
namespace Adfab\Gdpr\Controller\Settings;

class Index extends \Adfab\Gdpr\Controller\Privacy
{
    /**
     * Managing newsletter subscription page
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('privacy_settings')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Privacy settings'));
        $this->_view->renderLayout();
    }
}
