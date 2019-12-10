
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
 * Splash Widgets - Collections Manager
 */
export default class collection {

    /**
     * Check if Library is Loaded
     */
    isReady() 
    {
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
        // Detect All Widgets Add Modal Loader Btns
        $.SplashWidgets.ajax.setupAction(
            ".splash-widget-list",
            $.SplashWidgets.collections.loadAddModal
        );
        //------------------------------------------------------------------------------
        // Detect All Widgets Remove Btns
        $.SplashWidgets.ajax.setupAction(
            ".splash-widget-remover",
            $.SplashWidgets.collections.remove
        );       
        //------------------------------------------------------------------------------
        // Detect All Sortable Collections
        $('.splash-widget-sortable-collection').sortable({
            update: $.SplashWidgets.collections.sort,                
        });

    }
    
    /* 
     * Load OpenWidget Add Widget Modal with Ajax ACtion
     */
    loadAddModal(element)
    {
        //------------------------------------------------------------------------------
        // Load Modal & Ajaxify Form
        $.SplashWidgets.ajax.loadModal(
            $(element).data("list"), 
            "SplashWidgetModal"
        );  
    }
    
    /* 
     * Remove Widget From Collection Action
     */
    add(widget)
    {
        $.SplashWidgets.ajax.post($(widget).data("add"));
    }   
    
    /* 
     * Remove Widget From Collection Action
     */
    remove(widget)
    {
        $.SplashWidgets.ajax.post($(widget).data("remove"));
    }    
    
    /* 
     * Update Widget Collection Sorting Action
     */
    sort(event, ui)
    {
        $.SplashWidgets.ajax.ping(
            $(event.target).data("sort"),
            { 
                "ordering" : $(event.target).sortable('toArray', { attribute: 'data-id' }) 
            }
        );
    }   

}


