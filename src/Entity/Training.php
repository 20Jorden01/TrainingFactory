<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 */
class Training
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
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
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $costs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lesson", mappedBy="training", orphanRemoval=true)
     */
    private $training;

    public function __construct()
    {
        $this->training = new ArrayCollection();
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCosts(): ?string
    {
        return $this->costs;
    }

    public function setCosts(string $costs): self
    {
        $this->costs = $costs;

        return $this;
    }
    public function getDuration2()
    {
//        $tijd = date_create_from_format("h:i" , $this->duration, 'Europe/Amsterdam');
        return $this->duration->format('h:i');
    }

    public function getImg(): ?string
    {
        $test = "img/$this->name.png";
        return strtolower($test);
    }

    /**
     * @return Collection|Lesson[]
     */
    public function getTraining(): Collection
    {
        return $this->training;
    }

    public function addTraining(Lesson $training): self
    {
        if (!$this->training->contains($training)) {
            $this->training[] = $training;
            $training->setTraining($this);
        }

        return $this;
    }

    public function removeTraining(Lesson $training): self
    {
        if ($this->training->contains($training)) {
            $this->training->removeElement($training);
            // set the owning side to null (unless already changed)
            if ($training->getTraining() === $this) {
                $training->setTraining(null);
            }
        }

        return $this;
    }
}
