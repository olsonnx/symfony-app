<?php
/**
 * Notice entity.
 */

namespace App\Entity;

use App\Entity\NoticeStatus;
use App\Repository\NoticeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Notice.
 */
#[ORM\Entity(repositoryClass: NoticeRepository::class)]
#[ORM\Table(name: 'notices')]
#[ORM\HasLifecycleCallbacks]
class Notice
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'title.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'title.too_long')]
    private ?string $title = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Content.
     *
     * @var string|null
     */
    #[ORM\Column(length: 4096, nullable: true)]
    #[Assert\NotBlank(message: 'title.not_blank')]
    #[Assert\Length(max: 4096, maxMessage: 'title.too_long')]
    private ?string $content = null;

    /**
     * Category.
     *
     * @var Category|null
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'notices', fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * Tags.
     *
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'notices', fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'notices_tags')]
    private Collection $tags;

    /**
     * Author.
     *
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $author = null;

    /**
     * Status of the notice.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $status = null;

    /**
     * Notice constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the notice ID.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the notice title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the notice title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the creation date.
     *
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the last update date.
     *
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Get the content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the category.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the category.
     *
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get tags.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag.
     *
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove a tag.
     *
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * Get the author.
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set the author.
     *
     * @param User|null $author
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get the notice status.
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the notice status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static
    {
        if (!in_array($status, NoticeStatus::getAvailableStatuses())) {
            throw new \InvalidArgumentException('Invalid status');
        }

        $this->status = $status;
        return $this;
    }

    /**
     * Get the label of the current status.
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return NoticeStatus::label($this->status);
    }
}
