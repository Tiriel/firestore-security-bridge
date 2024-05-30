<?php

namespace Tiriel\FirestoreSecurityBridge\Dto;

use Symfony\Component\Security\Core\User\UserInterface;
use Tiriel\FirestoreOdmBundle\Dto\Interface\PersistableDtoInterface;

abstract class AbstractUser implements PersistableDtoInterface, UserInterface
{
}
