<?php

declare(strict_types=1);

use App\Entity\Article;
use App\Entity\Task;
use Symfony\Config\FrameworkConfig;
use Survos\WorkflowHelperBundle\Attribute\Workflow;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (FrameworkConfig $framework) {
//return static function (ContainerConfigurator $containerConfigurator): void {

    foreach ([Task::class] as $workflowClass) {
        $reflectionClass = new \ReflectionClass($workflowClass);
        foreach ($reflectionClass->getAttributes(Workflow::class) as $attribute) {

//            dump($attribute->getArguments());
//            dd($attribute->newInstance());

        }
        $workflow = $framework->workflows()->workflows($reflectionClass->getShortName())
            ->supports($workflowClass)
            ;

        $constants = $reflectionClass->getConstants();

        $initial = null; // get from somewhere else.
        foreach ($constants as $name=>$value) {
            if (preg_match('/PLACE_/', $name)) {
                $workflow->place()->name($value);
                if (empty($initial)) {
                    $initial = $value;
                }
            }
            }
        }
    $workflow->initialMarking($initial);



        foreach (array_filter(array_keys($constants), function ($s) {
            return preg_match('/^TRANSITION_/', $s);
        }) as $transitionConstant) {

            $reflectionConstant = new ReflectionClassConstant($transitionConstant)

        }
//            ->initialMarking();

    }

//    $containerConfigurator->extension('framework', ['workflows' => []])

    $blogPublishing = $framework->workflows()->workflows('blog_publishing');
    $blogPublishing
        ->type('state_machine') // or 'state_machine'|workflow
        ->supports([Task::class])
        ->initialMarking($initial);

    $blogPublishing->auditTrail()->enabled(true);
    $blogPublishing->markingStore()
        ->type('method')
//        ->property('currentPlace')
    ;

    $blogPublishing->place()->name('draft');
    $blogPublishing->place()->name('reviewed');
    $blogPublishing->place()->name('rejected');
    $blogPublishing->place()->name('published');

    $blogPublishing->transition()
        ->name('to_review')
        ->from(['draft'])
        ->to(['reviewed']);

    $blogPublishing->transition()
        ->name('publish')
        ->from(['reviewed'])
        ->to(['published']);

    $blogPublishing->transition()
        ->name('reject')
        ->from(['reviewed'])
        ->to(['rejected']);

    return;

    $containerConfigurator->extension('framework', [
        'workflows' => []
        ]);
    return;

            $containerConfigurator->extension('framework', [
        'workflows' => [
            'article' => [
                'type' => 'workflow',
                'marking_store' => [
                    'type' => 'method',
                ],
                'supports' => [Article::class],
                'places' => [
                    'draft' => [
                        'metadata' => [
                            'title' => 'Draft',
                        ],
                    ],
                    'waiting for journalist' => [
                        'metadata' => [
                            'title' => 'Waiting for Journalist review',
                        ],
                    ],
                    'approved by journalist' => [
                        'metadata' => [
                            'title' => 'Approved By Journalist',
                        ],
                    ],
                    'wait for spellchecker' => [
                        'metadata' => [
                            'title' => 'Waiting for Spellchecker review',
                        ],
                    ],
                    'approved by spellchecker' => [
                        'metadata' => [
                            'title' => 'Approved By Spellchecker',
                        ],
                    ],
                    'published' => null,
                ],
                'transitions' => [
                    'request review' => [
                        'guard' => 'is_fully_authenticated()',
                        'from' => 'draft',
                        'to' => ['waiting for journalist', 'wait for spellchecker'],
                        'metadata' => [
                            'title' => 'Do you want a Review?',
                        ],
                    ],
                    'journalist approval' => [
                        'guard' => 'is_granted(\'ROLE_JOURNALIST\')',
                        'from' => 'waiting for journalist',
                        'to' => 'approved by journalist',
                        'metadata' => [
                            'title' => 'Do you valid the article?',
                        ],
                    ],
                    'spellchecker approval' => [
                        'guard' => 'is_fully_authenticated() and is_granted(\'ROLE_SPELLCHECKER\')',
                        'from' => 'wait for spellchecker',
                        'to' => 'approved by spellchecker',
                        'metadata' => [
                            'title' => 'Do you valid the spell check?',
                        ],
                    ],
                    'publish' => [
                        'guard' => 'is_fully_authenticated()',
                        'from' => ['approved by journalist', 'approved by spellchecker'],
                        'to' => 'published',
                        'metadata' => [
                            'title' => 'Do you want to publish?',
                        ],
                    ],
                ],
                'metadata' => [
                    'title' => 'Workflow to manager article',
                ],
                'audit_trail' => true,
            ],
            'task' => [
                'type' => 'state_machine',
                'supports' => [Task::class],
                'marking_store' => [
                    'type' => 'method',
                ],
                'places' => ['new', 'backlogged', 'processing', 'failed', 'completed'],
                'transitions' => [
                    'start_process' => [
                        'from' => 'new',
                        'to' => 'processing',
                    ],
                    'retry' => [
                        'from' => 'backlogged',
                        'to' => 'processing',
                    ],
                    'temp_error' => [
                        'from' => 'processing',
                        'to' => 'backlogged',
                    ],
                    'permanent_error' => [
                        'from' => 'processing',
                        'to' => 'failed',
                    ],
                    'complete_without_error' => [
                        'from' => 'processing',
                        'to' => 'completed',
                    ],
                ],
            ],
        ],
    ]);
};
