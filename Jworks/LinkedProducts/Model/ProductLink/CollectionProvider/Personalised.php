<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */

namespace Jworks\LinkedProducts\Model\ProductLink\CollectionProvider;

/**
 * Class Personalised
 * @package Jworks\LinkedProducts\Model\ProductLink\CollectionProvider
 */
class Personalised implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        return $this->getPersonalisedProducts($product);
    }

    /**
     * Retrieve array of related products
     *
     * @return array
     */
    public function getPersonalisedProducts($product)
    {
        if (!$product->hasPersonalisedProducts()) {
            $products = [];
            $collection = $this->getPersonalisedProductCollection($product);
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $product->setPersonalisedProducts($products);
        }
        return $product->getData('personalised_products');
    }

    /**
     * Retrieve collection related product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getPersonalisedProductCollection($product)
    {
        $linkTypeInstance =$product->getLinkInstance();
        $linkTypeInstance->setLinkTypeId(\Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED);
        $collection = $linkTypeInstance->getProductCollection()->setIsStrongMode();
        $collection->setProduct($product);
        return $collection;
    }
}
