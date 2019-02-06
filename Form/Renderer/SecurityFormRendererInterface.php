<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Form\Renderer;

use Symfony\Component\Form\FormInterface;

/**
 * Security form renderer
 */
interface SecurityFormRendererInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface|null $form     Form
     * @param bool                                       $partial  Whether to render partial
     * @param string|null                                $template Template
     *
     * @return string
     */
    public function renderLoginForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string;

    /**
     * @param \Symfony\Component\Form\FormInterface $form     Form
     * @param bool                                  $partial  Whether to render partial
     * @param string|null                           $template Template
     *
     * @return string
     */
    public function renderPasswordResetForm(FormInterface $form, bool $partial = true, ?string $template = null): string;

    /**
     * @param \Symfony\Component\Form\FormInterface|null $form     Form
     * @param bool                                       $partial  Whether to render partial
     * @param string|null                                $template Template
     *
     * @return string
     */
    public function renderRegistrationForm(?FormInterface $form = null, bool $partial = true, ?string $template = null): string;
}
