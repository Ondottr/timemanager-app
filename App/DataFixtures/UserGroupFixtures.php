<?php declare( strict_types=1 );

namespace App\DataFixtures;

use App\Abstraction\Classes\AbstractDatabaseFixture;

/**
 * Class UserGroupFixtures
 *
 * @package App\DataFixtures
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class UserGroupFixtures extends AbstractDatabaseFixture
{

    protected function loadTable(): array|string
    {
        return <<<SQL
INSERT INTO user_groups (id, name)
VALUES
    (-1, 'banned'),
    (1, 'administrator'),
    (3, 'moderator'),
    (6, 'user');
SQL;
    }

    protected function loadFunctions(): array|string
    {
        return file_get_contents( __DIR__ . '/../../Doctrine/fixtures/user_groups_prevent_any_changes_function.sql' );
    }

    protected function loadTriggers(): array|string
    {
        return file_get_contents( __DIR__ . '/../../Doctrine/fixtures/user_groups_prevent_any_changes_trigger.sql' );
    }

}