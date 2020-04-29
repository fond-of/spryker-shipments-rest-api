<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper as SprykerShipmentQuoteMapper;

class ShipmentQuoteMapper extends SprykerShipmentQuoteMapper
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentTransfer $shipmentTransfer): ExpenseTransfer
    {
        $expenseTransfer = parent::createShippingExpenseTransfer($shipmentTransfer);

        return $expenseTransfer->setUnitNetPrice($shipmentTransfer->getMethod()->getStoreCurrencyPrice());
    }
}
