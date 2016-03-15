<?php

namespace BartFeenstra\DependencyRetrieverSymfonyBridge\Tests\Retriever;

use BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerParameterRetriever;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerParameterRetriever
 */
class ContainerParameterRetrieverTest extends \PHPUnit_Framework_TestCase
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
     * @var \BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever\ContainerParameterRetriever
     */
    protected $sut;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->sut = new ContainerParameterRetriever($this->name, $this->container->reveal());
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
        $this->container->hasParameter('known_dependency')->willReturn(true);
        $this->container->hasParameter('unknown_dependency')->willReturn(false);
        $this->assertTrue($this->sut->knowsDependency('known_dependency'));
        $this->assertFalse($this->sut->knowsDependency('unknown_dependency'));
    }

    /**
     * @covers ::retrieveDependency
     * @covers ::__construct
     */
    public function testRetrieveDependencyWithKnownDependency()
    {
        $parameter = 'Chocolate Lab';
        $this->container->getParameter('known_dependency')->willReturn($parameter);
        $this->assertSame($parameter, $this->sut->retrieveDependency('known_dependency'));
    }

    /**
     * @covers ::retrieveDependency
     * @covers ::__construct
     *
     * @expectedException \BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException
     */
    public function testRetrieveDependencyWithUnknownDependency()
    {
        $this->container->getParameter('known_dependency')->willThrow(new \InvalidArgumentException());
        $this->sut->retrieveDependency('known_dependency');
    }
}
