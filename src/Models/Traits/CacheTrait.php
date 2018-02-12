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

use Doctrine\ORM\Mapping as ORM;

use Splash\Widgets\Entity\Widget;

/**
 * @abstract Widget Access Trait - Defin access to a Widget 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
trait CacheTrait
{
    
    /**
     * @abstract    Widget Human Readable Name
     * @var string
     * @ORM\Column(name="name", type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * @abstract    Widget Human Readable Description
     * @var string
     * @ORM\Column(name="description", type="string", length=250, nullable=true)
     */
    protected $description;    
    
    /**
     * @abstract    Widget Header Title
     * @var string
     * @ORM\Column(name="title", type="string", length=250, nullable=true)
     */
    protected $title;
    
    /**
     * @abstract    Widget Header Sub-Title
     * @var string
     * @ORM\Column(name="subtitle", type="string", length=250, nullable=true)
     */
    protected $subtitle;    
    
    /**
     * @abstract    Widget Header Icon
     * @var string
     * @ORM\Column(name="icon", type="string", length=250, nullable=true)
     */
    protected $icon;  
    
    /**
     * @abstract    Widget Source/Origin
     * @var string
     * @ORM\Column(name="origin", type="string", length=250, nullable=true)
     */
    protected $origin;
    
    //==============================================================================
    //      Variables  
    //==============================================================================
    
    /**
     * @abstract    Widget Discriminator (Identify if refresh is needed)
     * @var         string
     * @ORM\Column(name="discriminator", type="string", length=250, nullable=true)
     */
    protected $discriminator   =   Null;

    /**
     * @abstract    Widget Cache Contents
     * @var         string
     * @ORM\Column(name="contents", type="text")
     */
    protected $contents   =   Null;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="refreshAt", type="datetime")
     */
    protected $refreshAt       =    Null;
    
    /**
     * @var \DateTime
     * @ORM\Column(name="expireAt", type="datetime")
     */
    protected $expireAt       =    Null;
       
    //==============================================================================
    //      Data Operations  
    //==============================================================================
    
    /**
     * @abstract    Build Widget Disctriminator
     * 
     * @param array     $Options        Widget Options Array
     * @param array     $Parameters     Widget Parameters Array
     * 
     * @return  self
     */
    public static function buildDiscriminator(array $Options, array $Parameters)
    {
        return md5(serialize($Options) . serialize($Parameters));
    }   
        
    //==============================================================================
    //      Getters & Setters  
    //==============================================================================
    
    /**
     * @abstract    Set Widget Disctriminator
     * 
     * @param string $discriminator 
     * 
     * @return  self
     */
    public function setDiscriminator($discriminator)
    {
        $this->discriminator = $discriminator;
        return $this;
    }   
    
    /**
     * @abstract    Get Widget Disctriminator
     * 
     * @return  string
     */
    public function getDiscriminator()
    {
        return $this->discriminator;
    }   
    
    /**
     * @abstract    Set Widget Cached Contents
     * 
     * @param string $contents 
     * 
     * @return  self
     */
    public function setContents($contents)
    {
        $this->contents = base64_encode($contents);
        return $this;
    }   
    
    /**
     * @abstract    Get Widget Cached Contents
     * 
     * @return  string
     */
    public function getContents()
    {
        return base64_decode($this->contents);
    }   
    
    /**
     * Set refreshAt
     *
     * @param \DateTime $refreshAt
     *
     * @return Report
     */
    public function setRefreshAt($refreshAt = Null)
    {
        if ( $refreshAt ) {
            $this->refreshAt = $refreshAt;
        } else {
            $this->refreshAt = new \DateTime();
        }
        return $this;
    }

    /**
     * Get refreshAt
     *
     * @return \DateTime
     */
    public function getRefreshAt()
    {
        return $this->refreshAt;
    }
    
    /**
     * Set expireAt
     *
     * @param \DateTime $expireAt
     *
     * @return Report
     */
    public function setExpireAt(\DateTime $expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * Get expireAt
     *
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }
    
    
}
