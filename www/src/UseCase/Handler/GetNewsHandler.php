<?php

declare(strict_types=1);

namespace App\UseCase\Handler;

use App\Repository\News\NewsRepository;
use App\UseCase\Input\BaseNewsInput;
use App\UseCase\Output\NewsResponse;
use http\Exception\InvalidArgumentException;

class GetNewsHandler
{
	public function __construct(
		private readonly NewsRepository $newsRepository,
	) {
	}

	public function __invoke(BaseNewsInput $newsInput): NewsResponse
	{
		$news = $this->newsRepository->find($newsInput->id);

		if (! $news) {
			throw new InvalidArgumentException('Invalid news id');
		}

		return new NewsResponse(
			id: $news->getId(),
			title: $news->getDescription(),
			description: $news->getTitle(),
		);
	}
}
