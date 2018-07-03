<?php

namespace AppBundle\Fixture;

use AppBundle\Entity\Form;
use Doctrine\Common\Persistence\ObjectManager;
use Ds\Component\Database\Fixture\ResourceFixture;
use Ds\Component\Formio\Exception\ValidationException;
use Ds\Component\Formio\Model\User as FormioUser;

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
        $env = $this->container->get('kernel')->getEnvironment();
        $configService = $this->container->get('ds_config.service.config');
        $service = $this->container->get('ds_api.api')->get('formio.authentication');
        $user = new FormioUser;
        $user
            ->setEmail($configService->get('ds_api.user.username'))
            ->setPassword($configService->get('ds_api.user.password'));
        $token = $service->login($user);
        $service = $this->container->get('ds_api.api')->get('formio.form');
        $service->setHeader('x-jwt-token', $token);
        $forms = $service->getList();

        foreach ($forms as $form) {
            if (in_array($form->getName(), ['user', 'admin', 'userLogin', 'userRegister'])) {
                // Skip base formio forms.
                continue;
            }

            try {
                $service->delete($form->getPath());
            } catch (ValidationException $exception) {
                // @todo this is so first time fixtures dont cause an error, handle "Invalid alias" better
            }
        }

        $api = $this->container->get('ds_api.api')->get('formio.role');
        $api->setHeader('x-jwt-token', $token);
        $roles = $api->getList();
        $objects = $this->parse($this->getResource());

        foreach ($objects as $object) {
            $form = new Form;
            $form
                ->setUuid($object->uuid)
                ->setOwner($object->owner)
                ->setOwnerUuid($object->owner_uuid)
                ->setType($object->type)
                ->setTenant($object->tenant);

            switch ($object->type) {
                case Form::TYPE_FORMIO:
                    $config = $object->config;

                    if (property_exists($config, 'components')) {
                        if (is_string($config->components)) {
                            $config->components = json_decode(file_get_contents(dirname(str_replace('{env}', $env, $this->getResource())).'/'.$config->components));
                        }
                    }

                    if (property_exists($config, 'submission_access')) {
                        if (is_string($config->submission_access)) {
                            $config->submission_access = json_decode(file_get_contents(dirname(str_replace('{env}', $env, $this->getResource())).'/'.$config->submission_access));
                            $submissionAccess = [];

                            foreach ($config->submission_access as $access) {
                                foreach ($access->roles as $key => $value) {
                                    foreach ($roles as $role) {
                                        if ($role->getMachineName() === $value) {
                                            $access->roles[$key] = $role->getId();
                                            break;
                                        }
                                    }
                                }

                                $submissionAccess[] = $access;
                            }

                            $config->submission_access = $submissionAccess;
                        }
                    }

                    $form->setConfig($config);
                    break;
            }

            $manager->persist($form);
            $manager->flush();
        }
    }
}
