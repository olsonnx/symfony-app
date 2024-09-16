<?php
/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Category.
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[UniqueEntity(fields: ['title'])]
class Category
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title;

    /**
     * Slug.
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug;

    // Getter and setter for Id

    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for createdAt

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter and setter for updatedAt

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // Getter and setter for title

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Ustawia automatycznie datę utworzenia przed zapisem do bazy.
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();  // Ustaw bieżącą datę i czas przy tworzeniu
        }
    }

    /**
     * Ustawia automatycznie datę aktualizacji przed zapisem do bazy.
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();  // Ustaw bieżącą datę i czas przy każdej aktualizacji
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @var Collection|Notice[]
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Notice::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $notices;

    public function __construct()
    {
        $this->notices = new ArrayCollection();
    }

    public function getNotices(): Collection
    {
        return $this->notices;
    }

    /**
     * @return $this
     */
    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setCategory($this); // Ustawiamy relację zwrotną
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // Jeśli usuwamy ogłoszenie, resetujemy kategorię w Notice
            if ($notice->getCategory() === $this) {
                $notice->setCategory(null);
            }
        }

        return $this;
    }
}
