<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ToDoList;
use App\Service\ItemService;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="items")
     */
    private $toDoList;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getToDoList(): ?ToDoList
    {
        return $this->toDoList;
    }

    public function setToDoList(?ToDoList $toDoList): self
    {
        $this->toDoList = $toDoList;

        return $this;
    }

    public function isValid(ItemService $itemService) 
    {
        $exceptions = [];

        if (empty($this->name))
            array_push($exceptions, "Nom vide.");

        if (! $itemService->checkIfItemNotExistByName($this->self))           
            array_push($exceptions, "Nom déjà utilisé.");

        if (empty($this->content))
            array_push($exceptions, "Content vide.");

        if (strlen($this->content) > 1000)
            array_push($exceptions, "Content trop long (maximum 1000 caractères).");
    
        return $exceptions;
    }
}
