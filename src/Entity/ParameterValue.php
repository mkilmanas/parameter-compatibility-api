<?php
declare(strict_types=1);


namespace App\Entity;

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

}