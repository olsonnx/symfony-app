<?php
/**
 * Notice management app
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tag Entity.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\Table(name: 'tags')]
class Tag
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title of the tag.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'title.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'title.too_long')]
    private ?string $title = null;

    /**
     * Created at timestamp.
     */
    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at timestamp.
     */
    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Slug for SEO-friendly URLs.
     */
    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Assert\NotBlank(message: 'slug.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'slug.too_long')]
    private ?string $slug = null;

    /**
     * Notices associated with this tag.
     *
     * @var Collection<int, Notice>
     */
    #[ORM\ManyToMany(targetEntity: Notice::class, mappedBy: 'tags', fetch: 'EXTRA_LAZY')]
    private Collection $notices;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->notices = new ArrayCollection();
    }

    /**
     * Get the ID of the tag.
     *
     * @return int|null The ID of the tag
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the tag.
     *
     * @return string|null The title of the tag
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the tag.
     *
     * @param string $title The title of the tag
     *
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the created at timestamp.
     *
     * @return \DateTimeImmutable|null The created at timestamp
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the created at timestamp.
     *
     * @param \DateTimeImmutable $createdAt The creation timestamp
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the updated at timestamp.
     *
     * @return \DateTimeImmutable|null The updated at timestamp
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the updated at timestamp.
     *
     * @param \DateTimeImmutable $updatedAt The updated timestamp
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the slug for the tag.
     *
     * @return string|null The slug of the tag
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug for the tag.
     *
     * @param string $slug The slug of the tag
     *
     * @return $this
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get notices associated with the tag.
     *
     * @return Collection<int, Notice> Collection of notices
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    /**
     * Add a notice to the tag.
     *
     * @param Notice $notice The notice to add
     *
     * @return $this
     */
    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->addTag($this); // Keep the relationship consistent
        }

        return $this;
    }

    /**
     * Remove a notice from the tag.
     *
     * @param Notice $notice The notice to remove
     *
     * @return $this
     */
    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            $notice->removeTag($this); // Keep the relationship consistent
        }

        return $this;
    }
}
