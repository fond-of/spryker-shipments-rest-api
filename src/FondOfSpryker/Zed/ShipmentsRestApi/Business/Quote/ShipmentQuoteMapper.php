<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote;

use FondOfSpryker\Shared\ShipmentsRestApi\ShipmentsRestApiConfig;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapperInterface;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

class ShipmentQuoteMapper implements ShipmentQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentsRestApiToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (
            !$restCheckoutRequestAttributesTransfer->getShipment()
            || !$restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod()
        ) {
            return $quoteTransfer;
        }
        $idShipmentMethod = $restCheckoutRequestAttributesTransfer->getShipment()->getIdShipmentMethod();

        $shipmentMethodTransfer = $this->shipmentFacade->findAvailableMethodById($idShipmentMethod, $quoteTransfer);
        if ($shipmentMethodTransfer === null) {
            return $quoteTransfer;
        }

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod($shipmentMethodTransfer)
            ->setShipmentSelection((string)$idShipmentMethod)
            ->setShippingAddress($quoteTransfer->getShippingAddress());

        $quoteTransfer = $this->setShipmentTransferIntoQuote($quoteTransfer, $shipmentTransfer);

        $expenseTransfer = $this->createShippingExpenseTransfer($shipmentTransfer, $quoteTransfer->getPriceMode());
        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(
        ShipmentTransfer $shipmentTransfer,
        string $priceMode
    ): ExpenseTransfer {
        $storeCurrecnyPrice = $shipmentTransfer->getMethod()->getStoreCurrencyPrice();

        return (new ExpenseTransfer())
            ->fromArray($shipmentTransfer->getMethod()->toArray(), true)
            ->setType(ShipmentsRestApiConfig::SHIPMENT_EXPENSE_TYPE)
            ->setQuantity(1)
            ->setShipment($shipmentTransfer)
            ->setUnitNetPrice($priceMode === ShipmentsRestApiConfig::PRICE_MODE_NET ? $storeCurrecnyPrice : 0)
            ->setUnitGrossPrice($priceMode === ShipmentsRestApiConfig::PRICE_MODE_GROSS ? $storeCurrecnyPrice : 0);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentTransferIntoQuote(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): QuoteTransfer
    {
        $quoteTransfer->setShipment($shipmentTransfer);
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }
}
