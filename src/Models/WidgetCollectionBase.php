<?php

namespace Splash\Widgets\Models;

use Doctrine\ORM\Mapping                        as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Splash\Widgets\Models\Traits\LifecycleTrait;
use Splash\Widgets\Models\Traits\OptionsTrait;
use Splash\Widgets\Models\Traits\CollectionTrait;

/**
 * @abstract    Widgets Collection Base Object
 */
class WidgetCollectionBase
{

    use CollectionTrait;
    use LifecycleTrait;
    
    //==============================================================================
    //      Definition           
    //==============================================================================
        
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=TRUE)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="preset", type="string", length=255, nullable=TRUE)
     */
    protected $preset = "M";
    
    //==============================================================================
    //      CONSTRUCTOR  
    //==============================================================================   
    
    public function __construct()
    {
        $this->widgets    =   new ArrayCollection();
    }

    //==============================================================================
    //      DATA OPERATIONS  
    //==============================================================================   

    public function __toString()
    {
        return $this->getName();         
    }
    
    //==============================================================================
    //      GETTERS & SETTERS 
    //==============================================================================       

    /**
     * Set name
     *
     * @param string $name
     *
     * @return WidgetCollectionBase
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return WidgetCollectionBase
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Preset
     *
     * @param string $preset
     *
     * @return WidgetCollectionBase
     */
    public function setPreset($preset)
    {
        $this->preset   = $preset;

        return $this;
    }

    /**
     * Get Preset
     *
     * @return string
     */
    public function getPreset()
    {
        return $this->preset;
    }



}
