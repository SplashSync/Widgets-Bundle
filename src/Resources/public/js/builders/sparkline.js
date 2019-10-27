
/*
 * This file is part of SplashSync Project.
 *
 * Copyright (C) 2015-2019 Splash Sync  <www.splashsync.com>
 * All rights reserved.
 *
 * NOTE: All information contained herein is, and remains the property of Splash
 * Sync and its suppliers, if any.  The intellectual and technical concepts
 * contained herein are proprietary to Splash Sync and its suppliers, and are
 * protected by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior written
 * permission is obtained from Splash Sync.
 *
 * @author Bernard Paquier <contact@splashsync.com>
 */


/**
 * Splash Widgets - Jquery Sparkline Charts
 * 
 * DEPENDENCY: https://omnipotent.net/jquery.sparkline
 */
export default class sparkline {

    /**
     * Check if Library is Loaded
     */
    isReady() 
    {
        if (!$.fn.sparkline) {
            console.warn("Splash Widgets : jQuery SparkLine not Found");
            return false;
        }        
        
        return true;
    }
    
    /**
     * Iinit All Sparkline Charts
     */
    buildAll() 
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }  
        
        this.buildBarCharts();
        this.buildLineCharts();
        this.buildPieCharts();
        
        console.log("Splash Widgets : Created SparkLine Charts");
    }
        
    /*
     * INITIALIZE SPARKLINE BAR CHARTS
     */
    buildBarCharts() 
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }   
        
        //------------------------------------------------------------------------------
        // Walk on Sparkline Charts
        $('.sparkline:not(:has(>canvas))').each(function() {
            var $this = $(this);

            //------------------------------------------------------------------------------
            // FILTER on BAR CHART
            if ($this.data('sparkline-type') !== 'bar') {
                return false;
            }

            //------------------------------------------------------------------------------
            // Build Chart
            $this[0].style.display = "block";        
            $this.sparkline('html', {
                    type :              'bar',
                    barColor :          $this.data('sparkline-bar-color')           || 'MediumTurquoise',
                    height :            $this.data('sparkline-height')              || '200px',
                    barWidth :          $this.data('sparkline-barwidth')            || 15,
                    barSpacing :        $this.data('sparkline-barspacing')          || 2,
                    stackedBarColor :   $this.data('sparkline-barstacked-color')    || ["#A90329", "#0099c6", "#98AA56", "#da532c", "#4490B1", "#6E9461", "#990099", "#B4CAD3"],
                    negBarColor :       $this.data('sparkline-negbar-color')        || 'IndianRed',
                    zeroAxis : 'false'
            });

            $this = null;
            
            return true;
        });
    }   
    
    /*
     * INITIALIZE SPARKLINE LINE CHARTS
     */
    buildLineCharts()
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }   

        $('.sparkline:not(:has(>canvas))').each(function () {
            var $this = $(this);

            //------------------------------------------------------------------------------
            // FILTER on BAR CHART
            if ($this.data('sparkline-type') !== 'line') {
                return false;
            }

            //------------------------------------------------------------------------------
            // Build Chart
            $this[0].style.display = "block";
            $this.sparkline('html', {

                type: 'line',
                width: $this.data('sparkline-width') || '95%',
                height: $this.data('sparkline-height') || '200px',
                lineWidth: $this.data('sparkline-line-width') || 2,
                lineColor: $this.data('sparkline-line-color') || 'MediumTurquoise',
                fillColor: $this.data('sparkline-fill-color') || 'LightCyan',
                spotColor: $this.data('sparkline-spot-color') || '',
                minSpotColor: $this.data('sparkline-minspot-color') || 'Blue',
                maxSpotColor: $this.data('sparkline-maxspot-color') || 'Red',
                highlightSpotColor: $this.data('sparkline-highlightspot-color') || 'Purple',
                highlightLineColor: $this.data('sparkline-highlightline-color') || 'Purple',
                spotRadius: $this.data('sparkline-spotradius') || 3,
                chartRangeMin: $this.data('sparkline-min-y') || 'undefined',
                chartRangeMax: $this.data('sparkline-max-y') || 'undefined',
                chartRangeMinX: $this.data('sparkline-min-x') || 'undefined',
                chartRangeMaxX: $this.data('sparkline-max-x') || 'undefined',
                normalRangeMin: $this.data('sparkline-min-val') || 'undefined',
                normalRangeMax: $this.data('sparkline-max-val') || 'undefined',
                normalRangeColor: $this.data('sparkline-norm-color') || '#c0c0c0',
                drawNormalOnTop: $this.data('sparkline-draw-normal') || false

            });

            $this = null;

            return true;
        });
    }

    /*
     * INITIALIZE SPARKLINE Pie CHARTS
     */
    buildPieCharts()
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }   

        $('.sparkline:not(:has(>canvas))').each(function () {
            var $this = $(this);

            //------------------------------------------------------------------------------
            // FILTER on BAR CHART
            if ($this.data('sparkline-type') !== 'pie') {
                return false;
            }

            //------------------------------------------------------------------------------
            // Build Chart
            $this[0].style.display = "block";
            $this.sparkline('html', {
                type: 'pie',
                width: $this.data('sparkline-piesize') || 90,
                height: $this.data('sparkline-piesize') || 90,
                tooltipFormat: '<span style="color: {{color}}">&#9679;</span> {{offset:labels}} ({{percent.1}}%)',
                sliceColors: $this.data('sparkline-piecolor') || ["CornflowerBlue ", "DarkOrange", "Crimson", "DarkViolet", "ForestGreen", "LightCoral", "LightSeaGreen ", "MediumSpringGreen"],
                borderWidth: $this.data('sparkline-border') || 1,
                offset: $this.data('sparkline-offset') || 0,
                borderColor: $this.data('border-color') || '#45494C',
                tooltipValueLookups: {'labels': $this.data('sparkline-labels') || {}}
            });

            $this = null;

            return true;
        });
    }    

}


