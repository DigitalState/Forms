<?php

namespace AppBundle\Entity\Attribute\Accessor;

use AppBundle\Entity\Form as FormEntity;

/**
 * Trait Form
 */
trait Form
{
    /**
     * Set form
     *
     * @param \AppBundle\Entity\Form $form
     * @return object
     */
    public function setForm(FormEntity $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return \AppBundle\Entity\Form
     */
    public function getForm()
    {
        return $this->form;
    }
}
