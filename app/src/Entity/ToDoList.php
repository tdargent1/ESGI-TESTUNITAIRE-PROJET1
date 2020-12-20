<?php

namespace App\Entity;

use Exception;
use App\Entity\Item;
use App\Service\ToDoListService;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ToDoListRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 */
class ToDoList
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
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastAddedTime;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="toDoList")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLastAddedTime(): ?\DateTimeInterface
    {
        return $this->lastAddedTime;
    }

    public function setLastAddedTime(\DateTimeInterface $lastAddedTime): self
    {
        $this->lastAddedTime = $lastAddedTime;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ToDoListService  $todoService, Item $item): self
    {
        $todoService->checkTimeBetweenAdding($this->self, $item);

        if($todoService->checkEnvoieMail($this->user)) {
            $mailService = new MailService();
            $mailService->envoieMail(
                $user->getEmail(), 
                "ToDoList - Alerte", 
                "Vous venez d'ajouter un huitième élément à votre ToDoLis"
            );
        }

        if (! $this->items->contains($item)) {
            $this->items[] = $item;
            $item->setToDoList($this);
        }
            
        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getToDoList() === $this) {
                $item->setToDoList(null);
            }
        }

        return $this;
    }

    public function isValid() 
    {
        if (empty($this->name))
            throw new Exception("Nom vide.");

        if (empty($this->description))
            throw new Exception("Description vide.");
    
        return true;
    }
}