<?php

namespace App\Entity;

use App\Attribute\Crud;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Survos\WorkflowBundle\Traits\MarkingInterface;
use Survos\WorkflowBundle\Traits\MarkingTrait;
use Survos\WorkflowHelperBundle\Attribute\Workflow;
use Survos\WorkflowHelperBundle\Attribute\Transition;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[Workflow('PLACE_')]
class Task implements MarkingInterface
{
    use MarkingTrait;

    const PLACE_DRAFT = 'draft';
    const PLACE_REVIEWED = 'reviewed';
    const PLACE_REJECTED = 'rejected';
    const PLACE_PUBLISHED = 'published';

    #[Transition(self::PLACE_DRAFT, self::PLACE_REVIEWED)]
    const TRANSITION_REVIEW = 'review';

    #[Transition(self::PLACE_REVIEWED, self::PLACE_PUBLISHED)]
    const TRANSITION_PUBLISH = 'publish';

    #[Transition([self::PLACE_REVIEWED, self::PLACE_DRAFT], self::PLACE_REJECTED)]
    const TRANSITION_REJECT = 'reject';

    #[Transition([self::PLACE_REVIEWED, self::PLACE_PUBLISHED], self::PLACE_DRAFT)]
    const TRANSITION_RESET = 'reset';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
