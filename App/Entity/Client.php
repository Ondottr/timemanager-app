<?php /** @noinspection MethodShouldBeFinalInspection */
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Override;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use PHP_SF\System\Core\DateTime;
use PHP_SF\System\Traits\ModelProperty\ModelPropertyCreatedAtTrait;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'clients')]
#[ORM\Cache(usage: 'READ_WRITE')]
#[ORM\Index(columns: ['is_active', 'user_id'])]
class Client extends AbstractEntity
{
    use ModelPropertyCreatedAtTrait;


    #[TranslatablePropertyName('name')]
    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[TranslatablePropertyName('isActive')]
    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    protected bool $isActive = true;


    #[TranslatablePropertyName('user')]
    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    protected ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Project::class, orphanRemoval: true)]
    private Collection $projects;


    public function __construct()
    {
        $this->createdAt = new DateTime;

        $this->projects = new ArrayCollection;
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setClient($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getClient() === $this) {
                $project->setClient(null);
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
