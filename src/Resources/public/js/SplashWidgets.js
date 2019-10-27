
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

//------------------------------------------------------------------------------
// Charts Plugins Builders 
import SparklineBuilder from './builders/sparkline.js';
import MorrisBuilder from './builders/morris.js';

//------------------------------------------------------------------------------
// Managers 
import WidgetManager from './managers/widget.js';
import CollectionManager from './managers/collection.js';
import AjaxManager from './managers/ajax.js';

/**
 * Splash Widgets JS Functions
 */
;
(function ($) {

    'use strict';

    $.SplashWidgets = {

        // List of Available Charts Builders
        chartBuilders : [],
        
        // Widgets Manager
        widgets: new WidgetManager(),
        
        // Widgets Collections Manager
        collections: new CollectionManager(),
        
        // Ajax Manager
        ajax: new AjaxManager(),
        
        /**
         * Init Splahs Widget Library
         */
        init: function () {

            //------------------------------------------------------------------------------
            // Connect All Charts Plugins 
            this.loadChartsPlugins();
            
            //------------------------------------------------------------------------------
            // Setup Page Events 
            $(window).on('load', function () {
                $.SplashWidgets.onWindowLoad();
            });

            $(document).ready(function (e) {
                $.SplashWidgets.onDocumentReady();
            });

            $(window).on('resize', function () {
                $.SplashWidgets.onWindowResize();
            });      
            
        },
        
        //------------------------------------------------------------------------------
        // CHARTS BUILDER 
        //------------------------------------------------------------------------------

        /**
         * Load All Charts Plugins
         */
        loadChartsPlugins: function () 
        {
            
            //------------------------------------------------------------------------------
            // Jquery Sparkline Charts
            this.chartBuilders.push(new SparklineBuilder());
            
            //------------------------------------------------------------------------------
            // Jquery Morris Charts
            this.chartBuilders.push(new MorrisBuilder());
            
        },
        
        /**
         * Build All Charts 
         */
        buidlAllCharts: function () 
        {
            //------------------------------------------------------------------------------
            // Walk on All Available Charts Plugins 
            this.chartBuilders.forEach(function(chartBuilder, index) {
                //------------------------------------------------------------------------------
                // Build All Available Charts
                chartBuilder.buildAll();
            });
              
            console.log("Widgets: Rebuild All Charts");
        },        
        
        //------------------------------------------------------------------------------
        // PAGE EVENTS
        //------------------------------------------------------------------------------
        
        /**
         * Triggered when Window / DOM is Load
         */
        onWindowLoad: function () {
        },

        /**
         * Triggered when Document is Ready, Fully Loaded
         */
        onDocumentReady: function () {
            //------------------------------------------------------------------------------
            // Load All Ajax Widgets
            this.widgets.loadAll();
            //------------------------------------------------------------------------------
            // Setup All Widgets Jquery Events
            this.widgets.setupAll();
            //------------------------------------------------------------------------------
            // Setup All Widgets Collections Jquery Events
            this.collections.setupAll();
            //------------------------------------------------------------------------------
            // Build All Available Charts
            this.buidlAllCharts();
            
        },

        /**
         * Triggered when Window is Rezised 
         */
        onWindowResize: function () {
            //------------------------------------------------------------------------------
            // Setup All Widgets Jquery Events
            this.widgets.setupAll();
            //------------------------------------------------------------------------------
            // Setup All Widgets Collections Jquery Events
            this.collections.setupAll();
            //------------------------------------------------------------------------------
            // Build All Available Charts
            this.buidlAllCharts();
        },
        
        
        /**
         * Setup All Datetime Pickers with Custom Options
         * 
         * @return
         */
        setupDateTimePickers: function () {

            //------------------------------------------------------------------------------
            // Bootstrap Standard DatesPicker
//            if (typeof $.fn.datepicker !== 'undefined') {
//                $('.datepicker').datepicker({
//                    language: 'fr'
//                });
//            }
            
            //------------------------------------------------------------------------------
            // Flatpickr DatesPicker
            if (typeof $.fn.flatpickr !== 'undefined') {
                //------------------------------------------------------------------------------
                // Detect Typed Texts
                var collection = $('.datepicker');
                if(!collection.length) {
                    return;
                }

                //------------------------------------------------------------------------------
                // Init All Typed Texts
                collection.each(function(index){
                    //------------------------------------------------------------------------------
                    // Base Options
                    var flatConfig = {
                        locale: $.flatpickrFrench,
                        dateFormat: "d/m/Y"
                    };
                    //------------------------------------------------------------------------------
                    // Detect Options
                    flatConfig.minDate = $(this).data('min-date') || null;
                    flatConfig.maxDate = $(this).data('max-date') || null;
                    //------------------------------------------------------------------------------
                    // Build Date Picker
                    $(this).flatpickr(flatConfig);
                });        
            }
        }       
        
    };

    $.SplashWidgets.init();

})(jQuery);