<?php
/**
 * 
 * @package Midhun_PagebuilderSingleProduct
 * @author Midhun
 */
declare(strict_types=1);

namespace Midhun\PagebuilderSingleProduct\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;

class SingleProductWidget extends Template implements BlockInterface
{
    protected $_template = "widget/singleproduct.phtml";

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepositoryInterface;

    /**
     * @var StoreManagerInterface
     */
    private $storeManagerInterface;

    /**
     * @var Data
     */
    private $priceHelper;

    /**
     * @var PostHelper
     */
    private $postHelper;

    /**
     * @var AbstractProduct
     */
    private $abstractProduct;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * Product param
     *
     * @param Template\Context $context
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param StoreManagerInterface $storeManagerInterface
     * @param Data $priceHelper
     * @param PostHelper $postHelper
     * @param AbstractProduct $abstractProduct
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepositoryInterface,
        StoreManagerInterface $storeManagerInterface,
        Data $priceHelper,
        PostHelper $postHelper,
        AbstractProduct $abstractProduct,
        UrlHelper $urlHelper,
        array $data = []
    ) {
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->priceHelper = $priceHelper;
        $this->postHelper = $postHelper;
        $this->abstractProduct = $abstractProduct;
        $this->urlHelper = $urlHelper;

        parent::__construct($context, $data);
    }

    public function getProductData()
    {
        $response = [];
        
        if ($this->getData('sku')) {
            try {
                $product = $this->productRepositoryInterface->get($this->getData('sku'));
                return $product;
            } catch (NoSuchEntityException $e) {
                throw new NoSuchEntityException(
                    __(
                        'No such product with the given Sku'
                    ),
                    $e
                );
            }
        }
        
        if (!$this->getData('widget_product_id')) {
            throw new \RuntimeException('Parameter widget_product_id is not set.');
        }

        $productId = $this->parseProductId($this->getData('widget_product_id'));

        try {
            $product = $this->productRepositoryInterface->getById($productId);
            return $product;
        } catch (NoSuchEntityException $e) {
            throw new NoSuchEntityException(
                __(
                    'No such product with the given Id'
                ),
                $e
            );
        }
    }


    /**
     * Get Formated Price
     *
     * @param string $price
     * @return string
     */
    public function getFormatedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * Get product image Url
     *
     * @param string $path
     * @return string
     */
    public function productImageUrl($path)
    {
        if ($path) {
            return $this->storeManagerInterface->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $path;
        }
        return null;
    }

    /**
     * Parse product Id
     *
     * @param string $productInfo
     * @throws \RuntimeException
     * @return int $productId
     */
    public function parseProductId($productInfo)
    {
        $productData = explode('/', $productInfo);

        if (!isset($productData[1])) {
            throw new \RuntimeException('Wrong widget_product_id structure.');
        }
        return $productData[1];
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->abstractProduct->getAddToCartUrl($product, ['_escape' => false]);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
