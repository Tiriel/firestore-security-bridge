<?php

namespace Tiriel\FirestoreSecurityBridge\Security\Provider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tiriel\FirestoreOdmBundle\Dto\Interface\PersistableDtoInterface;
use Tiriel\FirestoreOdmBundle\Exception\EntryNotFoundFirestoreException;
use Tiriel\FirestoreOdmBundle\Manager\Interface\DtoManagerInterface;
use Tiriel\FirestoreSecurityBridge\Dto\AbstractUser;
use Tiriel\FirestoreSecurityBridge\Exception\InvalidFirestoreDtoTypeException;

abstract class FirestoreUserProvider implements UserProviderInterface
{
    public function __construct(
        protected readonly DtoManagerInterface $manager,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user): AbstractUser
    {
        return $this->fetchUser($user->getUserIdentifier());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class): bool
    {
        return $this->getClass() === $class || is_subclass_of($class, $this->getClass());
    }

    /**
     * @inheritDoc
     */
    public function loadUserByIdentifier(string $identifier): AbstractUser
    {
        return $this->fetchUser($identifier);
    }

    protected function fetchUser(string $identifier): AbstractUser
    {
        if (!is_subclass_of($this->getClass(), AbstractUser::class)) {
            throw new InvalidFirestoreDtoTypeException($this->getClass());
        }

        /** @var PersistableDtoInterface&UserInterface[] $results */
        $results = $this->manager->search(['email', '=', $identifier]);

        if (empty($results)) {
            throw new EntryNotFoundFirestoreException($identifier, $this->getClass());
        }

        return $results[0];
    }

    public function getClass(): string
    {
        return $this->manager->getClass();
    }
}
