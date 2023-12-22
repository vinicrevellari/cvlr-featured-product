<?php

namespace Cvlr\FeaturedProduct\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Cvlr\FeaturedProduct\Block\FeaturedProduct;

class Ajax extends Action
{
    protected $jsonResultFactory;
    protected $featuredProductBlock;

    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        FeaturedProduct $featuredProductBlock
    ) {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
        $this->featuredProductBlock = $featuredProductBlock;
    }

    public function execute()
    {
        $qtyAvailable = $this->featuredProductBlock->getSalableQty();
        $result = $this->jsonResultFactory->create();
        return $result->setData(['qty_available' => $qtyAvailable]);
    }
}
