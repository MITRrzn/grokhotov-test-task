<?php

namespace App\Entity;

use App\Repository\UserMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserMessageRepository::class)]
class UserMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    #[ORM\Column(length: 64)]
    private ?string $email = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[Assert\NotBlank]
    #[Assert\Regex('/^9[[:digit:]]{9}$/', message: 'Please provide a valid phone number. 9xxxxxxxxx')]
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
