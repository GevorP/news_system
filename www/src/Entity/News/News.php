<?php

declare(strict_types=1);

namespace App\Entity\News;

use App\Repository\News\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: 'news', schema: 'public')]
class News
{
	#[ORM\Id]
	#[ORM\Column(type: 'uuid')]
	#[ORM\GeneratedValue(strategy: 'CUSTOM')]
	#[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
	private Uuid $id;

	#[ORM\Column(type: 'string', length: 255)]
	private string $title;

	#[ORM\Column(type: 'text')]
	private string $description;

	public function __construct(
		string $title,
		string $description
	) {
		$this->id = Uuid::v4();
		$this->title = $title;
		$this->description = $description;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getId(): UuidV4|Uuid
	{
		return $this->id;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}
}
