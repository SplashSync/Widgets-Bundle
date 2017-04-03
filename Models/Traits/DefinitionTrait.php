<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Traits;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @abstract Widget Definition Trait 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait DefinitionTrait
{
    
    /**
     * @abstract    Widget Human Readable Name
     * @var string
     */
    protected $name;

    /**
     * @abstract    Widget Human Readable Description
     * @var string
     */
    protected $description;    
    
    /**
     * @abstract    Widget Header Title
     * @var string
     */
    protected $title;
    
    /**
     * @abstract    Widget Header Sub-Title
     * @var string
     */
    protected $subtitle;    
    
    /**
     * @abstract    Widget Header Icon
     * @var string
     */
    protected $icon;  
    
    //====================================================================//
    // *******************************************************************//
    //  Widget Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Object
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
     * Set description
     *
     * @param string $description
     *
     * @return Object
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }    
    
    
    /**
     * Set Title
     * 
     * @param   $title
     * @return  Widget
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * Get Title
     * 
     * @return  String
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set SubTitle
     * 
     * @param   $subtitle
     * @return  Widget
     */
    public function setSubTitle($subtitle)
    {
        $this->subtitle = $subtitle;
        
        return $this;
    }
    
    /**
     * Get SubTitle
     * 
     * @return  String
     */
    public function getSubTitle()
    {
        return $this->subtitle;
    }    
    
    /**
     * Set Icon
     * 
     * @param   $text
     * @return  Widget
     */
    public function setIcon($text)
    {
        $this->icon = $text;
        
        return $this;
    }
    
    /**
     * Get Icon
     * 
     * @return  String
     */
    public function getIcon()
    {
        return $this->icon;
    }   
}
