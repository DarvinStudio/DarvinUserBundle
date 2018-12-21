<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\DependencyInjection;

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DarvinUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new ConfigInjector())->inject($this->processConfiguration(new Configuration(), $configs), $container, $this->getAlias());

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach ([
            'configuration/roles',
            'password_reset_token',
            'security',
            'user',
        ] as $resource) {
            $loader->load($resource.'.yml');
        }

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DarvinAdminBundle'])) {
            $loader->load('admin.yml');
        }
        if (isset($bundles['DarvinConfigBundle'])) {
            $loader->load('configuration/configuration.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DarvinAdminBundle'])) {
            $config = $this->processConfiguration(
                new Configuration(),
                $container->getParameterBag()->resolveValue($container->getExtensionConfig($this->getAlias()))
            );

            $container->prependExtensionConfig('darvin_admin', [
                'sections' => [
                    [
                        'alias'  => 'user',
                        'entity' => $config['user_class'],
                        'config' => '@DarvinUserBundle/Resources/config/admin/user.yml',
                    ],
                ],
            ]);
        }
    }
}
