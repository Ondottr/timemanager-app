<?php /** @noinspection MethodShouldBeFinalInspection */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use PHP_SF\System\Core\DateTime;
use PHP_SF\System\Traits\ModelProperty\ModelPropertyCreatedAtTrait;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'projects')]
#[ORM\Cache(usage: 'READ_WRITE')]
#[ORM\Index(columns: ['is_active', 'client_id'])]
class Project extends AbstractEntity
{
    use ModelPropertyCreatedAtTrait;


    #[TranslatablePropertyName('name')]
    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[TranslatablePropertyName('Is active')]
    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    protected bool $isActive = true;


    #[TranslatablePropertyName('client')]
    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(name: 'client_id', nullable: false)]
    protected ?Client $client = null;


    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Task::class, orphanRemoval: true)]
    private Collection $tasks;


    public function __construct()
    {
        $this->createdAt = new DateTime;

        $this->tasks = new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }


    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }


    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }


    #[Override]
    public function getLifecycleCallbacks(): array
    {
        return [];
    }

}
