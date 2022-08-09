<?php

namespace Shopsys\FrameworkBundle\Model\Slider;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Component\Grid\Ordering\OrderableEntityInterface;

/**
 * SliderItem
 *
 * @ORM\Table(name="slider_items")
 * @ORM\Entity
 */
class SliderItem implements OrderableEntityInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $link;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $domainId;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $hidden;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Slider\SliderItemData $sliderItemData
     */
    public function __construct(SliderItemData $sliderItemData)
    {
        $this->setData($sliderItemData);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Slider\SliderItemData $sliderItemData
     */
    public function edit(SliderItemData $sliderItemData)
    {
        $this->setData($sliderItemData);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Slider\SliderItemData $sliderItemData
     */
    protected function setData(SliderItemData $sliderItemData): void
    {
        $this->domainId = $sliderItemData->domainId;
        $this->name = $sliderItemData->name;
        $this->link = $sliderItemData->link;
        $this->hidden = $sliderItemData->hidden;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return int
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * @return int|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }
}
