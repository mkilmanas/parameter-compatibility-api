<?php
declare(strict_types=1);


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameter_values")
 */
class ParameterValue
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @var Parameter
     *
     * @ORM\ManyToOne(targetEntity="Parameter", inversedBy="values")
     */
    private $parameter;

    /**
     * @var ?string
     *
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="ParameterValue", inversedBy="isProhibitedBy")
     * @ORM\JoinTable(name="prohibited_value_pairs")
     */
    private $prohibits;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="ParameterValue", mappedBy="prohibits")
     */
    private $isProhibitedBy;

    /**
     * ParameterValue constructor.
     * @param string|null $id
     * @param Parameter|null $parameter
     * @param string|null $value
     * @throws \Exception
     */
    public function __construct(Parameter $parameter, ?string $id = null, ?string $value = null)
    {
        $this->parameter = $parameter;
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->value = $value;
        $this->prohibits = new ArrayCollection();
        $this->isProhibitedBy = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Parameter
     */
    public function getParameter(): Parameter
    {
        return $this->parameter;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getProhibitedValues() : Collection
    {
        return $this->prohibits;
    }

    public function addProhibitedValue(ParameterValue $value)
    {
        if (!$this->prohibits->contains($value))     {
            $this->prohibits->add($value);
            $value->addProhibitedValue($this);
        }
    }

}