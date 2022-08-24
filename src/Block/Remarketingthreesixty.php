<?php

namespace MageGuide\Remarketingthreesixty\Block;

/**
 * Block class for order success page
 * @package  MageGuide_Skroutz
 * @module   Skroutz
 * @author   MageGuide Developer
 */
class Remarketingthreesixty extends \Magento\Framework\View\Element\Template {

    /**
     * @var \MageGuide\Remarketingthreesixty\Helper\Data
     */
    protected $mghelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_saleOrders;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_modelProduct;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_granttotal;


    protected $_pageType = null;
    protected $_pagetype_product = 'offerdetail';
    protected $_pagetype_home = 'home';
    /* protected $_pagetype_category = 'category'; */
    protected $_pagetype_searchresults = 'searchresults';
    protected $_pagetype_cart = 'conversionintent';
    protected $_pagetype_success = 'conversion';
    protected $_pagetype_other = 'other';


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageGuide\Remarketingthreesixty\Helper\Data $mghelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order $saleOrders,
        \Magento\Catalog\Model\Product $modelProduct,
        \Magento\Sales\Model\Order $granttotal,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_data = $data;
        $this->mghelper = $mghelper;
        $this->_registry = $registry;
        $this->_request = $request;
        $this->_checkoutSession = $checkoutSession;
        $this->_saleOrders = $saleOrders;
        $this->_modelProduct = $modelProduct;
        $this->_granttotal = $granttotal;
    }

    public function getIsEnable(){ 
        $isEnable = $this->mghelper->getIsActive();
        return $isEnable;               
    }

    public function getTagId(){ 
        $getId = $this->mghelper->getTagManagerId();
        return $getId;               
    }

    public function getValueSkuOrId(){ 
        $isSkuOrId = $this->mghelper->getValueSI();
        if ($isSkuOrId == "valueP_ID") {
            return "id";
        } else {
            return "sku";
        }            
    } 

    public function getCustomCheckout() {
        $getdefaultCheckout = $this->mghelper->getCheckoutExtension();
        return $getdefaultCheckout;     
    }

    public function getIfUseDefault() {
        $ifgetdefaultCheckout = $this->mghelper->getIfDefaultCheckout();
        return $ifgetdefaultCheckout; 
    }

    public function getIfGoogleEvents() {
        $ifgoogleevents = $this->mghelper->getGoogleEvents();
        return $ifgoogleevents; 
    }

    public function getProductPrice() {
        if ($this->getPageType() == $this->_pagetype_product) {
            if ($this->_registry->registry('current_product')) {
                $final_price = $this->_registry->registry('current_product')->getFinalPrice();
                return sprintf("%01.2f", $final_price);
            }
        }
    }

    /**
     * find out the current page type to insert the appropriate script
     *
     * @return null|string
     */
    public function getPageType(){
        if (is_null($this->_pageType)) {
            if ($this->_registry->registry('current_product')) {
                $this->_pageType = $this->_pagetype_product;
                return $this->_pageType;
            }

            /*
            if (Mage::registry('current_category')) {
                $this->_pageType = $this->_pagetype_category;
                return $this->_pageType;
            }
            */

            $module = $this->_request->getModuleName();
            $controller = $this->_request->getControllerName();
            $action = $this->_request->getActionName();

            if (!$this->getIfUseDefault() && $this->getCustomCheckout() && $module!='cms' && $module!='catalogsearch') {
                if ($module == $this->getCustomCheckout()) {
                    if ($action == 'success') {
                        $this->_pageType = $this->_pagetype_success;
                    } else if ($controller == 'index' && $action == 'index'){
                        $this->_pageType = $this->_pagetype_cart;
                    }
                    else {
                        $this->_pageType = $this->_pagetype_other;
                    }
                    return $this->_pageType;
                } else if($module == 'checkout'){
                    if ($controller == 'cart') {
                        $this->_pageType = $this->_pagetype_cart;
                    } elseif ($action == 'success') {
                        $this->_pageType = $this->_pagetype_success;
                    } else {
                        $this->_pageType = $this->_pagetype_other;
                    }
                    return $this->_pageType;
                }
                else{
                    $this->_pageType = $this->_pagetype_other;
                    return $this->_pageType;
                }
            }

            switch ($module) {
                case 'cms':
                    if ($controller == 'index' && $action == 'index') {
                        $this->_pageType = $this->_pagetype_home;
                    } else {
                        $this->_pageType = $this->_pagetype_other;
                    }
                    break;
                case 'checkout':
                    if ($controller == 'cart') {
                        $this->_pageType = $this->_pagetype_cart;
                    } elseif ($action == 'success') {
                        if ($this->getIfUseDefault()){
                            $this->_pageType = $this->_pagetype_success;
                        } else {
                            $this->_pageType = $this->_pagetype_other;
                        }
                    } else if ($controller == 'index' && $action == 'index'){
                        $this->_pageType = $this->_pagetype_cart;
                    }
                    else {
                        $this->_pageType = $this->_pagetype_other;
                    }
                    break;
                case 'catalogsearch':
                    if ($controller == 'result') {
                        $this->_pageType = $this->_pagetype_searchresults;
                    } else {
                        $this->_pageType = $this->_pagetype_other;
                    }
                    break;
                default:
                    $this->_pageType = $this->_pagetype_other;
            }
        }
        return $this->_pageType;
    } 

    public function getPageTypeGoogleEvents(){
        if ($this->_pageType == 'offerdetail'){
            $_pageTypeGoogle = 'product_page';
        } else {
            $_pageTypeGoogle = 'thank_you_page';
        } 
        return $_pageTypeGoogle;
    }

    public function getPageValueGoogleEvents(){
        $last_order_id = $this->_checkoutSession->getLastOrderId();
        $order = $this->_granttotal->load($last_order_id);
        $order = $order->getGrandTotal();
        return sprintf("%01.2f", $order);
    }

    /**
     * get total value for current page
     * if product page, use product's final price
     * if cart page, use the sum of all the items in quote
     * if checkout success page, use order subtotal including tax
     * if anything else, return empty value
     *
     * @return string
     */
    public function getPageValue() {
        switch ($this->getPageType()) {
            case ($this->_pagetype_product):
                return $this->getProductPrice();
                break;
            case ($this->_pagetype_cart):
                $cartSubtotalInclTax = 0;
                $quote = $this->_checkoutSession->getQuote();
                $quoteItems = $quote->getAllVisibleItems();
                if (count($quoteItems)) {
                    foreach ($quoteItems as $item) {
                        $cartSubtotalInclTax += $item->getRowTotalInclTax();
                    }
                }
                return sprintf("%01.2f", $cartSubtotalInclTax);
                break;  
            case ($this->_pagetype_success):
                $last_order_id = $this->_checkoutSession->getLastOrderId();
                $order = $this->_saleOrders->load($last_order_id);
                $orderSubtotalInclTax = $order->getSubtotalInclTax();
                return sprintf("%01.2f", $orderSubtotalInclTax);
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * get list of product ids for current page
     *
     * @return string
     */
    public function getDynxProductId() {
        $products = '';
        switch ($this->getPageType()) {
            case ($this->_pagetype_product):
                if (strcmp($this->getValueSkuOrId(),"id") == 0) {
                    $productID = $this->_registry->registry('current_product')->getId();
                } else {
                    $productID = $this->_registry->registry('current_product')->getSku();
                }

                return "'" . $productID . "'";
                break;

            case $this->_pagetype_cart:
                $quote = $this->_checkoutSession->getQuote();
                $quoteItems = $quote->getAllVisibleItems();
                if (count($quoteItems)) {
                    if (sizeof($quoteItems) == 1) {
                        $item = current($quoteItems);
                        if (strcmp($this->getValueSkuOrId(),"id") == 0) {
                            return "'" . $item->getProductId() . "'";
                        } else {
                            $sku = $this->_modelProduct->load($item->getProductId())->getSku();
                            return "'" . $sku . "'";
                        }
                    } else {
                        $products .= '[';
                        foreach ($quoteItems as $item) {
                            if (strcmp($this->getValueSkuOrId(),"id") == 0) {
                                $products .= "'" . $item->getProductId() . "',";
                            } else {
                                $sku = $this->_modelProduct->load($item->getProductId())->getSku();
                                $products .= "'" . $sku . "',";
                            }
                        }
                        $products .= ']';
                    }
                }
                return $products;
                break;
            case ($this->_pagetype_success):
                $last_order_id = $this->_checkoutSession->getLastOrderId();
                $order = $this->_saleOrders->load($last_order_id);
                $orderItems = $order->getAllVisibleItems();
                if (count($orderItems)) {
                    if (sizeof($orderItems) == 1) {
                        $item = current($orderItems);
                        if (strcmp($this->getValueSkuOrId(),"id") == 0) {
                            return "'" . $item->getProductId() . "'";
                        } else {
                            $sku = $this->_modelProduct->load($item->getProductId())->getSku();
                            return "'" . $sku . "'";
                        }
                    } else {
                        $products .= '[';
                        foreach ($orderItems as $item) {
                            if (strcmp($this->getValueSkuOrId(),"id") == 0) {
                                $products .= "'" . $item->getProductId() . "',";
                            } else {
                                $sku =$this->_modelProduct->load($item->getProductId())->getSku();
                                $products .= "'" . $sku . "',";
                            }
                        }
                        $products .= ']';
                    }
                }
                return $products;
                break;
        }
        return "''";
    }

}