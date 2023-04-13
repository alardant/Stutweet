<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Post')] //Pour renommer l'entité)
class Post
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: true, length: 150)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', length: 320)]
    private string $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private $user;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): mixed
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): mixed
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
