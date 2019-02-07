<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015-2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Renderer;

use Darvin\UserBundle\Form\Factory\UserFormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * User form renderer
 */
class UserFormRenderer implements UserFormRendererInterface
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \Darvin\UserBundle\Form\Factory\UserFormFactoryInterface
     */
    private $userFormFactory;

    /**
     * @param \Symfony\Component\Templating\EngineInterface            $templating      Templating
     * @param \Darvin\UserBundle\Form\Factory\UserFormFactoryInterface $userFormFactory User form factory
     */
    public function __construct(EngineInterface $templating, UserFormFactoryInterface $userFormFactory)
    {
        $this->templating = $templating;
        $this->userFormFactory = $userFormFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function renderProfileForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->userFormFactory->createProfileForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/profile/%sedit.html.twig', $partial ? '_' : '');
        }

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
