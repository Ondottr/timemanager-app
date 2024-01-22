<?php declare( strict_types=1 );

namespace App\DataFixtures\Purgers;

use App\Abstraction\Classes\AbstractPurger;

/**
 * Class UsersPurger
 *
 * @package App\DataFixtures\Purgers
 * @author  Dmytro Dyvulskyi <dmytro.dyvulskyi@nations-original.com>
 */
final class UsersPurger extends AbstractPurger
{

    protected function getQueries(): array
    {
        return [
            /** @lang PostgreSQL */
            'DROP FUNCTION IF EXISTS prevent_admin_deletion() CASCADE',
            /** @lang PostgreSQL */
            'TRUNCATE TABLE users CASCADE'
        ];
    }

}
