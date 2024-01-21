<?php declare(strict_types=1);

namespace App\View;

use App\Entity\Client;
use PHP_SF\System\Classes\Abstracts\AbstractView;

/**
 * @property Client[] $clients
 */
// @formatter:off
final class clients_list_page extends AbstractView { public function show(): void { ?>
<!--@formatter:on-->

  <h1>Clients list page</h1>

    <?php dd($this->clients) ?>

  <!--@formatter:off-->
<?php } }