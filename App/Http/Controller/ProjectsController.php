<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace App\Http\Controller;

use App\Entity\Client;
use App\Entity\Project;
use InvalidArgumentException;
use PHP_SF\Framework\Http\Middleware\auth;
use PHP_SF\System\Attributes\Route;
use PHP_SF\System\Classes\Abstracts\AbstractController;
use PHP_SF\System\Core\RedirectResponse;
use PHP_SF\System\Core\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ProjectsController
 *
 * @package App\Http\Controller
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class ProjectsController extends AbstractController
{

    #[Route(url: '/projects/{$projectId}', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function project_page(int $projectId): Response
    {
        $project = Project::find($projectId);

        if ($project === null) {
            throw new NotFoundHttpException("Project with id $projectId not found");
        }

        return $this->render(project_page::class, compact('project'));
    }

    #[Route(url: '/projects/{projectId}/tasks', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function project_tasks_list_page(int $projectId): Response
    {
        $project = Project::find($projectId);

        if ($project === null) {
            throw new NotFoundHttpException("Project with id $projectId not found");
        }

        $tasks = $project->getTasks();

        return $this->render(project_projects_list_page::class, compact('tasks'));
    }

    #[Route(url: '/project/new', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function project_new_page(): Response
    {
        return $this->render(project_new_page::class);
    }

    #[Route(url: '/project/new', httpMethod: Request::METHOD_POST, middleware: [auth::class])]
    public function project_new(): RedirectResponse
    {
        $name = htmlspecialchars(trim($this->request->get('name', '')));

        if (empty($name)) {
            throw new InvalidArgumentException('Name is required');
        }

        $client = Client::find($this->request->get('user_id'));

        if ($client === null) {
            throw new InvalidArgumentException('Client not found');
        }

        $project = (new Project())
            ->setClient($client)
            ->setName($name);

        em()->persist($project);

        return $this->redirectTo('project_page', ['projectId' => $project->getId()]);
    }

    // TODO:: delete endpoint

}