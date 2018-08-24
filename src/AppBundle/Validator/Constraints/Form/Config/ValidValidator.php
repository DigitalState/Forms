<?php

namespace AppBundle\Validator\Constraints\Form\Config;

use AppBundle\Entity\Form;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ValidValidator
 */
class ValidValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($form, Constraint $constraint)
    {
        $config = $form->getConfig();

        switch ($form->getType()) {
            case Form::TYPE_FORMIO:
                $this->validateFormio($config, $constraint);
                break;
        }
    }

    /**
     * Validate formio form config
     *
     * @param array $config
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    protected function validateFormio(array $config, Constraint $constraint)
    {
        foreach (['title', 'display', 'type', 'name', 'path', 'components', 'submissionAccess'] as $attribute) {
            if (!array_key_exists($attribute, $config)) {
                $this->context
                    ->buildViolation($constraint->missing)
                    ->setParameter('{{ attribute }}', '"'.$attribute.'"')
                    ->atPath('config.'.$attribute)
                    ->addViolation();
            }
        }
    }
}
