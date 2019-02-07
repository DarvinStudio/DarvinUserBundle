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

use Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Profile form renderer
 */
class ProfileFormRenderer implements ProfileFormRendererInterface
{
    /**
     * @var \Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface
     */
    private $profileFormFactory;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @param \Darvin\UserBundle\Form\Factory\ProfileFormFactoryInterface $profileFormFactory Profile form factory
     * @param \Symfony\Component\Templating\EngineInterface               $templating         Templating
     */
    public function __construct(ProfileFormFactoryInterface $profileFormFactory, EngineInterface $templating)
    {
        $this->profileFormFactory = $profileFormFactory;
        $this->templating = $templating;
    }

    /**
     * {@inheritDoc}
     */
    public function renderEditForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->profileFormFactory->createEditForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/profile/%sedit.html.twig', $partial ? '_' : '');
        }

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function renderPasswordChangeForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string
    {
        if (empty($form)) {
            $form = $this->profileFormFactory->createPasswordChangeForm();
        }
        if (empty($template)) {
            $template = sprintf('@DarvinUser/profile/%schange_password.html.twig', $partial ? '_' : '');
        }

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
