<?php

namespace AppBundle\EventListener\Entity\Form\Type;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException as ApiPlatformValidationException;
use AppBundle\Entity\Form;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Ds\Component\Formio\Exception\ValidationException;
use Ds\Component\Formio\Model\Form as FormioForm;
use Ds\Component\Formio\Model\User as FormioUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class FormioListener
 */
class FormioListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Persist form entity on formio
     *
     * @param \AppBundle\Entity\Form $form
     */
    public function postPersist(Form $form)
    {
        if (Form::TYPE_FORMIO !== $form->getType()) {
            return;
        }

        $this->synchronize($form, 'persist');
    }

    /**
     * Update form entity on formio
     *
     * @param \AppBundle\Entity\Form $form
     */
    public function postUpdate(Form $form)
    {
        if (Form::TYPE_FORMIO !== $form->getType()) {
            return;
        }

        $this->synchronize($form, 'update');
    }

    /**
     * Remove form entity from formio
     *
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $event
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        foreach ($event->getEntityManager()->getUnitOfWork()->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Form) {
                continue;
            }

            if (Form::TYPE_FORMIO !== $entity->getType()) {
                return;
            }

            $this->synchronize($entity, 'remove');
        }
    }

    /**
     * Synchronize form entity with formio form
     *
     * @param \AppBundle\Entity\Form $form
     * @param string $event
     */
    protected function synchronize(Form $form, $event)
    {
        $api = $this->container->get('ds_api.api');
        $configService = $this->container->get('ds_config.service.config');
        $service = $api->get('formio.authentication');
        $user = new FormioUser;
        $user
            ->setEmail($configService->get('ds_api.user.username'))
            ->setPassword($configService->get('ds_api.user.password'));
        $token = $service->login($user);
        $service = $api->get('formio.form');
        $service->setHeader('x-jwt-token', $token);
        $tenant = $form->getTenant();
        $config = (object) $form->getConfig();
        $form = new FormioForm;
        $form
            ->setTitle($config->title)
            ->setDisplay($config->display)
            ->setType($config->type)
            ->setName($tenant.'-'.$config->name)
            ->setPath($tenant.'-'.$config->path)
            ->setComponents($config->components)
            ->setSubmissionAccess($config->submissionAccess);

        try {
            switch ($event) {
                case 'persist':
                    $service->create($form);
                    break;

                case 'update':
                    $service->update($form);
                    break;

                case 'remove':
                    $service->delete($form->getPath());
                    break;
            }
        } catch (ValidationException $exception) {
            $violations = [];

            foreach ($exception->getErrors() as $error) {
                $message = $error->message;
                $path = 'config.' . $error->path;
                $template = '%s: %s';
                $parameters = [$path, $message];
                $root = '';
                $value = null;
                $violations[] = new ConstraintViolation($message, $template, $parameters, $root, $path, $value);
            }

            $list = new ConstraintViolationList($violations);
            throw new ApiPlatformValidationException($list, 'An error occurred', 0, $exception);
        }
    }
}
