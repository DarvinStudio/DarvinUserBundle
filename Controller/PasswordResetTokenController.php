<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Password reset token controller
 */
class PasswordResetTokenController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function requestAction(Request $request)
    {
        return new Response($this->getPasswordResetTokenFormRenderer()->renderRequestForm($request->isXmlHttpRequest()));
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\PasswordResetTokenFormRenderer
     */
    private function getPasswordResetTokenFormRenderer()
    {
        return $this->get('darvin_user.password_reset_token.form.renderer');
    }
}
