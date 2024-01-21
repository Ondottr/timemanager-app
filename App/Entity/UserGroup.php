<?php /** @noinspection MethodShouldBeFinalInspection */
declare( strict_types=1 );

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity( repositoryClass: UserGroupRepository::class, readOnly: true )]
#[ORM\Table( name: 'user_groups' )]
#[ORM\Cache( usage: 'READ_ONLY' )]
class UserGroup extends AbstractEntity
{

    #[TranslatablePropertyName( 'Name' )]
    #[ORM\Column( type: 'string', unique: true )]
    #[Groups( groups: [ 'read' ] )]
    protected string $name;


    public function getName(): string
    {
        return $this->name;
    }

    public function getLifecycleCallbacks(): array
    {
        return [];
    }

}
