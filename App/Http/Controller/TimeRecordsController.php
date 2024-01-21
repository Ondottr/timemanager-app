<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace App\Http\Controller;

use App\Entity\Task;
use App\Entity\TimeRecord;
use InvalidArgumentException;
use PHP_SF\Framework\Http\Middleware\auth;
use PHP_SF\System\Attributes\Route;
use PHP_SF\System\Classes\Abstracts\AbstractController;
use PHP_SF\System\Core\RedirectResponse;
use PHP_SF\System\Core\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TimeRecordsController
 *
 * @package App\Http\Controller
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class TimeRecordsController extends AbstractController
{

    #[Route(url: '/time_record/new', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function time_record_new_page(): Response
    {
        return $this->render(time_record_new_page::class);
    }

    #[Route(url: '/time_record/new', httpMethod: Request::METHOD_POST, middleware: [auth::class])]
    public function time_record_new(): RedirectResponse
    {
        $task = Task::find($this->request->get('task_id'));

        if ($task === null) {
            throw new InvalidArgumentException('Task not found');
        }

        $timeRecord = (new TimeRecord())
            ->setTask($task);

        em()->persist($timeRecord);

        return $this->redirectTo('time_record_page', ['timeRecordId' => $timeRecord->getId()]);
    }

    // TODO:: delete endpoint

}