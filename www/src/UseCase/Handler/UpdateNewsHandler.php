<?php

namespace App\UseCase\Handler;

use App\Repository\News\NewsRepository;
use App\UseCase\Input\UpdateNewsInput;
use http\Exception\InvalidArgumentException;

class UpdateNewsHandler
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    )
    {
    }

    public function __invoke(UpdateNewsInput $newsInput) :void
    {
        $news = $this->newsRepository->find($newsInput->id);

        if(!$news){
            throw new InvalidArgumentException("Invalid news id");
        }

        $news->setDescription($newsInput->description);
        $news->setTitle($newsInput->title);
        $this->newsRepository->save($news);
    }
}