<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */
namespace Jworks\LinkedProducts\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * InstallData constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Install product link types
         */
        $data = [
            ['link_type_id' => \Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED, 'code' => 'personalised'],
        ];

        foreach ($data as $bind) {
            $this->moduleDataSetup->getConnection()->insertForce(
                $this->moduleDataSetup->getTable(
                    'catalog_product_link_type'
                ),
                $bind
            );
        }

        /**
         * install product link attributes
         */
        $data = [
            [
                'link_type_id' => \Jworks\LinkedProducts\Model\Product\Link::LINK_TYPE_PERSONALISED,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int',
            ],
        ];

        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('catalog_product_link_attribute'),
            $data
        );
    }
}
