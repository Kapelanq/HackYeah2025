<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function getMap(): Response
    {

    }
}
