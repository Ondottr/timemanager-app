<?php declare( strict_types=1 );

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHP_SF\System\Classes\Abstracts\AbstractEntityRepository;

/**
 * @method User|null find( $id, $lockMode = null, $lockVersion = null )
 * @method User|null findOneBy( array $criteria, array $orderBy = null )
 * @method array|User[] findAll()
 * @method array|User[] findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
final class UserRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct( em(), new ClassMetadata( User::class ) );
    }
}
