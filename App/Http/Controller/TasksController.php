<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace App\Http\Controller;

use App\Entity\Project;
use App\Entity\Task;
use InvalidArgumentException;
use PHP_SF\Framework\Http\Middleware\auth;
use PHP_SF\System\Attributes\Route;
use PHP_SF\System\Classes\Abstracts\AbstractController;
use PHP_SF\System\Core\RedirectResponse;
use PHP_SF\System\Core\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TasksController
 *
 * @package App\Http\Controller
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class TasksController extends AbstractController
{

    #[Route(url: '/tasks/{$taskId}', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function task_page(int $taskId): Response
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundHttpException("Task with id $taskId not found");
        }

        return $this->render(task_page::class, compact('task'));
    }

    #[Route(url: '/tasks/{taskId}/time_records', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function task_time_records_list_page(int $taskId): Response
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundHttpException("Task with id $taskId not found");
        }

        $timeRecords = $task->getTimeRecords();

        return $this->render(task_tasks_list_page::class, compact('timeRecords'));
    }

    #[Route(url: '/task/new', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function task_new_page(): Response
    {
        return $this->render(task_new_page::class);
    }

    #[Route(url: '/task/new', httpMethod: Request::METHOD_POST, middleware: [auth::class])]
    public function task_new(): RedirectResponse
    {
        $name = htmlspecialchars(trim($this->request->get('name', '')));

        if (empty($name)) {
            throw new InvalidArgumentException('Name is required');
        }

        $project = Project::find($this->request->get('project_id'));

        if ($project === null) {
            throw new InvalidArgumentException('Project not found');
        }

        $task = (new Task())
            ->setProject($project)
            ->setName($name);

        em()->persist($task);

        return $this->redirectTo('task_page', ['taskId' => $task->getId()]);
    }

    // TODO:: delete endpoint

}