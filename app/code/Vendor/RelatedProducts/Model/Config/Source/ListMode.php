<?php
namespace Vendor\RelatedProducts\Model\Config\Source;

class ListMode implements \Magento\Framework\Data\OptionSourceInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => '3', 'label' => __('3')],
    ['value' => '4', 'label' => __('4')],
    ['value' => 'More than 5', 'label' => __('More than 5')],
   
  ];
 }
}