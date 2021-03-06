<?php

namespace App\Service;

use App\Entity\Form;
use Doctrine\ORM\EntityManagerInterface;
use Ds\Component\Entity\Service\EntityService;

/**
 * Class FormService
 */
final class FormService extends EntityService
{
    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param string $entity
     */
    public function __construct(EntityManagerInterface $manager, string $entity = Form::class)
    {
        parent::__construct($manager, $entity);
    }
}
