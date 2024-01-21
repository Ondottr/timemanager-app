<?php declare( strict_types=1 );

namespace App\Repository;

use App\Entity\Project;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHP_SF\System\Classes\Abstracts\AbstractEntityRepository;

/**
 * @method Project|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Project|null findOneBy( array $criteria, array $orderBy = null )
 * @method array|Project[] findAll()
 * @method array|Project[] findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
final class ProjectRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct( em(), new ClassMetadata( Project::class ) );
    }
}
