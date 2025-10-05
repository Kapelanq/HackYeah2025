<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    public function __construct(
        protected UsersRepository $usersRepository
    )
    {
    }


    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $username = $data['username'];
        $password = $data['password'];

        $user = $this->usersRepository->loginUser($username, $password);

        if (!$user) {
            return new JsonResponse([
                'message' => 'Invalid username or password',
            ], Response::HTTP_UNAUTHORIZED
            );
        }

        return new JsonResponse([
            'message' => 'Logged in successfully',
            'user' => $user->toArray(),
        ], Response::HTTP_OK
        );
    }
}
