<?php

namespace AppBundle\Fixture;

use AppBundle\Entity\Form;
use Doctrine\Common\Persistence\ObjectManager;
use Ds\Component\Database\Fixture\ResourceFixture;

/**
 * Class FormFixture
 */
abstract class FormFixture extends ResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $objects = $this->parse($this->getResource());

        foreach ($objects as $object) {
            $form = new Form;
            $form
                ->setUuid($object->uuid)
                ->setOwner($object->owner)
                ->setOwnerUuid($object->owner_uuid)
                ->setType($object->type)
                ->setConfig((array) $object->config)
                ->setTenant($object->tenant);
            $manager->persist($form);
            $manager->flush();
        }
    }
}
