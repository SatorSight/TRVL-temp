<?php

namespace Service\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PushText
 *
 * @ORM\Table(name="push_text")
 * @ORM\Entity
 */
class PushText
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", nullable=true)
     */
    private $label;
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", nullable=true)
     */
    private $value;
    /**
     * @var string
     *
     * @ORM\Column(name="add_text", type="string", nullable=true)
     */
    private $addText;
    /**
     * @var string
     *
     * @ORM\Column(name="add_value", type="string", nullable=true)
     */
    private $addValue;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return PushText
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return PushText
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set addValue
     *
     * @param string $addValue
     *
     * @return PushText
     */
    public function setAddValue($addValue)
    {
        $this->addValue = $addValue;

        return $this;
    }

    /**
     * Get addValue
     *
     * @return string
     */
    public function getAddValue()
    {
        return $this->addValue;
    }

    /**
     * Set addText
     *
     * @param string $addText
     *
     * @return PushText
     */
    public function setAddText($addText)
    {
        $this->addText = $addText;

        return $this;
    }

    /**
     * Get addText
     *
     * @return string
     */
    public function getAddText()
    {
        return $this->addText;
    }
}
