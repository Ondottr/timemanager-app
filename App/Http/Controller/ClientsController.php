<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace App\Http\Controller;

use App\Entity\Client;
use App\View\clients_list_page;
use InvalidArgumentException;
use PHP_SF\Framework\Http\Middleware\auth;
use PHP_SF\System\Attributes\Route;
use PHP_SF\System\Classes\Abstracts\AbstractController;
use PHP_SF\System\Core\RedirectResponse;
use PHP_SF\System\Core\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ClientsController
 *
 * @package App\Http\Controller
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class ClientsController extends AbstractController
{

    #[Route(url: '/clients', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function clients_list_page(): Response
    {
        $clients = user()->getClients();

        return $this->render(clients_list_page::class, compact('clients'));
    }

    #[Route(url: '/clients/{$clientId}', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function client_page(int $clientId): Response
    {
        $client = Client::find($clientId);

        if ($client === null) {
            throw new NotFoundHttpException("Client with id $clientId not found");
        }

        return $this->render(client_page::class, compact('client'));
    }

    #[Route(url: '/clients/{clientId}/projects', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function client_projects_list_page(int $clientId): Response
    {
        $client = Client::find($clientId);

        if ($client === null) {
            throw new NotFoundHttpException("Client with id $clientId not found");
        }

        $projects = $client->getProjects();

        return $this->render(client_projects_list_page::class, compact('projects'));
    }

    #[Route(url: '/client/new', httpMethod: Request::METHOD_GET, middleware: [auth::class])]
    public function client_new_page(): Response
    {
        return $this->render(client_new_page::class);
    }

    #[Route(url: '/client/new', httpMethod: Request::METHOD_POST, middleware: [auth::class])]
    public function client_new(): RedirectResponse
    {
        $name = htmlspecialchars(trim($this->request->get('name', '')));

        if (empty($name)) {
            throw new InvalidArgumentException('Name is required');
        }

        $client = (new Client())
            ->setUser(user())
            ->setName($name);

        em()->persist($client);

        return $this->redirectTo('client_page', ['clientId' => $client->getId()]);
    }

    // TODO:: delete endpoint

}