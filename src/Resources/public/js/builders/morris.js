
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
 * Splash Widgets - Jquery Morris Charts
 * 
 * DEPENDENCY: https://morrisjs.github.io/morris.js/
 */
export default class morris {

    /**
     * Check if Library is Loaded
     */
    isReady() 
    {
        if (typeof Raphael === "undefined") {
            console.warn("Splash Widgets : Raphael not Found");
            return false;
        }        
        if (typeof Morris === "undefined") {
            console.warn("Splash Widgets : Morris.js not Found");
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
        
        this.buildDonutCharts();
        this.buildOthersCharts();
        
        console.log("Splash Widgets : Created Morris Charts");
    }  
    
    /*
     * INITIALIZE MORRIS DONUT CHARTS
     */
    buildDonutCharts()
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }   
        
        //------------------------------------------------------------------------------
        // Walk on Morris Donuts Charts
        $('.morris-donut:not(:has(>svg))').each(function () {
            var $this = $(this);
            //------------------------------------------------------------------------------
            // Prepare Main Option Object
            var splashMorrisDonut = {
                //------------------------------------------------------------------------------
                // ID of the element in which to draw the chart.
                element: $this[0].id,
                //------------------------------------------------------------------------------
                // Chart data records
                data: $this.data('morris-dataset'),
            };
            //------------------------------------------------------------------------------
            // Merge with Chart Options 
            jQuery.extend(splashMorrisDonut, $this.data('morris-options') || []);

            //------------------------------------------------------------------------------
            // Render Morris Donut Chart 
            new Morris.Donut(splashMorrisDonut);

            return true;
        });
    }

    /*
     * INITIALIZE MORRIS COMMONS CHARTS
     */
    buildOthersCharts()
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }   
        
        //------------------------------------------------------------------------------
        // Walk on Morris Charts
        $('.morris-chart:not(:has(>svg))').each(function () {
            //------------------------------------------------------------------------------
            var $this = $(this);
            //------------------------------------------------------------------------------
            // Prepare Chart Type 
            var splashMorrisType = $this.data('morris-type') || "Line";
            //------------------------------------------------------------------------------
            // Prepare Main Option Object
            var splashMorrisChart = {
                // ID of the element in which to draw the chart.
                element: $this[0].id,
                // Chart data records
                data: $this.data('morris-dataset'),
                // The name of the data record attribute that contains x-values.
                xkey: $this.data('morris-xkey'),
                // A list of names of data record attributes that contain y-values.
                ykeys: $this.data('morris-ykeys'),
                // Labels for the ykeys
                labels: $this.data('morris-labels')
            };
            //------------------------------------------------------------------------------
            // Merge with Chart Options 
            jQuery.extend(splashMorrisChart, $this.data('morris-options') || []);

            switch (splashMorrisType)
            {
                case "Line":
                    // Render Morris Line Chart 
                    new Morris.Line(splashMorrisChart);
                    break;
                case "Area":
                    // Render Morris Area Chart 
                    new Morris.Area(splashMorrisChart);
                    break;
                case "Bar":
                    // Render Morris Bar Chart 
                    new Morris.Bar(splashMorrisChart);
                    break;
            }

            return true;
        });
    }

}


