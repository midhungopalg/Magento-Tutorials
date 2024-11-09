<?php
/**
 * @package Midhun_CustomProductAttributes
 * @author  Midhun
 */
declare(strict_types=1);

namespace Midhun\CustomProductAttributes\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Options extends AbstractSource
{
    /**
    * Get all options
    *
    * @return array
    */
    public function getAllOptions()
    {
        $this->options = [
            ['label' => __('No'), 'value'=>'0'],
            ['label' => __('Yes'), 'value'=>'1'],
            ['label' => __('Other'), 'value'=>'2']
        ];
        return $this->options;
    }
}
