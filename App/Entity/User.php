<?php /** @noinspection MethodShouldBeFinalInspection */
declare( strict_types=1 );

namespace App\Entity;

use App\DoctrineLifecycleCallbacks\UserPreRemoveCallback;
use App\Enums\UserGroupEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping as ORM;
use PHP_SF\Framework\Http\Middleware\auth;
use PHP_SF\System\Attributes\Validator\Constraints as Validate;
use PHP_SF\System\Attributes\Validator\TranslatablePropertyName;
use PHP_SF\System\Classes\Abstracts\AbstractEntity;
use PHP_SF\System\Core\DateTime;
use PHP_SF\System\Interface\UserInterface;
use PHP_SF\System\Traits\ModelProperty\ModelPropertyCreatedAtTrait;

use function is_int;

#[ORM\Entity( repositoryClass: UserRepository::class )]
#[ORM\Table( name: 'users' )]
#[ORM\Cache( usage: 'READ_WRITE' )]
#[ORM\Index( columns: [ 'email' ] )]
class User extends AbstractEntity implements UserInterface
{
    use ModelPropertyCreatedAtTrait;


    # region Basic properties
    #[Validate\Email]
    #[Validate\Length( min: 6, max: 50 )]
    #[TranslatablePropertyName( 'E-mail' )]
    #[ORM\Column( type: 'string', unique: true )]
    protected string $email;

    #[TranslatablePropertyName( 'Password' )]
    #[ORM\Column( type: 'string' )]
    protected string $password;
    # endregion


    # region ManyToOne properties
    #[TranslatablePropertyName( 'User Group' )]
    #[ORM\ManyToOne( targetEntity: UserGroup::class )]
    #[ORM\JoinColumn( name: 'user_group_id', nullable: false, columnDefinition: 'INT NOT NULL DEFAULT 6' )]
    protected int|UserGroup $userGroup;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Client::class, orphanRemoval: true)]
    private Collection $clients;

    # endregion


    public function __construct()
    {
        $this->setCreatedAt( new DateTime );
        $this->clients = new ArrayCollection();
    }


    # region Entity related methods
    public static function isAdmin( int|null $id = null ): bool
    {
        return self::userGroupCheck( UserGroupEnum::ADMINISTRATOR, $id );
    }

    private static function userGroupCheck( UserGroupEnum $userGroup, int|null $id = null ): bool
    {
        if ( $id !== null ) {
            if ( auth::isAuthenticated() && user()->getId() === $id )
                return user()->getUserGroup()->getId() === $userGroup->getId();

            if ( ( $user = self::find( $id ) ) instanceof self )
                return $user->getUserGroup()->getId() === $userGroup->getId();
        }

        return auth::isAuthenticated() && user()->getUserGroup()->getId() === $userGroup->getId();
    }
    # endregion


    # region Getters and Setters for Basic properties
    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function setEmail( string|null $email ): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function setPassword( #[\SensitiveParameter] string|null $password ): self
    {
        $this->password = password_hash( $password, PASSWORD_ARGON2I );

        return $this;
    }
    # endregion


    # region Getters and Setters for ManyToOne properties
    public function getUserGroup(): UserGroup
    {
        if ( is_int( $this->userGroup ) )
            $this->setUserGroup( UserGroup::find( $this->userGroup ) );

        return $this->userGroup;
    }

    public function setUserGroup( int|UserGroup $userGroup ): self
    {
        $this->userGroup = $userGroup;

        return $this;
    }
    # endregion


    public function getLifecycleCallbacks(): array
    {
        return [
            Events::postRemove => UserPreRemoveCallback::class,
        ];
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
            }
        }

        return $this;
    }

}
