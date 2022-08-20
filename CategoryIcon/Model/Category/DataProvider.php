<?php
/**
 * @author Midhun
 * @copyright Copyright (c) 2019 Midhun
 * @package Midhun_CategoryIcon
 */
declare(strict_types=1);

namespace Midhun\CategoryIcon\Model\Category;
 
use Magento\Catalog\Model\Category\DataProvider as CategoryDataProvider;
 
class DataProvider extends CategoryDataProvider
{
    /**
     * @return array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'category_icon_image';
 
        return $fields;
    }
}
