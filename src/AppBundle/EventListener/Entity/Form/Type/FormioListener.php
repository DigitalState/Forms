<?php

namespace AppBundle\EventListener\Entity\Form\Type;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException as ApiPlatformValidationException;
use AppBundle\Entity\Form;
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
     * @var \Ds\Component\Api\Api\Api
     */
    protected $api;

    /**
     * @var \Ds\Component\Config\Service\ConfigService
     */
    protected $configService;

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
     * Persist forms of type formio to formio service
     *
     * @param \AppBundle\Entity\Form $form
     */
    public function postPersist(Form $form)
    {
        // Circular reference error workaround
        // @todo Look into fixing this
        $this->api = $this->container->get('ds_api.api');
        $this->configService = $this->container->get('ds_config.service.config');
        //

        if (Form::TYPE_FORMIO !== $form->getType()) {
            return;
        }

        $api = $this->api->get('formio.authentication');
        $user = new FormioUser;
        $user
            ->setEmail($this->configService->get('ds_api.user.username'))
            ->setPassword($this->configService->get('ds_api.user.password'));
        $token = $api->login($user);
        $api = $this->api->get('formio.form');
        $api->setHeader('x-jwt-token', $token);
        $config = (object) $form->getConfig();
        $form = new FormioForm;
        $form
            ->setTitle($config->title)
            ->setDisplay($config->display)
            ->setType($config->type)
            ->setName($config->name)
            ->setPath($config->path)
            ->setComponents($config->components);

        try {
            $api->create($form);
        } catch (ValidationException $exception) {
            $violations = [];

            foreach ($exception->getErrors() as $error) {
                $message = $error->message;
                $path = 'config.'.$error->path;
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
