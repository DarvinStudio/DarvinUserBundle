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
 * User controller
 */
class UserController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        return new Response($this->getUserFormRenderer()->renderProfileForm($request->isXmlHttpRequest()));
    }

    /**
     * @return \Darvin\UserBundle\Form\Renderer\UserFormRenderer
     */
    private function getUserFormRenderer()
    {
        return $this->get('darvin_user.user.form.renderer');
    }
}
