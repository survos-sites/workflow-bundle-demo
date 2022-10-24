<?php

namespace App\Workflow;

use App\Attribute\Crud;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Survos\WorkflowBundle\Traits\MarkingInterface;
use Survos\WorkflowBundle\Traits\MarkingTrait;
use Survos\WorkflowBundle\Attribute\Workflow;
use Survos\WorkflowBundle\Attribute\Transition;
use Survos\WorkflowBundle\Attribute\Place;

#[Workflow('PLACE_')]
class Publish
{
    const PLACE_DRAFT = 'draft';
    const PLACE_REVIEWED = 'reviewed';
    const PLACE_REJECTED = 'rejected';
    #[Place(metadata: ['description' => "Published on the website"])]
    const PLACE_PUBLISHED = 'published';

    #[Transition(self::PLACE_DRAFT, self::PLACE_REVIEWED, metadata: ['description' => 'When the draft is ready'])]
    const TRANSITION_REVIEW = 'review';

    #[Transition(from: self::PLACE_REVIEWED, to: self::PLACE_PUBLISHED,
        guard: "is_granted('ROLE_REVIEWER')")]
    const TRANSITION_PUBLISH = 'publish';

    #[Transition([self::PLACE_REVIEWED, self::PLACE_DRAFT], self::PLACE_REJECTED)]
    const TRANSITION_REJECT = 'reject';

    #[Transition([self::PLACE_REVIEWED, self::PLACE_PUBLISHED], self::PLACE_DRAFT)]
    const TRANSITION_RESET = 'reset';
}
