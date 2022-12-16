<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\UserShopItemStatus;
use App\Repository\UserShopItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserShopItemRepository::class)]
#[ORM\UniqueConstraint(fields: ['user', 'shopItem'])]
class UserShopItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userShopItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userShopItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopItem $shopItem = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $status = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getShopItem(): ?ShopItem
    {
        return $this->shopItem;
    }

    public function setShopItem(?ShopItem $shopItem): self
    {
        $this->shopItem = $shopItem;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): UserShopItemStatus
    {
        return UserShopItemStatus::tryFrom($this->status);
    }

    public function setStatus(UserShopItemStatus $status): self
    {
        $this->status = $status->value;

        return $this;
    }
}
