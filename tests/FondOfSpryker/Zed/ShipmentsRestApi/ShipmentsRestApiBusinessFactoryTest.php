<?php

namespace FondOfSpryker\Zed\ShipmentsRestApi;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\ShipmentsRestApi\Business\Quote\ShipmentQuoteMapper;
use FondOfSpryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiDependencyProvider;

class ShipmentsRestApiBusinessFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $shipmentFacadeMock;

    /**
     * @var \FondOfSpryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiBusinessFactory
     */
    protected $shipmentsRestApiBusinessFactory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentFacadeMock = $this->getMockBuilder(ShipmentsRestApiToShipmentFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentsRestApiBusinessFactory = new ShipmentsRestApiBusinessFactory();
        $this->shipmentsRestApiBusinessFactory->setContainer($this->containerMock);
    }

    /**
     * @return void
     */
    public function testCreateShipmentQuoteMapper(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->with(ShipmentsRestApiDependencyProvider::FACADE_SHIPMENT)
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(ShipmentsRestApiDependencyProvider::FACADE_SHIPMENT)
            ->willReturn($this->shipmentFacadeMock);

        $this->assertInstanceOf(
            ShipmentQuoteMapper::class,
            $this->shipmentsRestApiBusinessFactory->createShipmentQuoteMapper()
        );
    }
}
