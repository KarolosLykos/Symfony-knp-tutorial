<?php

namespace AppBundle\Doctrine;


use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


class HashPasswordListener implements EventSubscriber
{

    private $passwordEncoder;

    // Dependency injection From userpasswordencoder use the encodepassword method
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }


        $this->encodePassword($entity);

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }


        $this->encodePassword($entity);
        // necessary to force the update to see the change .
        $em = $args->getEntityManager();
        // get metada , get the entity .
        $meta = $em->getClassMetadata(get_class($entity));
        // get unit of work and recompute the changes .
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);

    }
    public function getSubscribedEvents()
    {
      return ['prePersist','preUpdate'];
    }

    /**
     * @param User $entity
     */
    public function encodePassword(User $entity)
    {
        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }

}
