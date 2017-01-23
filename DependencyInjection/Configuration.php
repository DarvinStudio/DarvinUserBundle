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

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('darvin_user');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('already_logged_in_redirect_route')->defaultValue('darvin_page_homepage')->end()
                ->integerNode('password_reset_token_lifetime')->defaultValue(3 * 60 * 60)->end()
                ->scalarNode('public_firewall_name')->defaultValue('public_area')->end()
                ->arrayNode('roles')->prototype('scalar')->end()->end()
                ->scalarNode('user_class')
                    ->defaultValue(BaseUser::class)
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !class_exists($v);
                        })
                        ->thenInvalid('Class does not exist.')
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return $v !== BaseUser::class && !is_subclass_of($v, BaseUser::class);
                        })
                        ->thenInvalid(sprintf('Class must be "%s" or subclass of it.', BaseUser::class))
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
