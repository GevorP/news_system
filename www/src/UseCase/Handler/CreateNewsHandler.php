<?php

namespace App\UseCase\Handler;

use App\Entity\News\News;
use App\Repository\News\NewsRepository;
use App\UseCase\Input\NewsInput;
use App\UseCase\Output\CreateNewsResponse;

class CreateNewsHandler
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    )
    {
    }

    public function __invoke(NewsInput $newsInput) :CreateNewsResponse
    {
        $news = new News(
           title: $newsInput->title,
           description: $newsInput->description
        );
        $this->newsRepository->save($news);
        return new CreateNewsResponse($news->getId());
    }
}