<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LessonRepository")
 */
class Lesson
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPersons;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $intructor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Training", inversedBy="training")
     * @ORM\JoinColumn(nullable=false)
     */
    private $training;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="lesson", orphanRemoval=true)
     */
    private $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMaxPersons(): ?int
    {
        return $this->maxPersons;
    }

    public function setMaxPersons(int $maxPersons): self
    {
        $this->maxPersons = $maxPersons;

        return $this;
    }

    public function getTrainingId(): ?Training
    {
        return $this->training_id;
    }

    public function setTrainingId(?Training $training_id): self
    {
        $this->training_id = $training_id;

        return $this;
    }

    public function getIntructor(): ?user
    {
        return $this->intructor;
    }

    public function setIntructor(?user $intructor): self
    {
        $this->intructor = $intructor;

        return $this;
    }

    public function getTime2()
    {
        return $this->time->format('H:i');
    }

    public function getDate2()
    {
        return $this->date->format('Y-m-d');
    }

    public function getDeelnemersAantal($registrations, $lesid){
        $aantal = 0;
        foreach($registrations as $reg){
            if($reg->getLesson()->getId() == $lesid ){
                $aantal ++;
            }
        }
        return $aantal;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): self
    {
        $this->training = $training;

        return $this;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setLesson($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getLesson() === $this) {
                $registration->setLesson(null);
            }
        }

        return $this;
    }
}
