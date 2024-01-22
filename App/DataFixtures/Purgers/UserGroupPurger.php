<?php declare( strict_types=1 );

namespace App\DataFixtures\Purgers;

use App\Abstraction\Classes\AbstractPurger;

/**
 * Class UserGroupPurger
 *
 * @package App\DataFixtures\Purgers
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class UserGroupPurger extends AbstractPurger
{

    protected function getQueries(): array
    {
        return [
            /** @lang PostgreSQL */
            'DROP FUNCTION IF EXISTS raise_user_groups_table_exception() CASCADE',
            /** @lang PostgreSQL */
            'TRUNCATE TABLE user_groups CASCADE'
        ];
    }

}
