<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2019, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\UserBundle\Username;

use Darvin\UserBundle\Repository\UserRepository;
use Darvin\Utils\Transliteratable\TransliteratorInterface;

/**
 * Username generator
 */
class UsernameGenerator implements UsernameGeneratorInterface
{
    /**
     * @var \Darvin\Utils\Transliteratable\TransliteratorInterface
     */
    private $transliterator;

    /**
     * @var \Darvin\UserBundle\Repository\UserRepository
     */
    private $userRepository;

    /**
     * @param \Darvin\Utils\Transliteratable\TransliteratorInterface $transliterator Transliterator
     * @param \Darvin\UserBundle\Repository\UserRepository           $userRepository User entity repository
     */
    public function __construct(TransliteratorInterface $transliterator, UserRepository $userRepository)
    {
        $this->transliterator = $transliterator;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function generateUsername(?string $source, $userId = null): ?string
    {
        if (empty($source)) {
            return null;
        }

        $username = $this->transliterator->transliterate($source);

        if (empty($username)) {
            return null;
        }

        $similar = $this->userRepository->getSimilarUsernames($username, $userId);

        if (empty($similar)) {
            return $username;
        }

        $i = 1;

        do {
            $unique = implode('-', [$username, $i]);

            $i++;
        } while (in_array($unique, $similar));

        return $unique;
    }
}
