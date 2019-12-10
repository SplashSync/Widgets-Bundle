
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
 * Splash Widgets - Ajax Actions Manager
 */
export default class ajax {

    /**
     * Check if Library is Loaded
     */
    isReady() 
    {
        return true;
    }
    
    /**
     * Setup Action for Buttons
     */
    setupAction(selector, callback) 
    {
        //------------------------------------------------------------------------------
        // Detect All Element wit this Selector
        var collection = $(selector);
        if(!collection.length) {
            return;
        }
        //------------------------------------------------------------------------------
        // Walk on All Elements
        collection.each(function(){
            //------------------------------------------------------------------------------
            // Prevent repeated Events
            if ($(this).hasClass("ready")) {
                return;
            }
            $(this).addClass("ready");
            //------------------------------------------------------------------------------
            // Setup Reload Action
            $(this).click(function (e) {
                callback(this);
                e.preventDefault();
                e.stopPropagation();
            });
        });
    }  
    
    /* 
     * Touch an Url
     */
    ping(url, data)
    {
        //------------------------------------------------------------------------------
        // Execute Ajax Request
        $.ajax({
            type:   "POST",
            url:    url,
            data:   data,
            cache:  true,
        });
    }  
    
    /* 
     * Touch an Url & Reload Page
     */
    post(url, data)
    {
        //------------------------------------------------------------------------------
        // Execut Ajax R
        $.ajax({
            type:   "POST",
            url:    url,
            data:   data,
            cache:  true,
            success: function(data){
                setTimeout(function() { window.location.reload();   }, 500);
            }
        });
    }  
    
    /* 
     * Load Raw Modal with Ajax Action
     */
    loadModal(url, elementId, ajaxFormName)
    {
        //------------------------------------------------------------------------------
        // Load Modal
        $.ajax({
            type:   "POST",
            url:    url,
            data:   false,
            cache:  true,
            success: function(data){
                //------------------------------------------------------------------------------
                // Create Bootstrap Modal if Needed
                var Modal = document.getElementById(elementId);
                if (!Modal) {
                    $('body').append('<div class="modal fade" tabindex="-1" role="dialog" id="'+elementId+'"></div>');
                    Modal = document.getElementById(elementId);
                }
                //------------------------------------------------------------------------------
                // Load Modal Contents
                Modal.innerHTML = data;
                //------------------------------------------------------------------------------
                // Ajaxify Form
                if (ajaxFormName) {  
                    $.SplashWidgets.ajax.ajaxify(elementId, ajaxFormName);
                }              
                //------------------------------------------------------------------------------
                // Show Modal
                $(Modal).modal("show");

                return data;
            }
        });
    }
    
    /* 
     * Transform Form for Ajax Submit 
     */
    ajaxify(elementId, ajaxFormName)
    {
        //------------------------------------------------------------------------------
        // find the Form        
        var forms = [ '[ name="' + ajaxFormName + '"]' ];
        //------------------------------------------------------------------------------
        // Setup Submit Action        
        $( forms.join(',') ).submit( function( e )
        {
            e.preventDefault();

            $.SplashWidgets.ajax.postForm( $(this), function(){
                //------------------------------------------------------------------------------
                // Hide Bootstrap Modal
                var Modal = document.getElementById(elementId);
                if (!Modal) {
                    Modal.modal("hide");
                }              
                //------------------------------------------------------------------------------
                // Reload Page
                setTimeout(function() { window.location.reload();   }, 500);
            });

          return false;
        });
    }
    
    /* 
     * Ajax Submit of the Form 
     */
    postForm( $form, callback )
    {
        //------------------------------------------------------------------------//
        //  Get all form values
        var values = {};
        $.each( $form.serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        //------------------------------------------------------------------------//
        //  Throw the form values to the server!
        $.ajax({
            type        : $form.attr( 'method' ),
            url         : $form.attr( 'action' ),
            data        : values,
            success     : function(data) {
                callback( data );
            },
        });
    }      
}


