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
    
    const GROUPBY_YEAR          =   "Y";
    const GROUPBY_MONTH         =   "M";
    const GROUPBY_WEEK          =   "W";
    const GROUPBY_DAY           =   "D";
    const GROUPBY_HOUR          =   "H";
    const GROUPBY_MINUTE        =   "M";

    const PRESETS               =   array(
        
        "Y"     =>  [
            "DateStart" => "first day of january this year",          
            "DateEnd" => "", 
            "GroupBy" => "M"
            ],
        "M"     =>  [
            "DateStart" => "first day of this month",          
            "DateEnd" => "", 
            "GroupBy" => "D"
            ],
        "W"     =>  [
            "DateStart" => "last monday",          
            "DateEnd" => "next sunday", 
            "GroupBy" => "D"
            ],
        "D"     =>  [
            "DateStart" => "-1 day",          
            "DateEnd" => "", 
            "GroupBy" => "H"
            ],
        "PY"     =>  [
            "DateStart" => "first day of january last year",          
            "DateEnd" => "last day of december last year", 
            "GroupBy" => "M"
            ],
        "PM"     =>  [
            "DateStart" => "first day of last month",          
            "DateEnd" => "last day of last month", 
            "GroupBy" => "D"
            ],
        "PW"     =>  [
            "DateStart" => "last week last monday",          
            "DateEnd" => "last sunday", 
            "GroupBy" => "D"
            ],
        "PD"     =>  [
            "DateStart" => "-2 day",          
            "DateEnd" => "-1 day", 
            "GroupBy" => "H"
            ],
        
    );
    
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
            
    /**
     * Get Report Dates Options
     *
     * @return Report
     */
    public function getDatesArray()
    {
        
        //==============================================================================
        //  Check If Report is in Preset Dates Mode
        if ( !empty( $this->options["DatePreset"] ) && array_key_exists($this->options["DatePreset"], self::PRESETS) ) {
            $Presets        =   self::PRESETS;
            $DatesOptions   =   $Presets[ $this->options["DatePreset"] ];
        } else {
            $DatesOptions   =   $this->options;
        }
        //==============================================================================
        //  Prepare Dates
        $DateStart  =   new \DateTime($DatesOptions["DateStart"]);
        $DateStart->setTime(0, 0, 0);
        $DateEnd    =   new \DateTime($DatesOptions["DateEnd"]);
        $DateEnd->setTime(23, 59, 59);
        //==============================================================================
        //  Prepare Dates Array
        return  [
            "DateStart"     =>      $DateStart,
            "DateEnd"       =>      $DateEnd,
            "GroupBy"       =>      $DatesOptions["GroupBy"],
        ];


    }
    
    /**
     * Get Report Widget Parameters Array
     *
     * @return Report
     */
    public function getParametersArray()
    {
        
        //==============================================================================
        //  Check If Report is in Preset Dates Mode
        if ( !empty( $this->options["DatePreset"] ) && array_key_exists($this->options["DatePreset"], self::PRESETS) ) {
            $Presets        =   self::PRESETS;
            $DatesOptions   =   $Presets[ $this->options["DatePreset"] ];
        } else {
            $DatesOptions   =   $this->options;
        }
        
        //==============================================================================
        //  Prepare Dates Array
        return  [
            "DateStart"     =>      (new \DateTime($DatesOptions["DateStart"]))->format(SPL_T_DATETIMECAST),
            "DateEnd"       =>      (new \DateTime($DatesOptions["DateEnd"]))->format(SPL_T_DATETIMECAST),
            "GroupBy"       =>      $DatesOptions["GroupBy"],
        ];


    }
    
    
    
    //==============================================================================
    //      GETTERS & SETTERS 
    //==============================================================================       

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Report
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
     * @return Report
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
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}
