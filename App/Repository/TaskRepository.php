<?php declare( strict_types=1 );

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHP_SF\System\Classes\Abstracts\AbstractEntityRepository;

/**
 * @method Task|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Task|null findOneBy( array $criteria, array $orderBy = null )
 * @method array|Task[] findAll()
 * @method array|Task[] findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
final class TaskRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct( em(), new ClassMetadata( Task::class ) );
    }
}
