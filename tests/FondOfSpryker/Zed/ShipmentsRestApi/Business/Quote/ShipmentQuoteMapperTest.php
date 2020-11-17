<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use ReflectionMethod;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;

class ShipmentQuoteMapperTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface
     */
    protected $shipmentFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentTransfer
     */
    protected $shipmentTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected $shipmentMethodTransferMock;

    /**
     * @var \FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper
     */
    protected $shipmentQuoteMapper;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->shipmentFacadeMock = $this->getMockBuilder(ShipmentsRestApiToShipmentFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentTransferMock = $this->getMockBuilder(ShipmentTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentMethodTransferMock = $this->getMockBuilder(ShipmentMethodTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentQuoteMapper = new ShipmentQuoteMapper($this->shipmentFacadeMock);
    }

    /**
     * @return void
     */
    public function testCreateShippingExpenseTransfer(): void
    {
        $this->shipmentTransferMock->expects($this->atLeastOnce())
            ->method('getMethod')
            ->willReturn($this->shipmentMethodTransferMock);

        $this->shipmentMethodTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->shipmentMethodTransferMock->expects($this->atLeastOnce())
            ->method('getStoreCurrencyPrice')
            ->willReturn(1990);

        $method = new ReflectionMethod(get_class($this->shipmentQuoteMapper), 'createShippingExpenseTransfer');
        $method->setAccessible(true);

        $expenseTransfer = $method->invoke($this->shipmentQuoteMapper, $this->shipmentTransferMock, 'NET_MODE');

        $this->assertEquals(1990, $expenseTransfer->getUnitNetPrice());
    }
}
