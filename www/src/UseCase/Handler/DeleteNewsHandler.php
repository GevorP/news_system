<?php

namespace App\UseCase\Handler;

use App\Repository\News\NewsRepository;
use App\UseCase\Input\BaseNewsInput;
use http\Exception\InvalidArgumentException;

class DeleteNewsHandler
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    )
    {
    }

    public function __invoke(BaseNewsInput $newsInput) :void
    {
        $news = $this->newsRepository->find($newsInput->id);

        if(!$news){
            throw new InvalidArgumentException("Invalid news id");
        }

        $this->newsRepository->delete($news);
    }
}