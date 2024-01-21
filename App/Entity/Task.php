<?php /** @noinspection MethodShouldBeFinalInspection */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use PHP_SF\System\Core\DateTime;
use PHP_SF\System\Traits\ModelProperty\ModelPropertyCreatedAtTrait;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'tasks')]
#[ORM\Cache(usage: 'READ_WRITE')]
#[ORM\Index(columns: ['is_completed', 'project_id'])]
class Task extends AbstractEntity
{
    use ModelPropertyCreatedAtTrait;


    #[TranslatablePropertyName('name')]
    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[TranslatablePropertyName('isCompleted')]
    #[ORM\Column(name: 'is_completed', type: Types::BOOLEAN, options: ['default' => false])]
    protected bool $completed = false;


    #[TranslatablePropertyName('project')]
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'project_id', nullable: false)]
    protected ?Project $project = null;

    #[ORM\OneToMany(mappedBy: 'taks', targetEntity: TimeRecord::class, orphanRemoval: true)]
    private Collection $timeRecords;


    public function __construct()
    {
        $this->createdAt = new DateTime;
        $this->timeRecords = new ArrayCollection();
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

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }


    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, TimeRecord>
     */
    public function getTimeRecords(): Collection
    {
        return $this->timeRecords;
    }

    public function addTimeRecord(TimeRecord $timeRecord): static
    {
        if (!$this->timeRecords->contains($timeRecord)) {
            $this->timeRecords->add($timeRecord);
            $timeRecord->setTask($this);
        }

        return $this;
    }

    public function removeTimeRecord(TimeRecord $timeRecord): static
    {
        if ($this->timeRecords->removeElement($timeRecord)) {
            // set the owning side to null (unless already changed)
            if ($timeRecord->getTask() === $this) {
                $timeRecord->setTask(null);
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
