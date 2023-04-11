<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{

    private int $id;

    #[Assert\Length(min: 0, max: 150, maxMessage: 'Le titre ne doit pas faire plus de 150 caractères', minMessage: 'Le titre doit faire moins de 0 caractères')]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Le contenu ne doit pas être vide')]
    private string $content;

    #[Assert\NotBlank(message: 'L\'URL de l\'image ne doit pas etre vide')]
    private ?string $image = null;

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

    public function getTitle(): string
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

    public function getImage(): string
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
