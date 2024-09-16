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
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'title.not_blank')]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title = null;

    /**
     * Slug.
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug = null;

    /**
     * Notices associated with the category.
     *
     * @var Collection|Notice[]
     */
    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Notice::class, cascade: ['persist', 'remove'], orphanRemoval: true, fetch: 'EXTRA_LAZY')]
    private Collection $notices;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->notices = new ArrayCollection();
    }

    // Getter and setter for Id

    /**
     * Get the ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for createdAt

    /**
     * Get created at.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set created at.
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter and setter for updatedAt

    /**
     * Get updated at.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set updated at.
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // Getter and setter for title

    /**
     * Get the title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title.
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    // Setter for creation date

    /**
     * Automatically set the created date before saving to the database.
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();  // Set the current date and time when creating
        }
    }

    // Setter for update date

    /**
     * Automatically set the updated date before saving to the database.
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();  // Set the current date and time for each update
    }

    // Getter and setter for slug

    /**
     * Get the slug.
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug.
     *
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    // Relation with notices

    /**
     * Get all notices related to this category.
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    /**
     * Add a notice to the category.
     *
     * @return $this
     */
    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setCategory($this);  // Set the reverse relationship
        }

        return $this;
    }

    /**
     * Remove a notice from the category.
     *
     * @return $this
     */
    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // If we remove the notice, reset the category in Notice
            if ($notice->getCategory() === $this) {
                $notice->setCategory(null);
            }
        }

        return $this;
    }
}
