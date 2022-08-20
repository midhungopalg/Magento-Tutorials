<?php
/**
 * @author Midhun
 * @copyright Copyright (c) 2019 Midhun
 * @package Midhun_CategoryIcon
 */
declare(strict_types=1);

namespace Midhun\CategoryIcon\Controller\Adminhtml\Icon;
 
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\ImageUploader;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
 
class Upload extends Action
{
    /**
     * Image uploader
     *
     * @var ImageUploader
     */
    private $imageUploader;
 
    /**
     * Upload constructor.
     *
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;

        parent::__construct($context);
    }
 
    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('category_icon_image');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
