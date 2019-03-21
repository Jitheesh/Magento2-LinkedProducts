<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */

namespace Jworks\LinkedProducts\Model\Product\CopyConstructor;

/**
 * Class Personalised
 * @package Jworks\LinkedProducts\Model\Product\CopyConstructor
 */
class Personalised implements \Magento\Catalog\Model\Product\CopyConstructorInterface
{
    /**
     * Build product links
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Model\Product $duplicate
     * @return void
     */
    public function build(\Magento\Catalog\Model\Product $product, \Magento\Catalog\Model\Product $duplicate)
    {
        $data = [];
        $attributes = [];

        $link = $product->getLinkInstance();
        $link->setLinkTypeId(\Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED);

        foreach ($link->getAttributes() as $attribute) {
            if (isset($attribute['code'])) {
                $attributes[] = $attribute['code'];
            }
        }
        /** @var \Magento\Catalog\Model\Product\Link $link */
        foreach ($this->getRelatedLinkCollection($product) as $link) {
            $data[$link->getLinkedProductId()] = $link->toArray($attributes);
        }
        $duplicate->setRelatedLinkData($data);
    }

    /**
     * Retrieve collection related link
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Collection
     */
    public function getRelatedLinkCollection($product)
    {
        $link = $product->getLinkInstance();
        $link->setLinkTypeId(\Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED);
        $collection = $link->getProductCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

}
