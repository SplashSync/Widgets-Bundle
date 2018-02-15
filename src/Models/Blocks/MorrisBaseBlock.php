<?php

/*
 * This file is part of the Splash Sync project.
 *
 * (c) Bernard Paquier <pro@bernard-paquier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Splash\Widgets\Models\Blocks;

/**
 * @abstact Morris Js Chart Base Block Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class MorrisBaseBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        "title"             => "",
        "dataset"           => array(),
        "xkey"              => "label",
        "ykeys"             => ["value"],
        "labels"            => ["Data"],
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
        'Width'             => "col-sm-12 col-md-12 col-lg-12",
        "AllowHtml"         => False,    
        "ChartOptions"      => array(
            "hideHover"     =>  True
        ),
    );

        
    /**
     * @var string
     */
    protected $type = "MorrisLineBlock";
    
    /**
     * Set Block Contents from Array
     *
     * @param array $Contents
     *
     * @return MorrisLineBlock
     */
    public function setContents($Contents)
    {
        //==============================================================================
        //  Safety Check
        if ( !is_array($Contents) && !is_a($Contents, "ArrayObject") ){
            return $this;
        } 
        
        //==============================================================================
        //  Import Title
        if ( !empty($Contents["title"]) ){
            $this->setTitle($Contents["title"]);
        }         
        //==============================================================================
        //  Import Data 
        if ( !empty($Contents["dataset"]) ){
            $this->setDataSet($Contents["dataset"]);
        }     
        //==============================================================================
        //  Import Parameters
        if ( !empty($Contents["xkey"]) ){
            $this->setXkey($Contents["xkey"]);
        }                
        if ( !empty($Contents["ykeys"]) ){
            $this->setYkeys($Contents["ykeys"]);
        }                
        if ( !empty($Contents["labels"]) ){
            $this->setLabels($Contents["labels"]);
        }                
     
        return $this;
    }      
    
    /**
     * @abstract    Parse Array Data for Morris Charts
     * 
     * @param   mixed   $In
     * 
     * @return  array
     */
    protected function filterArray($In)
    {
        if ( is_a($In, "ArrayObject")) {
            return array_values($In->getArrayCopy());
        } 
        return array_values($In);
    }
    
    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Set Title
     * 
     * @param   $text
     * 
     * @return  MorrisLineBlock
     */
    public function setTitle($text)
    {
        $this->data["title"]     =   $text;
        return $this;
    }
    
    /**
     * Get Title
     * 
     * @return  String
     */
    public function getTitle()
    {
        return $this->data["title"];
    }   
       
    
    /**
     * Set Chart Data
     * 
     * @param   array $data
     * 
     * @return  MorrisLineBlock
     */
    public function setDataSet($data)
    {
        $this->data["dataset"]     = $this->filterArray($data);
        return $this;
    }
    
    /**
     * Get Data
     * 
     * @return  array
     */
    public function getDataSet()
    {
        return $this->data["dataset"];
    }    

    /**
     * Set X key
     * 
     * @return  MorrisLineBlock
     */
    public function setXkey($value)
    {
        $this->data["xkey"]     =   $value;
        return $this;
    }
    
    /**
     * Set Y Keys
     * 
     * @return  MorrisLineBlock
     */
    public function setYkeys($value)
    {
        $this->data["ykeys"]     =   $this->filterArray($value);
        return $this;
    }
    
    /**
     * Set Labels
     * 
     * @return  MorrisLineBlock
     */
    public function setLabels($value)
    {
        $this->data["labels"]     =   $this->filterArray($value);
        return $this;
    }
        
    /**
     * Set Chart Options
     * 
     * @param   array $options
     * 
     * @return  MorrisLineBlock
     */
    public function setChartOptions($options)
    {
        $this->options["ChartOptions"]     =   $options;
        return $this;
    }    
}
