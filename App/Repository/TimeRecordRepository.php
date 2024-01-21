<?php declare( strict_types=1 );

namespace App\Repository;

use App\Entity\TimeRecord;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHP_SF\System\Classes\Abstracts\AbstractEntityRepository;

/**
 * @method TimeRecord|null find( $id, $lockMode = null, $lockVersion = null )
 * @method TimeRecord|null findOneBy( array $criteria, array $orderBy = null )
 * @method array|TimeRecord[] findAll()
 * @method array|TimeRecord[] findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
final class TimeRecordRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct( em(), new ClassMetadata( TimeRecord::class ) );
    }
}
