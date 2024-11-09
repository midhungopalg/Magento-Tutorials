<?php
/**
 * @package Midhun_CustomCategoryAttributes
 * @author  Midhun
 */
declare(strict_types=1);

namespace Midhun\CustomCategoryAttributes\Model\Config\Source;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => 'l1', 'label' => __('L1')],
                ['value' => 'l2', 'label' => __('L2')],
                ['value' => 'l3', 'label' => __('L3')],
            ];
        }
        return $this->_options;
    }
        
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'l1' => __('L1'),
            'l2' => __('L2'),
            'L3' => __('L3'),
        ];
    }
}
