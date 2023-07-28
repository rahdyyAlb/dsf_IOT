<?php
namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserEventListener
{

	private $passwordEncoder;

	public function __construct(UserPasswordHasherInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if ($entity instanceof User) {
			$hashedPassword = $this->passwordEncoder->hashPassword($entity, $entity->getPassword());
			$entity->setPassword($hashedPassword);
		}
	}
}