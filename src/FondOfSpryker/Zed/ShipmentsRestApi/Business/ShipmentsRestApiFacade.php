<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacade as SprykerShipmentsRestApiFacade;

/**
 * @method \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory getFactory()
 */
class ShipmentsRestApiFacade extends SprykerShipmentsRestApiFacade implements ShipmentsRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapShipmentToQuoteItem(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $this->getFactory()->createShipmentQuoteMapper()
            ->mapShipmentToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
