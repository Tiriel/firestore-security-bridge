# Firestore Security Bridge

A small bridge to be used in conjunction with [Firestore ODM Bundle](https://github.com/Tiriel/firestore-odm-bundle).
This bridge allows you to use your Firestore DTO as users in Symfony's Security Bundle.

## Requirements

This bundle requires PHP 8.2+, Symfony 7+, and [Firestore ODM Bundle](https://github.com/Tiriel/firestore-odm-bundle).

This bundle also relies heavily on [Google Glouc Firestore PHP Client](https://cloud.google.com/php/docs/reference/cloud-firestore/latest),
and its technical requirements are the same (most notably PHP gRPC and Protobuf extensions). See Google's documentation for more details.

## Installation

If you have Symfony Flex, a [contrib recipe](https://github.com/symfony/recipes-contrib) is available:

```shell
composer require tiriel/firestore-security-bridge
```

That's it, you're good to go.

If you haven't got Flex installed, first require the bundle as shown above.
Then, enable the bundle in the `config/bundles.php` file:

```php
<?php

return [
    // ...
    Tiriel\FirestoreOdmBundle\TirielFirestoreOdmBundle::class => ['all' => true],
    Tiriel\FirestoreSecurityBridge\TirielFirestoreSecurityBundle::class => ['all' => true],
];

```

You can now reuse the file `config/packages/tiriel.yaml` from Firestore ODM and add the following content:

```yaml
tiriel_firestore_odm:
    # ...

tiriel_firestore_security:
    # The service id or the manager responsible for the DTO you want to use as UserInterface
    user_manager: App\Manager\UserFirestoreDtoManager
```

## Usage

### User DTO

See the documentation for [Firestore ODM Bundle](https://github.com/Tiriel/firestore-odm-bundle) if you want
a basic understanding of how DTOs are used in Firebase ODM.

In addition, the DTO you wish to use as your Security user should extend `Tiriel\FirestoreSecurityBridge\Dto\AbstractUser`.
This class implements `Tiriel\FirestoreOdmBundle\Dto\Interface\PersistableDtoInterface`
and `Symfony\Component\Security\Core\User\UserInterface`.
If you want your users to be authenticated using password, you can still implement 
`Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface`.

Example:

```php
use Tiriel\FirestoreSecurityBridge\Dto\AbstractUser;

class User extends AbstractUser implements PasswordAuthenticatedUserInterface
{
    // ...
```

You can now create a manager for your DTO as usual per 
[Firestore ODM Bundle](https://github.com/Tiriel/firestore-odm-bundle)'s documentation.

### Using the DTO and Manager in the Security Bundle

You are now set to use your new DTO and its manager in your firewalls, simply define a new
User Provider in `config/packages/security.yaml` with the id `tiriel.firestore_security.user_provider`:

```yaml
security:
    # ...
    # https://symfony.com/doc/current/security.html#loading-the-user-the-user-provider
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_user_provider:
            id: 'tiriel.firestore_security.user_provider'
```

You are now set, you can use your User DTO as a security User.
