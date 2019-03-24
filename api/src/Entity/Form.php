<?php

namespace App\Entity;

use Ds\Component\Locale\Model\Type\Localizable;
use Ds\Component\Model\Attribute\Accessor;
use Ds\Component\Model\Type\Deletable;
use Ds\Component\Model\Type\Identifiable;
use Ds\Component\Model\Type\Ownable;
use Ds\Component\Model\Type\Uuidentifiable;
use Ds\Component\Model\Type\Versionable;
use Ds\Component\Tenant\Model\Attribute\Accessor as TenantAccessor;
use Ds\Component\Tenant\Model\Type\Tenantable;
use Ds\Component\Translation\Model\Type\Translatable;
use Ds\Component\Translation\Model\Attribute\Accessor as TranslationAccessor;
use Knp\DoctrineBehaviors\Model as Behavior;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Validator\Constraints\Form as FormAssert;
use Doctrine\ORM\Mapping as ORM;
use Ds\Component\Locale\Model\Annotation\Locale;
use Ds\Component\Translation\Model\Annotation\Translate;
use Symfony\Bridge\Doctrine\Validator\Constraints as ORMAssert;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Form
 *
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={
 *              "groups"={"form_output"}
 *          },
 *          "denormalization_context"={
 *              "groups"={"form_input"}
 *          },
 *          "filters"={
 *              "app.form.search",
 *              "app.form.search_translation",
 *              "app.form.date",
 *              "app.form.order"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FormRepository")
 * @ORM\Table(name="app_form")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @FormAssert\Config\Valid
 */
class Form implements Identifiable, Uuidentifiable, Ownable, Translatable, Localizable, Deletable, Versionable, Tenantable
{
    use Behavior\Translatable\Translatable;
    use Behavior\Timestampable\Timestampable;
    use Behavior\SoftDeletable\SoftDeletable;

    use Accessor\Id;
    use Accessor\Uuid;
    use Accessor\Owner;
    use Accessor\OwnerUuid;
    use Accessor\Type;
    use Accessor\Config;
    use TranslationAccessor\Title;
    use TranslationAccessor\Description;
    use Accessor\Data;
    use Accessor\Deleted;
    use Accessor\Version;
    use TenantAccessor\Tenant;

    /**
     * @const string
     */
    const TYPE_FORMIO = 'formio';

    /**
     * @var integer
     * @ApiProperty(identifier=false, writable=false)
     * @Serializer\Groups({"form_output"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ApiProperty(identifier=true, writable=false)
     * @Serializer\Groups({"form_output"})
     * @ORM\Column(name="uuid", type="guid", unique=true)
     * @Assert\Uuid
     */
    private $uuid;

    /**
     * @var \DateTime
     * @ApiProperty(writable=false)
     * @Serializer\Groups({"form_output"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ApiProperty(writable=false)
     * @Serializer\Groups({"form_output"})
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     * @ApiProperty(writable=false)
     * @Serializer\Groups({"form_output"})
     */
    protected $deletedAt;

    /**
     * @var string
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="`owner`", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=255)
     */
    private $owner;

    /**
     * @var string
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="owner_uuid", type="guid", nullable=true)
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private $ownerUuid;

    /**
     * @var string
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(min=1, max=255)
     */
    private $type;

    /**
     * @var array
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="config", type="json_array")
     * @Assert\Type("array")
     */
    private $config;

    /**
     * @var array
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @Assert\Type("array")
     * @Assert\NotBlank
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Length(min=1)
     * })
     * @Locale
     * @Translate
     */
    private $title;

    /**
     * @var array
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @Assert\Type("array")
     * @Assert\NotBlank
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Length(min=1)
     * })
     * @Locale
     * @Translate
     */
    private $description;

    /**
     * @var array
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="data", type="json_array")
     * @Assert\Type("array")
     */
    private $data;

    /**
     * @var integer
     * @ApiProperty
     * @Serializer\Groups({"form_output", "form_input"})
     * @ORM\Column(name="version", type="integer")
     * @ORM\Version
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    private $version;

    /**
     * @var string
     * @ApiProperty(writable=false)
     * @Serializer\Groups({"form_output"})
     * @ORM\Column(name="tenant", type="guid")
     * @Assert\Uuid
     */
    private $tenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = [];
        $this->title = [];
        $this->description = [];
        $this->data = [];
    }
}
