<?php

namespace Tiriel\FirestoreSecurityBridge\Exception;

use Tiriel\FirestoreOdmBundle\Exception\FirestoreException;

class InvalidFirestoreDtoTypeException extends FirestoreException
{
    public function __construct(string $className)
    {
        parent::__construct("Security Firestore User DTO should extend AbstractUser", ['className' => $className]);
    }
}
