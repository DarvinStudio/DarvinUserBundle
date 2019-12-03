<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller\Profile;

use Darvin\UserBundle\User\UserManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Profile show controller
 */
class ShowController
{
    /**
     * @var \Twig\Environment
     */
    private $twig;

    /**
     * @var \Darvin\UserBundle\User\UserManagerInterface
     */
    private $userManager;

    /**
     * @param \Twig\Environment                            $twig        Twig
     * @param \Darvin\UserBundle\User\UserManagerInterface $userManager User manager
     */
    public function __construct(Environment $twig, UserManagerInterface $userManager)
    {
        $this->twig = $twig;
        $this->userManager = $userManager;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        return new Response($this->twig->render('@DarvinUser/profile/show.html.twig', [
            'user' => $this->userManager->getCurrentUser(),
        ]));
    }
}
