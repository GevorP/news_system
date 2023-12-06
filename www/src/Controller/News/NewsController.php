<?php
namespace App\Controller\News;

use App\UseCase\Handler\CreateNewsHandler;
use App\UseCase\Handler\DeleteNewsHandler;
use App\UseCase\Handler\GetNewsHandler;
use App\UseCase\Handler\UpdateNewsHandler;
use App\UseCase\Input\BaseNewsInput;
use App\UseCase\Input\NewsInput;
use App\UseCase\Input\UpdateNewsInput;
use App\UseCase\Output\CreateNewsResponse;
use App\UseCase\Output\NewsResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[OA\RequestBody(required: true, content: new JsonContent(
        ref: new Model(type: NewsInput::class)
    ))]
    #[Route('/app/news', name: 'create_news', methods: [Request::METHOD_POST])]
    #[OA\Post(
        summary: 'Create news', responses: [
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'something wrong'),
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Ok',
                content: new JsonContent(
                    ref: new Model(type: CreateNewsResponse::class)
                )
            ),
        ]
    )]
    public function create(NewsInput $newsInput, CreateNewsHandler $createNewsHandler): JsonResponse
    {
        return $this->json($createNewsHandler($newsInput), Response::HTTP_CREATED);
    }

    #[OA\RequestBody(required: true, content: new JsonContent(
        ref: new Model(type: UpdateNewsInput::class)
    ))]
    #[Route('/app/news/{id}', name: 'update_news', methods: [Request::METHOD_PUT])]
    #[OA\Put(
        summary: 'Update news', responses: [
        new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'something wrong'),
        new OA\Response(response: Response::HTTP_OK, description: 'Ok'),
    ]
    )]
    public function update(UpdateNewsInput $newsInput, UpdateNewsHandler $updateNewsHandler): Response
    {
        $updateNewsHandler($newsInput);
        return new Response("updated", Response::HTTP_OK);
    }

    #[Route('/app/news/{id}', name: 'delete_news', methods: [Request::METHOD_DELETE])]
    #[OA\Delete(
        summary: 'Delete news', responses: [
        new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'something wrong'),
        new OA\Response(response: Response::HTTP_OK, description: 'Ok'),
    ]
    )]
    public function delete(BaseNewsInput $newsInput, DeleteNewsHandler $deleteNewsHandler): Response
    {
        $deleteNewsHandler($newsInput);
        return new Response("deleted", Response::HTTP_OK);
    }

    #[Route('/public/news/{id}', name: 'get_news', methods: [Request::METHOD_GET])]
    #[OA\Get(
        summary: 'Get news by id', responses: [
        new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'something wrong'),
        new OA\Response(
            response: Response::HTTP_OK,
            description: 'Ok',
            content: new JsonContent(
                ref: new Model(type: NewsResponse::class)
            )
        ),
    ]
    )]
    public function get(BaseNewsInput $newsInput, GetNewsHandler $getNewsHandler): JsonResponse
    {
        return $this->json($getNewsHandler($newsInput));
    }
}