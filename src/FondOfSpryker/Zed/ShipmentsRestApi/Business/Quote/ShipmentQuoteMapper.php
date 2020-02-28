<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper as SprykerShipmentQuoteMapper;

class ShipmentQuoteMapper extends SprykerShipmentQuoteMapper implements ShipmentQuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentToQuoteItem(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $idShipmentMethod = $restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod();
        foreach ($quoteTransfer->getItems() as $item) {
            $shipment = $item->getShipment();
            if ($shipment === null) {
                $shipment = new ShipmentTransfer();
            }

            $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);

            if ($shipmentMethodTransfer === null) {
                return $quoteTransfer;
            }

            $shipment->setMethod($shipmentMethodTransfer)
                ->setShipmentSelection((string)$idShipmentMethod)
                ->setShippingAddress(clone $quoteTransfer->getBillingAddress());
            $item->setShipment($shipment);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): ExpenseTransfer
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentMethodTransfer->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpenseTransfer->setUnitNetPrice($shipmentMethodTransfer->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setUnitGrossPrice($shipmentMethodTransfer->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setQuantity(1);

        return $shipmentExpenseTransfer;
    }
}
