<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote;

use FondOfSpryker\Yves\Shipment\ShipmentConfig;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
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

            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer->setIdShipmentMethod(ShipmentConfig::DEFAULT_MODULE_SHIPMENT_METHOD_ID);

            $shipment->setMethod($shipmentMethodTransfer)
                ->setShipmentSelection((string)$idShipmentMethod)
                ->setShippingAddress(clone $quoteTransfer->getBillingAddress());
            $item->setShipment($shipment);
        }

        return $quoteTransfer;
    }
}
