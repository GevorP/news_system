<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\RuntimeException;

#[RequestBody(required: true, content: new JsonContent(
	properties: [
		new Property(property: 'email', description: 'Email', type: 'string', example: 'user@gmail.com'),
		new Property(property: 'password', description: 'Password', type: 'string', example: '584d56434p5f'),
	],
))]
#[Route('/auth/login', name: 'login', methods: [Request::METHOD_POST])]
class AuthLoginController extends AbstractController
{
	public function __invoke(): Response
	{
		// $this->denyAccessUnlessGranted('ROLE_USER');
		throw new RuntimeException('something went wrong');
	}
}
