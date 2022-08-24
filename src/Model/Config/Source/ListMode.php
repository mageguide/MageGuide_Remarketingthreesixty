<?php 

namespace MageGuide\Remarketingthreesixty\Model\Config\Source;

class ListMode implements \Magento\Framework\Option\ArrayInterface {

    public function toOptionArray() {
        return [
            'valueP_ID' => __('Product ID'),
            'valueP_SK' => __('Product SKU')
        ];
    }

}