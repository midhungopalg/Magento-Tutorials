<?php
/**
 * @package Midhun_PagebuilderSingleProduct
 * @author  Midhun
 */
declare(strict_types=1);

namespace Midhun\PagebuilderSingleProduct\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;

class Preview extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
{
    /**
     * Constructor
     *
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Generates an HTML preview for the stage
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $pageResult = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $block = null;
        if (isset($params['sku']['productSku'])) {
            $block = $pageResult->getLayout()
                ->createBlock('Midhun\PagebuilderSingleProduct\Block\Widget\SingleProductWidget')
                ->setTemplate('Midhun_PagebuilderSingleProduct::widget/singleproduct.phtml')
                ->setSku($params['sku']['productSku'])
                ->toHtml();
        }
        
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($block);
    }
}
