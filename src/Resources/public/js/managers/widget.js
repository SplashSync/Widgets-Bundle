
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
 * Splash Widgets - Widget Manager
 */
export default class widget {
    
    /**
     * Check if Library is Loaded
     */
    isReady() 
    {
        if (typeof jQuery === "undefined") {
            console.warn("Splash Widgets : jQuery not Found");
            return false;
        }            
        
        return true;
    }
    
    /**
     * Setup All Widgets Possibles Events
     */
    setupAll() 
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }          
        //------------------------------------------------------------------------------
        // Detect All Widgets Reloader Btns
        $.SplashWidgets.ajax.setupAction(
            ".splash-widget-reloader",
            $.SplashWidgets.widgets.load
        );        
        //------------------------------------------------------------------------------
        // Detect All Widgets Edit Btns
        $.SplashWidgets.ajax.setupAction(
            ".splash-widget-edit",
            $.SplashWidgets.widgets.edit
        );     
    }  
    
    /**
     * Load All Widgets waiting for Ajax Loading
     */
    loadAll() 
    {
        //------------------------------------------------------------------------------
        // Safety Check
        if (!this.isReady()) {
            return false;
        }          
        //------------------------------------------------------------------------------
        // Detect All Waiting Widgets
        var collection = $(".splash-widget-loader");
        if(!collection.length) {
            return;
        }
        //------------------------------------------------------------------------------
        // Walk on All Waiting Widgets
        collection.each(function(){
            $.SplashWidgets.widgets.load(this);
        });
    }      
    
    /**
     * Load Widget Contents from Ajax
     */
    load(widget) 
    {
        //------------------------------------------------------------------------------
        // Collect Widget Infos
        var target = $(widget).data("target");
        var reloadUrl = $(widget).data("reload");
        //------------------------------------------------------------------------------
        // Load Widget from Ajax
        $.ajax({
            type:   "POST",
            url:    reloadUrl,
            data:   false,
            cache:  true,
            success: function(data){
                widget = document.getElementById(target);
                if (widget) {
                    widget.innerHTML = data;
                    $.SplashWidgets.onWindowResize();
                }
            }
        }); 
    }
    
    /* 
     * Load Widget Edit Form with Ajax Action
     */
    edit(widget)
    {
        //------------------------------------------------------------------------------
        // Load Modal & Ajaxify Form
        $.SplashWidgets.ajax.loadModal(
            $(widget).data("edit"), 
            "SplashWidgetModal", 
            "splash_widgets_settings_form"
        );
    }
}


