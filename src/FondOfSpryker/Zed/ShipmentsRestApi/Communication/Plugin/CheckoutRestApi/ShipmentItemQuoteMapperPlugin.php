<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacadeInterface getFacade()
 */
class ShipmentItemQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function map(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->mapShipmentToQuoteItem($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
