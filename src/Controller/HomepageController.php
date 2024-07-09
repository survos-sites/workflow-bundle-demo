<?php

namespace App\Controller;

use Survos\WorkflowBundle\Service\WorkflowHelperService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(WorkflowHelperService $workflowHelperService,
                          #[AutowireLocator('workflow.state_machine')] ServiceLocator $workflowsByTaggedIterator,
    ): Response
    {
        $workflows = $workflowHelperService->getWorkflowsGroupedByClass();
        $configuration =  $workflowHelperService->getWorkflowConfiguration();

        return $this->render('homepage/index.html.twig', [
            'workflows' => $workflows,
            'workflowsByTaggedIterator' => $workflowsByTaggedIterator,// $workflowHelperService->getWorkflowsFromTaggedIterator(),
            'workflowsConfiguration' => $configuration,
        ]);
    }
}
