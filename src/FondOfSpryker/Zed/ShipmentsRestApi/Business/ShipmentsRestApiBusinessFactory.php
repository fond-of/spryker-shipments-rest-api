<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business;

use FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapperInterface;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory as SprykerShipmentsRestApiBusinessFactory;

class ShipmentsRestApiBusinessFactory extends SprykerShipmentsRestApiBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\ShipmentsRestApi\Business\ShipmentQuoteMapperInterface
     */
    public function createShipmentQuoteMapper(): ShipmentQuoteMapperInterface
    {
        return new ShipmentQuoteMapper($this->getShipmentFacade());
    }
}