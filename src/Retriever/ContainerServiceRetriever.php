<?php

namespace BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever;

use BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException;
use BartFeenstra\DependencyRetriever\Retriever\Retriever;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Provides a Symfony container service retriever.
 */
class ContainerServiceRetriever implements Retriever
{

    /**
     * The service container.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * The retriever's name.
     *
     * @var string
     */
    protected $name;

    /**
     * Constructs a new instance.
     *
     * @param string $name
     *   The retriever's name.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The service container.
     */
    public function __construct($name, ContainerInterface $container)
    {
        $this->container = $container;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function knowsDependency($id)
    {
        return $this->container->has($id);
    }

    public function retrieveDependency($id)
    {
        try {
            return $this->container->get($id);
        } catch (ServiceNotFoundException $e) {
            throw new UnknownDependencyException($this->getName(), $id, $e);
        }
    }
}
