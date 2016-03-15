<?php

namespace BartFeenstra\DependencyRetrieverSymfonyBridge\Retriever;

use BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException;
use BartFeenstra\DependencyRetriever\Retriever\Retriever;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Symfony container parameter retriever.
 */
class ContainerParameterRetriever implements Retriever
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
        return $this->container->hasParameter($id);
    }

    public function retrieveDependency($id)
    {
        try {
            return $this->container->getParameter($id);
        } catch (\InvalidArgumentException $e) {
            throw new UnknownDependencyException($this->getName(), $id, $e);
        }
    }
}
