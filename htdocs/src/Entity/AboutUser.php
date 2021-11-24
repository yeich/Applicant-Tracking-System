<?php

namespace App\Entity;

use App\Repository\AboutUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AboutUserRepository::class)
 */
class AboutUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="aboutUser", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentJobTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentEmployer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lookingFor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrentJobTitle(): ?string
    {
        return $this->currentJobTitle;
    }

    public function setCurrentJobTitle(?string $currentJobTitle): self
    {
        $this->currentJobTitle = $currentJobTitle;

        return $this;
    }

    public function getCurrentEmployer(): ?string
    {
        return $this->currentEmployer;
    }

    public function setCurrentEmployer(?string $currentEmployer): self
    {
        $this->currentEmployer = $currentEmployer;

        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(?string $motivation): self
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getLookingFor(): ?string
    {
        return $this->lookingFor;
    }

    public function setLookingFor(?string $lookingFor): self
    {
        $this->lookingFor = $lookingFor;

        return $this;
    }
}
