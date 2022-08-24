<?php

namespace MageGuide\Remarketingthreesixty\Helper;

/**
 * Helper class
 * @package  MageGuide\Remarketingthreesixty
 * @module   Remarketing360
 * @author   MageGuide Developer
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	/**
     * @var array
     */
    protected $_remarketingOptions;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_remarketingOptions = $this->scopeConfig->getValue('mageguide_remarketingthreesixty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return boolean
     */
    public function getIsActive() {
        return $this->_remarketingOptions['general']['status'];
    }

    /**
     * @return string
     */
    public function getTagManagerId() {
        return trim($this->_remarketingOptions['general']['program_id']);
    }

    /**
     * @return string
     */
    public function getValueSI() {
        return trim($this->_remarketingOptions['general']['list_mode']);
    }

    /**
     * @return boolean
     */
    public function getIfDefaultCheckout() {
        return $this->_remarketingOptions['general']['default_checkout'];
    }

    /**
     * @return string
     */
    public function getCheckoutExtension() {
        return trim($this->_remarketingOptions['general']['checkout_extension']);
    }

    public function getGoogleEvents() {
        return trim($this->_remarketingOptions['general']['google_events']);
    }

}
