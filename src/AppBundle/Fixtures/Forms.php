<?php

namespace AppBundle\Fixtures;

use AppBundle\Fixture\FormFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class Forms
 */
class Forms extends FormFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * {@inheritdoc}
     */
    protected function getResource()
    {
        return '/srv/api-platform/src/AppBundle/Resources/fixtures/{env}/forms.yml';
    }
}
