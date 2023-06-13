<?php
declare(strict_types = 1);

namespace App\Tests\support;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Create DI container
 * TODO use test kernel
 */
class TestCaseWithContainerBase extends TestCase
{
    protected ?ContainerBuilder $container;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $container = new ContainerBuilder();

        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../config')
        );
        $loader->load('config.php');

        $container->compile();  // For validate dependencies

        $this->container = $container;
    }
}