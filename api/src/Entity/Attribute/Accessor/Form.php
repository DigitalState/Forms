<?php

namespace App\Entity\Attribute\Accessor;

use App\Entity\Form as FormEntity;

/**
 * Trait Form
 */
trait Form
{
    /**
     * Set form
     *
     * @param \App\Entity\Form $form
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
     * @return \App\Entity\Form
     */
    public function getForm()
    {
        return $this->form;
    }
}
