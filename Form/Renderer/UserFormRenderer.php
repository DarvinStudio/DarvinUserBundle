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

use Darvin\UserBundle\Form\Factory\UserFormFactory;
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
     * @var \Darvin\UserBundle\Form\Factory\UserFormFactory
     */
    private $userFormFactory;

    /**
     * @param \Symfony\Component\Templating\EngineInterface   $templating      Templating
     * @param \Darvin\UserBundle\Form\Factory\UserFormFactory $userFormFactory User form factory
     */
    public function __construct(EngineInterface $templating, UserFormFactory $userFormFactory)
    {
        $this->templating = $templating;
        $this->userFormFactory = $userFormFactory;
    }

    /**
     * @param bool                                  $widget Whether to render widget
     * @param \Symfony\Component\Form\FormInterface $form   Form
     *
     * @return string
     */
    public function renderProfileForm($widget = true, FormInterface $form = null)
    {
        if (empty($form)) {
            $form = $this->userFormFactory->createProfileForm();
        }

        $template = $widget
            ? 'DarvinUserBundle:User/widget:profile.html.twig'
            : 'DarvinUserBundle:User:profile.html.twig';

        return $this->templating->render($template, array(
            'form' => $form->createView(),
        ));
    }
}
