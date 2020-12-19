<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @Assert\Range(
     *     min = -90,
     *     max = 90,
     *     notInRangeMessage="Zeměpisná šířka musí být v rozsahu od {{ min }} do {{ max }}",
     * )
     * @ORM\Column(type="decimal", precision=8, scale=6)
     */
    private string $latitude;

    /**
     * @Assert\Range(
     *     min = -180,
     *     max = 180,
     *     notInRangeMessage="Zeměpisná šířka musí být v rozsahu od {{ min }} do {{ max }}",
     * )
     * @ORM\Column(type="decimal", precision=9, scale=6)
     */
    private string $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $photoFilename;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="post", orphanRemoval=true)
     */
    private $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(string $photoFilename): self
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getReactionForUser(User $user)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('user', $user));

        return $this->likes->matching($criteria)->first();
    }
}
