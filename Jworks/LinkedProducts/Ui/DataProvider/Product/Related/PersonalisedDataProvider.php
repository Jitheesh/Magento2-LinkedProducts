<?php
/**
 *
 * @category Jworks
 * @package LinkedProducts
 * @subpackage Config
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 */

namespace Jworks\LinkedProducts\Ui\DataProvider\Product\Related;

/**
 * Class PersonalisedDataProvider
 *
 * @api
 * @since 101.0.0
 */
class PersonalisedDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider
{
    /**
     * {@inheritdoc
     * @since 101.0.0
     */
    protected function getLinkType()
    {
        return 'personalised';
    }
}
