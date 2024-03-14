<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct( UserPasswordHasherInterface $passwordEncoder ) {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        return [
            FormField::addPanel( 'Datos del Usuario' )->setIcon( 'fa fa-user' ),
            TextField::new('fullname','Nombre Completo'),
            TextField::new('email','Correo Electrónico'),
            TextField::new('username','Nombre de Usuario'),
            ChoiceField::new( 'roles' )
                ->setChoices( array_combine( $roles, $roles ) )
                ->allowMultipleChoices()
                ->renderAsBadges(),
            Field::new('notify','Notificar'),
            FormField::addPanel( 'Contraseña' )
                ->onlyWhenCreating()
                ->setIcon( 'fa fa-key' ),
            FormField::addPanel( 'Modificar Contraseña' )
                ->onlyWhenUpdating()
                ->setIcon( 'fa fa-key' ),
            // TextField::new('password', 'Contraseña')
            //     ->hideOnIndex()
            //     ,

            Field::new( 'password', 'Nueva Contraseña' )->onlyWhenCreating()->setRequired( true )
                ->setFormType( RepeatedType::class )
                ->setFormTypeOptions( [
                    'type'            => PasswordType::class,
                    'first_options'   => [ 'label' => 'Nueva Contraseña' ],
                    'second_options'  => [ 'label' => 'Repetir Contraseña' ],
                    'error_bubbling'  => true,
                    'invalid_message' => 'Las contraseñnas no coinciden',
                ] ),
            Field::new( 'password', 'Nueva Contraseña' )->onlyWhenUpdating()->setRequired( false )
                ->setFormType( RepeatedType::class )
                ->setFormTypeOptions( [
                    'type'            => PasswordType::class,
                    'first_options'   => [ 'label' => 'Nueva Contraseña' ],
                    'second_options'  => [ 'label' => 'Repetir Contraseña' ],
                    'error_bubbling'  => true,
                    'invalid_message' => 'Las contraseñnas no coinciden',
                ] ),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Usuario')
            ->setEntityLabelInPlural('Usuarios')
            ->setSearchFields(['fullname', 'username', 'email'])
            ->setDefaultSort(['fullname' => 'ASC'])
        ;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ( $entityInstance->getPassword() !== null ) {
            $entityInstance->setPassword( $this->passwordEncoder->hashPassword( $entityInstance, $entityInstance->getPassword() ) );
            parent::updateEntity($entityManager, $entityInstance);
        }
    }

}
