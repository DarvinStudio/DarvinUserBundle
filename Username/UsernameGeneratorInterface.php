<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Username;

/**
 * Username generator
 */
interface UsernameGeneratorInterface
{
    /**
     * @param string|null $source Source
     * @param mixed|null  $userId User ID
     *
     * @return string|null
     */
    public function generateUsername(?string $source, $userId = null): ?string;
}
