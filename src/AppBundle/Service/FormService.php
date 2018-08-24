<?php

namespace AppBundle\Service;

use AppBundle\Entity\Form;
use Doctrine\ORM\EntityManager;
use Ds\Component\Entity\Service\EntityService;

/**
 * Class FormService
 */
class FormService extends EntityService
{
    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $manager
     * @param string $entity
     */
    public function __construct(EntityManager $manager, $entity = Form::class)
    {
        parent::__construct($manager, $entity);
    }
}
