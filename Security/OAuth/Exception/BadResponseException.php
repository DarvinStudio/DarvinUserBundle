<?php declare(strict_types=1);
/**
 * @author    Lev Semin <lev@darvin-studio.ru>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Security\OAuth\Exception;

/**
 * Bad response exception
 */
class BadResponseException extends \LogicException
{
    /**
     * @param mixed  $response      Response
     * @param string $requiredClass Required response class
     */
    public function __construct($response, string $requiredClass)
    {
        parent::__construct(sprintf(
            'OAuth response must be instance of %s, %s given.',
            $requiredClass,
            is_object($response) ? get_class($response) : 'not object'
        ));
    }
}
