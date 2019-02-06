<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
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
class UserFormRenderer
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
     * @param \Symfony\Component\Form\FormInterface $form   Form
     * @param bool                                  $widget Whether to render widget
     *
     * @return string
     */
    public function renderProfileForm(FormInterface $form = null, $widget = true)
    {
        if (empty($form)) {
            $form = $this->userFormFactory->createProfileForm();
        }

        $template = $widget
            ? '@DarvinUser/user/_profile.html.twig'
            : '@DarvinUser/user/profile.html.twig';

        return $this->templating->render($template, [
            'form' => $form->createView(),
        ]);
    }
}
