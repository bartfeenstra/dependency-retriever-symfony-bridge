<?php

namespace BartFeenstra\DependencyRetrieverSymfonyBridge\Tests\Retriever;

use BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerServiceRetriever;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerServiceRetriever
 */
class ContainerServiceRetrieverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The service container.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    protected $container;

    /**
     * The retriever's name.
     *
     * @var string
     */
    protected $name = 'Dirkjan';

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerServiceRetriever
     */
    protected $sut;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->sut = new ContainerServiceRetriever($this->name, $this->container->reveal());
    }

    /**
     * @covers ::getName
     * @covers ::__construct
     */
    public function testGetName()
    {
        $this->assertSame($this->name, $this->sut->getName());
    }

    /**
     * @covers ::knowsDependency
     * @covers ::__construct
     */
    public function testKnowsDependency()
    {
        $this->container->has('known_dependency')->willReturn(true);
        $this->container->has('unknown_dependency')->willReturn(false);
        $this->assertTrue($this->sut->knowsDependency('known_dependency'));
        $this->assertFalse($this->sut->knowsDependency('unknown_dependency'));
    }

    /**
     * @covers ::retrieveDependency
     * @covers ::__construct
     */
    public function testRetrieveDependencyWithKnownDependency()
    {
        $service = new \stdClass();
        $this->container->get('known_dependency')->willReturn($service);
        $this->assertSame($service, $this->sut->retrieveDependency('known_dependency'));
    }

    /**
     * @covers ::retrieveDependency
     * @covers ::__construct
     *
     * @expectedException \BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException
     */
    public function testRetrieveDependencyWithUnknownDependency()
    {
        $this->container->get('known_dependency')->willThrow(new ServiceNotFoundException('known_dependency'));
        $this->sut->retrieveDependency('known_dependency');
    }
}
