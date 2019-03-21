<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */

namespace Jworks\LinkedProducts\Model\Product;

/**
 * Class Link
 * @package Jworks\LinkedProducts\Model\Product
 */
class Link extends \Magento\Catalog\Model\Product\Link
{

    const LINK_TYPE_PERSONALISED = 6;

    /**
     * @return $this
     */
    public function usePersonalisedLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_PERSONALISED);
        return $this;
    }

    /**
     * @return Link\SaveHandler
     */
    private function getProductLinkSaveHandler()
    {
        if (null === $this->saveProductLinks) {
            $this->saveProductLinks = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Catalog\Model\Product\Link\SaveHandler::class);
        }
        return $this->saveProductLinks;
    }
}
