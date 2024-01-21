<?php declare( strict_types=1 );

namespace App\Repository;

use App\Entity\Client;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHP_SF\System\Classes\Abstracts\AbstractEntityRepository;

/**
 * @method Client|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Client|null findOneBy( array $criteria, array $orderBy = null )
 * @method array|Client[] findAll()
 * @method array|Client[] findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
final class ClientRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct( em(), new ClassMetadata( Client::class ) );
    }
}
