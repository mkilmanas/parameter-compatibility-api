<?php
declare(strict_types=1);


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameters")
 */
class Parameter
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ParameterValue", mappedBy="parameter")
     */
    private $values;

    /**
     * Parameter constructor.
     * @param string|null $id
     * @param string|null $name
     * @param Collection|null $values
     * @throws \Exception
     */
    public function __construct(?string $id = null, ?string $name = null, ?Collection $values = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name ?? "";
        $this->values = $values ?? new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(ParameterValue $value) : Parameter
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
        }
        return $this;
    }

    public function removeValue(ParameterValue $value) : Parameter
    {
        if ($this->values->contains($value)) {
            $this->values->removeElement($value);
        }
        return $this;
    }
}