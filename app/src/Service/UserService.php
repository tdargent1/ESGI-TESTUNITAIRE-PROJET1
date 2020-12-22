<?php

namespace App\Service;

use Carbon\Carbon;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isValid(User $user)
    {
        $exceptions = [];

        if (empty($user->lastname))
            array_push($exceptions, "Nom vide.");

        if (empty($user->firstname))
            array_push($exceptions, "Prénom vide.");

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL))
            array_push($exceptions, "Email vide.");

        if ($user->birthday->addYears(13)->isAfter(Carbon::now()))
            array_push($exceptions, "L'utilisateur doit avoir au moins 13 ans.");

        if (strlen($user->password) < 8 || strlen($user->password) > 40)
            array_push($exceptions, "Le mot de passe doit comprendre entre 8 et 40 catactères.");

        return $exceptions;
    }
}