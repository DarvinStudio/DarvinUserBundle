<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Event;

use Darvin\UserBundle\Entity\BaseUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * User event
 */
class UserEvent extends Event
{
    /**
     * @var \Darvin\UserBundle\Entity\BaseUser
     */
    private $user;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response|null
     */
    private $response;

    /**
     * @param \Darvin\UserBundle\Entity\BaseUser        $user    User
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     */
    public function __construct(BaseUser $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;

        $this->response = null;
    }

    /**
     * @return \Darvin\UserBundle\Entity\BaseUser
     */
    public function getUser(): BaseUser
    {
        return $this->user;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response|null $response response
     *
     * @return UserEvent
     */
    public function setResponse(?Response $response): UserEvent
    {
        $this->response = $response;

        return $this;
    }
}
