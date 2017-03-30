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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Abstact Widget Model 
 * 
 * @author Bernard Paquier <pro@bernard-paquier.fr>
 */
class TableBlock extends BaseBlock
{

    //====================================================================//
    // *******************************************************************//
    //  BLOCK GENERICS PARAMETERS
    // *******************************************************************//
    //====================================================================//

    //====================================================================//
    // Define Standard Data Fields for this Widget Block
    static $DATA          = array(
        "rows"              => Null
    );

    //====================================================================//
    // Define Standard Options for this Widget Block
    // Uncomment to override défault options
    static $OPTIONS       = array(
        'Width'             => "col-xs-12 col-sm-12 col-md-12 col-lg-12",
        "Layout"            => "table-bordered table-hover",
        "HeadingRows"       => 1,
        "HeadingColumns"    => 0,
        "AllowHtml"         => False,                    
    );

        
    /**
     * @var string
     */
    protected $type = "TableBlock";
    
    //====================================================================//
    // *******************************************************************//
    //  Block Getter & Setter Functions
    // *******************************************************************//
    //====================================================================//
    
    /**
     * Add a Row to Table
     * 
     * @param   array $Row
     * 
     * @return  Widget
     */
    public function addRow($Row)
    {
        $this->data["rows"][]     =   $Row;
        return $this;
    }
    
    /**
     * Add Multiple Rows to Table
     * 
     * @param   array $Rows
     * 
     * @return  Widget
     */
    public function addRows($Rows)
    {
        foreach ($Rows as $Row) {
            $this->addRow($Row);
        }
        return $this;
    }
       
    /**
     * Set Block Contents
     *
     * @param array $Contents
     *
     * @return Widget
     */
    public function setContents($Contents)
    {
        //==============================================================================
        //  Safety Check
        if ( !is_array($Contents) && !is_a($Contents, "ArrayObject") ){
            return $this;
        } 
        
        //==============================================================================
        //  Safety Check - Verify Rows 
        if ( isset($Contents["rows"]) && $this->ValidateRows($Contents["rows"]) ){
            //==============================================================================
            //  Import Rows   
            $this->setData(["rows" => $Contents["rows"]]);
        }  
     
        return $this;
    }   
    
    /**
     * Verify Rows are Valid
     *
     * @param array $Rows
     *
     * @return Widget
     */
    public function ValidateRows($Rows)
    {
        //==============================================================================
        //  Verify Rows are inside an Array
        if ( !is_array($Rows) && !is_a($Rows, "ArrayObject") ){
            return False;
        } 
        foreach ($Rows as $Row) {
            //==============================================================================
            //  Verify Cells are inside an Array
            if ( !is_array($Row) && !is_a($Row, "ArrayObject") ){
                return False;
            }
            foreach ($Row as $Cell) {
                //==============================================================================
                //  Verify Cell is scalar
                if ( !is_scalar($Cell) ){
                    return False;
                } 
            } 
        }
        return True;
    }   
    
}
