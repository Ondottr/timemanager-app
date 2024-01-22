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

    #[Route(url: '/task/{$taskId}', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function task_page(int $taskId): Response
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundHttpException(_t('Task with id %s not found', $taskId));
        }

        return $this->render(task_page::class, compact('task'));
    }

    #[Route(url: '/task/{taskId}/time_records', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function task_time_records_list_page(int $taskId): Response
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundHttpException(_t('Task with id %s not found', $taskId));
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
            throw new InvalidArgumentException(_t('Name is required'));
        }

        $project = Project::find($this->request->get('project_id'));

        if ($project === null) {
            throw new InvalidArgumentException(_t('Project not found'));
        }

        $task = (new Task())
            ->setProject($project)
            ->setName($name);

        em()->persist($task);

        return $this->redirectTo('task_page', ['taskId' => $task->getId()]);
    }

    #[Route(url: '/task/{$taskId}/delete', httpMethod: Request::METHOD_POST, middleware: [auth::class])]
    public function task_delete(int $taskId): RedirectResponse
    {
        $task = Task::find($taskId);

        if ($task === null) {
            throw new NotFoundHttpException(_t('Task with id %s not found', $taskId));
        }

        $projectId = $task->getProject()->getId();

        em()->remove($task);

        return $this->redirectTo('project_tasks_list_page',
            get: compact('projectId'),
            messages: [_t('Task deleted')]
        );
    }

}