<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\DependencyInjection;

use Darvin\Utils\DependencyInjection\ConfigInjector;
use Darvin\Utils\DependencyInjection\ConfigLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
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
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        (new ConfigInjector($container))->inject($config, $this->getAlias());

        (new ConfigLoader($container, __DIR__.'/../Resources/config'))->load([
            'authentication',
            'password_reset_token',
            'profile',
            'role',
            'security',
            'user',
            'username',

            'admin'         => ['bundle' => 'DarvinAdminBundle'],

            'configuration' => ['bundle' => 'DarvinConfigBundle'],

            'dev/fixture'   => ['env' => 'dev'],

            'mailer'        => ['callback' => function () use ($config) {
                return $config['mailer']['enabled'];
            }],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $bundles     = $container->getParameter('kernel.bundles');
        $utilsConfig = $this->processConfiguration(
            new \Darvin\UtilsBundle\DependencyInjection\Configuration(),
            $container->getParameterBag()->resolveValue($container->getExtensionConfig('darvin_utils'))
        );

        if (!$utilsConfig['mailer']['enabled']) {
            $container->prependExtensionConfig($this->getAlias(), [
                'mailer' => [
                    'enabled' => false,
                ],
            ]);
        }
        if (isset($bundles['DarvinAdminBundle'])) {
            $config = $this->processConfiguration(
                new Configuration(),
                $container->getParameterBag()->resolveValue($container->getExtensionConfig($this->getAlias()))
            );

            $container->prependExtensionConfig('darvin_admin', [
                'sections' => [
                    $config['user_class'] => [
                        'alias'  => 'user',
                        'config' => '@DarvinUserBundle/Resources/config/admin/user.yaml',
                    ],
                ],
            ]);
        }
    }
}
