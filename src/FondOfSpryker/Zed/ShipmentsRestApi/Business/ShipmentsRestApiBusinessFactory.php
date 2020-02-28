<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business;

use FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapperInterface;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory as SprykerShipmentsRestApiBusinessFactory;

/**
 * @method \Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiBusinessFactory extends SprykerShipmentsRestApiBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapperInterface
     */
    public function createShipmentQuoteMapper(): ShipmentQuoteMapperInterface
    {
        return new ShipmentQuoteMapper($this->getShipmentFacade());
    }
}
