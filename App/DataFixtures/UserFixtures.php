<?php declare( strict_types=1 );

namespace App\DataFixtures;

use App\Abstraction\Classes\AbstractDatabaseFixture;
use App\Enums\UserGroupEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class UserFixtures
 *
 * @package App\DataFixtures
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class UserFixtures extends AbstractDatabaseFixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            UserGroupFixtures::class,
        ];
    }

    protected function loadTable(): array|string
    {
        return [
            sprintf(
                "INSERT INTO users (id, email, password, user_group_id) VALUES (1, '%s', '%s', %s);",
                env('ADMIN_EMAIL'),
                password_hash(env('ADMIN_PASSWORD'), PASSWORD_DEFAULT),
                UserGroupEnum::ADMINISTRATOR->getId()
            ),
        ];
    }

    protected function loadFunctions(): array|string
    {
        return file_get_contents(__DIR__ . '/../../Doctrine/fixtures/user_prevent_admin_deletion_function.sql');
    }

    protected function loadTriggers(): array|string
    {
        return file_get_contents(__DIR__ . '/../../Doctrine/fixtures/user_prevent_admin_deletion_trigger.sql');
    }

}