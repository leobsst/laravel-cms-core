<?php

namespace Leobsst\LaravelCmsCore\Services;

class RolesService
{
    public static function getRoleLongName(?string $role): ?string
    {
        return match ($role) {
            'user' => 'Utilisateur',
            'editor' => 'Éditeur',
            'manager' => 'Manager',
            'owner' => 'Propriétaire',
            'admin' => 'Administrateur',
            default => null,
        };
    }
}
