<?php
namespace Cvlr\FeaturedProduct\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\InventoryCatalogApi\Api\DefaultStockProviderInterfaceFactory;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;


class FeaturedProduct extends Template
{
    protected $defaultStockProviderFactory;
    protected $getProductSalableQty;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        DefaultStockProviderInterfaceFactory $defaultStockProviderFactory,
        GetProductSalableQtyInterface $getProductSalableQty,
        array $data = []
    ) {
        $this->defaultStockProviderFactory = $defaultStockProviderFactory;
        $this->getProductSalableQty = $getProductSalableQty;
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
    }


    public function getSalableQty()
    {
        $product = $this->getFeaturedProduct();
        $sku = $product->getSku();
        $stockId = 1;
        return $this->getProductSalableQty->execute($sku, $stockId);
    }


    public function getFeaturedProductEnabled()
    {
        return $this->_scopeConfig->getValue('featured_product_settings/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getFeaturedProduct()
    {
        $isEnabled = $this->_scopeConfig->getValue('featured_product_settings/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if (!$isEnabled) {
            return null;
        }

        $sku = $this->_scopeConfig->getValue('featured_product_settings/general/featured_sku', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        try {
            return $this->productRepository->get($sku);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getRegularPrice()
    {
        $product = $this->getFeaturedProduct();
        $regularPrice = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
        return $this->formatPrice($regularPrice);
    }

    public function getSpecialPrice()
    {
        $product = $this->getFeaturedProduct();
        $specialPrice = $product->getPriceInfo()->getPrice('special_price')->getAmount()->getValue();
        return $this->formatPrice($specialPrice);
    }

    public function getCostPrice()
    {
        $product = $this->getFeaturedProduct();
        $costPrice = $product->getCost();
        return $this->formatPrice($costPrice);
    }

    protected function formatPrice($price)
    {
        return number_format($price, 2, ',', '.');
    }
}
