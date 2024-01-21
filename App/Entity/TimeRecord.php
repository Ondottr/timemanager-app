<?php

namespace App\Entity;

use App\Repository\TimeRecordRepository;
use DateInterval;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Override;
use PHP_SF\System\Attributes\Validator\Constraints as Validate;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use PHP_SF\System\Core\DateTime;

#[ORM\Entity(repositoryClass: TimeRecordRepository::class)]
class TimeRecord extends AbstractEntity
{

    #[Validate\DateTime]
    #[TranslatablePropertyName('Start Time')]
    #[ORM\Column(name: 'start_time', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected string|DateTimeInterface|null $startTime;

    #[Validate\DateTime]
    #[TranslatablePropertyName('End Time')]
    #[ORM\Column(name: 'end_time', type: 'datetime', nullable: true)]
    protected string|DateTimeInterface|null $endTime;

    #[ORM\Column(nullable: true)]
    protected ?DateInterval $interval = null;


    #[TranslatablePropertyName('Task')]
    #[ORM\ManyToOne(inversedBy: 'timeRecords')]
    #[ORM\JoinColumn(name: 'task_id', nullable: false)]
    private ?Task $task = null;


    public function __construct()
    {
        $this->startTime = new DateTime;
    }


    public function getStartTime(): ?DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getInterval(): ?DateInterval
    {
        return $this->interval;
    }

    public function setInterval(?DateInterval $interval): static
    {
        $this->interval = $interval;

        return $this;
    }


    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }


    #[Override]
    public function getLifecycleCallbacks(): array
    {
        return [];
    }

}
