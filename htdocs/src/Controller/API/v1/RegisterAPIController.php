<?php

namespace App\Controller\API\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/register")
 */
class RegisterAPIController extends AbstractController
{
    /**
     * @Route("/", name="api_register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {

        $json = $request->request->all();
        $data = json_decode($json, true);

        return new JsonResponse($data);

        return new JsonResponse([
            'error' => false,
            'msg' => 'Registration successful!'
        ]);
    }
}
