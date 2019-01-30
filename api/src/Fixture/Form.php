<?php

namespace App\Fixture;

use App\Entity\Form as FormEntity;
use Doctrine\Common\Persistence\ObjectManager;
use Ds\Component\Api\Api\Api;
use Ds\Component\Config\Service\ConfigService;
use Ds\Component\Database\Fixture\Yaml;
use Ds\Component\Formio\Exception\ValidationException;
use Ds\Component\Formio\Model\User as FormioUser;

/**
 * Trait Form
 */
trait Form
{
    use Yaml;

    /**
     * @var string
     */
    private $path;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $fixtures = array_key_exists('FIXTURES', $_ENV) ? $_ENV['FIXTURES'] : 'dev';
        $configService = $this->container->get(ConfigService::class);

        $service = $this->container->get(Api::class)->get('formio.authentication');
        $user = new FormioUser;
        $user
            ->setEmail($configService->get('ds_api.user.username'))
            ->setPassword($configService->get('ds_api.user.password'));
        $token = $service->login($user);

        $service = $this->container->get(Api::class)->get('formio.form');
        $service->setHeader('x-jwt-token', $token);
        $forms = $service->getList();

        foreach ($forms as $form) {
            if (in_array($form->getName(), ['user', 'admin', 'userLogin', 'userRegister'])) {
                // Skip base formio forms
                continue;
            }

            try {
                $service->delete($form->getPath());
            } catch (ValidationException $exception) {
                // @todo this is so first time fixtures dont cause an error, handle "Invalid alias" better
            }
        }

        $api = $this->container->get(Api::class)->get('formio.role');
        $api->setHeader('x-jwt-token', $token);
        $roles = $api->getList();
        $objects = $this->parse($this->path);

        foreach ($objects as $object) {
            $form = new FormEntity;
            $form
                ->setUuid($object->uuid)
                ->setOwner($object->owner)
                ->setOwnerUuid($object->owner_uuid)
                ->setTitle((array) $object->title)
                ->setDescription((array) $object->description)
                ->setType($object->type)
                ->setTenant($object->tenant);

            switch ($object->type) {
                case FormEntity::TYPE_FORMIO:
                    $config = $object->config;

                    if (property_exists($config, 'components')) {
                        if (is_string($config->components)) {
                            $config->components = json_decode(file_get_contents(dirname(str_replace('{fixtures}', $fixtures, $this->path)).'/'.$config->components));
                        }
                    }

                    if (property_exists($config, 'submissionAccess')) {
                        if (is_string($config->submissionAccess)) {
                            $config->submissionAccess = json_decode(file_get_contents(dirname(str_replace('{fixtures}', $fixtures, $this->path)).'/'.$config->submissionAccess));
                            $submissionAccess = [];

                            foreach ($config->submissionAccess as $access) {
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

                            $config->submissionAccess = $submissionAccess;
                        }
                    }

                    $form->setConfig((array) $config);
                    break;
            }

            $manager->persist($form);
        }

        $manager->flush();
    }
}
