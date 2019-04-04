<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */

// @codingStandardsIgnoreFile

namespace Jworks\LinkedProducts\Block\Product\ProductList;

use Magento\Catalog\Model\ResourceModel\Product\Collection;


/**
 * Catalog product related items block
 *
 * @api
 * @SuppressWarnings(PHPMD.LongVariable)
 * @since 100.0.2
 */
class Personalised extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var Collection
     */
    protected $_itemCollection;

    /**
     * Checkout session
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Checkout cart
     *
     * @var \Magento\Checkout\Model\ResourceModel\Cart
     */
    protected $_checkoutCart;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->_checkoutCart = $checkoutCart;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_checkoutSession = $checkoutSession;
        $this->moduleManager = $moduleManager;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return $this
     */
    protected function _prepareData()
    {
        $product = $this->getProduct();
        /* @var $product \Magento\Catalog\Model\Product */

        $this->_itemCollection = $this->getPersonalisedProductCollection($product)->addAttributeToSelect(
            'required_options'
        )->setPositionOrder()->addStoreFilter();

        if ($this->moduleManager->isEnabled('Magento_Checkout')) {
            $this->_addProductAttributesAndPrices($this->_itemCollection);
        }
        $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $this->_itemCollection->load();

        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }

    /**
     * Retrieve collection related product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getPersonalisedProductCollection($product)
    {
        $linkTypeInstance = $product->getLinkInstance();
        $linkTypeInstance->setLinkTypeId(\Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED);
        $collection = $linkTypeInstance->getProductCollection()->setIsStrongMode();
        $collection->setProduct($product);
        return $collection;
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        /**
         * getIdentities() depends on _itemCollection populated, but it can be empty if the block is hidden
         * @see https://github.com/magento/magento2/issues/5897
         */
        if ($this->_itemCollection === null) {
            $this->_prepareData();
        }
        return $this->_itemCollection;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getItems() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }
        return $identities;
    }

    /**
     * Find out if some products can be easy added to cart
     *
     * @return bool
     */
    public function canItemsAddToCart()
    {
        foreach ($this->getItems() as $item) {
            if (!$item->isComposite() && $item->isSaleable() && !$item->getRequiredOptions()) {
                return true;
            }
        }
        return false;
    }
}
