/* 
 * Load OpenWidget Contents with Ajax ACtion
 */
function SplashWidgets_LoadContents(Service, Type, Edit, Editable)
{
    $.ajax({
        type:   "POST",
        url:    Routing.generate("splash_widgets_render_widget", {Service : Service, Type : Type, Edit : Edit | false , Editable : Editable | false}),
        data:   false,
        cache:  true,
        success: function(data){
            WidgetBlock = document.getElementById(Type);
            if (WidgetBlock) {
                WidgetBlock.innerHTML = data;
                SplashWidgets_runAllCharts();
            }
            return data;
        }
    }); 

}

/* 
 * Load OpenWidget Contents with Ajax ACtion
 */
function SplashWidgets_LoadEditModal(Service, Type)
{
    /*
     * Load Widget Edit Modal
     */
    $.ajax({
        type:   "POST",
        url:    Routing.generate("splash_widgets_edit_widget", {Service : Service, Type : Type }),
        data:   false,
        cache:  true,
        success: function(data){
            
            /*
             * Create Bootstrap Modal if Needed
             */
            Modal = document.getElementById("SplashWidgetModal");
            if (!Modal) {
                $('body').append('<div class="modal fade" tabindex="-1" role="dialog" id="SplashWidgetModal"></div>');
                Modal = document.getElementById("SplashWidgetModal");
            }
            
            /*
             * Load Modal Contents
             */
            Modal.innerHTML = data;
            
            SplashWidgets_AjaxifyForm('splash_widgets_settings_form');
            
            $('#SplashWidgetModal').modal("show");
            
            return data;
        }
    }); 
    
}

/* 
 * Load OpenWidget Contents with Ajax ACtion
 */
function SplashWidgets_AjaxifyForm(FormName)
{
    var forms = [ '[ name="' + FormName + '"]' ];

    $( forms.join(',') ).submit( function( e ){
      e.preventDefault();

      SplashWidgets_PostForm( $(this), function( response ){
            $('#SplashWidgetModal').modal("hide");
            SplashWidgets_Reload();  
            console.log("SplashWidgets : Options Updated ");
      });

      return false;
    });
  
    console.log("SplashWidgets : Ajaxify Form " + FormName);
}

function SplashWidgets_PostForm( $form, callback ){
 
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

/* 
 * Update Widgets Collection Ordering with AJAX Requests
 */
function SplashWidgets_UpdatePositions(CollectionId,Ordering){

    $.ajax({
            type: "POST",
            url: Routing.generate('splash_widgets_collections_reorder', { CollectionId: CollectionId , Ordering: Ordering }),
            data: false,
            cache: true,
            error: function(data){
            },
            success: function(data){
            }
        });         
}

/* 
 * Update Widgets Collection Dates Preset with AJAX Requests
 */
function SplashWidgets_UpdateDatesPreset(CollectionId,Preset){

    $.ajax({
            type: "POST",
            url: Routing.generate('splash_widgets_collections_preset', { CollectionId: CollectionId , Preset: Preset }),
            data: false,
            cache: true,
            error: function(data){
            },
            success: function(data){
                SplashWidgets_Reload();  
            }
        });         
}

/* 
 * Add a Widget to Collection with AJAX Requests
 */
function SplashWidgets_AddToCollection(CollectionId, Service, Type){

    $.ajax({
            type: "POST",
            url: Routing.generate('splash_widgets_collections_add_widget', { CollectionId: CollectionId , Service: Service, Type : Type }),
            data: false,
            cache: true,
            error: function(data){
            },
            success: function(data){
                SplashWidgets_Reload();
                console.log("SplashWidgets : Widget Added");
            }
        });         
}

/* 
 * Remove a Widget from Collection with AJAX Requests
 */
function SplashWidgets_RemoveFromCollection(Service, Type){

    $.ajax({
            type: "POST",
            url: Routing.generate('splash_widgets_collections_remove_widget', { Service: Service , Type : Type }),
            data: false,
            cache: true,
            error: function(data){
            },
            success: function(data){
                SplashWidgets_Reload(0);
                console.log("SplashWidgets : Widget Added");
            }
        });         
}

/* 
 * Load OpenWidget Add Widget Modal with Ajax ACtion
 */
function SplashWidgets_LoadAddModal(CollectionId, Channel)
{
    /*
     * Load Widget Edit Modal
     */
    $.ajax({
        type:   "POST",
        url:    Routing.generate("splash_widgets_collections_list_widget", { CollectionId : CollectionId , Channel : Channel}),
        data:   false,
        cache:  true,
        success: function(data){
            
            /*
             * Create Bootstrap Modal if Needed
             */
            Modal = document.getElementById("SplashWidgetModal");
            if (!Modal) {
                $('body').append('<div class="modal fade" tabindex="-1" role="dialog" id="SplashWidgetModal"></div>');
                Modal = document.getElementById("SplashWidgetModal");
            }
            
            /*
             * Load Modal Contents
             */
            Modal.innerHTML = data;
            $('#SplashWidgetModal').modal("show");

            return data;
        }
    }); 
    
}

/*
 * INITIALIZE SPARKLINE BAR CHARTS
 */
function SplashWidgets_SparkLineBarCharts() 
{
    /*
     * SPARKLINES
     * DEPENDENCY: js/plugins/sparkline/jquery.sparkline.min.js
     */
    if (!$.fn.sparkline) {
        return false;
    }        

    $('.sparkline:not(:has(>canvas))').each(function() {
        var $this = $(this);

        // FILTER on BAR CHART
        if ($this.data('sparkline-type') !== 'bar') {
            return false;
        }

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
        
        console.log("Splash Widgets : Created SparkLine Bar Chart");
    });
}
    
/*
 * INITIALIZE SPARKLINE Line CHARTS
 */
function SplashWidgets_SparkLineLineCharts() 
{
    /*
     * SPARKLINES
     * DEPENDENCY: js/plugins/sparkline/jquery.sparkline.min.js
     */
    if (!$.fn.sparkline) {
        return false;
    }        
    
    $('.sparkline:not(:has(>canvas))').each(function() {
        var $this = $(this);

        // FILTER on BAR CHART
        if ($this.data('sparkline-type') !== 'line') {
            return false;
        }

        $this[0].style.display = "block";        
        $this.sparkline('html', {
            
            type :                  'line',
            width :                 $this.data('sparkline-width')           || '95%',
            height :                $this.data('sparkline-height')          || '200px',
            lineWidth :             $this.data('sparkline-line-width')      || 2,
            lineColor :             $this.data('sparkline-line-color')      || 'MediumTurquoise',
            fillColor :             $this.data('sparkline-fill-color')      || 'LightCyan',
            spotColor :             $this.data('sparkline-spot-color')      || '',
            minSpotColor :          $this.data('sparkline-minspot-color')   || 'Blue',
            maxSpotColor :          $this.data('sparkline-maxspot-color')   || 'Red',
            highlightSpotColor :    $this.data('sparkline-highlightspot-color') || 'Purple',
            highlightLineColor :    $this.data('sparkline-highlightline-color') || 'Purple',
            spotRadius :            $this.data('sparkline-spotradius')      || 3,
            chartRangeMin :         $this.data('sparkline-min-y')           || 'undefined',
            chartRangeMax :         $this.data('sparkline-max-y')           || 'undefined',
            chartRangeMinX :        $this.data('sparkline-min-x')           || 'undefined',
            chartRangeMaxX :        $this.data('sparkline-max-x')           || 'undefined',
            normalRangeMin :        $this.data('sparkline-min-val')         || 'undefined',
            normalRangeMax :        $this.data('sparkline-max-val')         || 'undefined',
            normalRangeColor :      $this.data('sparkline-norm-color')      || '#c0c0c0',
            drawNormalOnTop :       $this.data('sparkline-draw-normal')     || false

        });

        $this = null;
        
        console.log("Splash Widgets : Created SparkLine Line Chart");
    });
}

/*
 * INITIALIZE SPARKLINE Pie CHARTS
 */
function SplashWidgets_SparkLinePieCharts() 
{
    /*
     * SPARKLINES
     * DEPENDENCY: js/plugins/sparkline/jquery.sparkline.min.js
     */
    if (!$.fn.sparkline) {
        return false;
    }        
    
    $('.sparkline:not(:has(>canvas))').each(function() {
        var $this = $(this);

        // FILTER on BAR CHART
        if ($this.data('sparkline-type') !== 'pie') {
            return false;
        }

        $this[0].style.display = "block";        
        $this.sparkline('html', {
            type :              'pie',
            width :             $this.data('sparkline-piesize') || 90,
            height :            $this.data('sparkline-piesize') || 90,
            tooltipFormat :     '<span style="color: {{color}}">&#9679;</span> {{offset:labels}} ({{percent.1}}%)',
            sliceColors :       $this.data('sparkline-piecolor') || ["CornflowerBlue ", "DarkOrange", "Crimson", "DarkViolet","ForestGreen", "LightCoral", "LightSeaGreen ", "MediumSpringGreen"],
            borderWidth :       $this.data('sparkline-border') || 1,
            offset :            $this.data('sparkline-offset') || 0,
            borderColor :       $this.data('border-color') || '#45494C',
            tooltipValueLookups: { 'labels': $this.data('sparkline-labels') || {}  }
        });
                     
        $this = null;
        
        console.log("Splash Widgets : Created SparkLine Pie Chart");
    });
}

/*
 * INITIALIZE MORRIS DONUT CHARTS
 */
function SplashWidgets_MorrisDonutCharts() 
{
    if (!Morris.Donut) {
        return false;
    }        
    
    $('.morris-donut:not(:has(>svg))').each(function() {
        var $this = $(this);
        // Prepare Main Option Object
        var splashMorrisDonut = {
            // ID of the element in which to draw the chart.
            element:    $this[0].id,
            // Chart data records
            data:      $this.data('morris-dataset'),
        };
        // Merge with Chart Options 
        jQuery.extend(splashMorrisDonut, $this.data('morris-options') || []);
            
        // Render Morris Donut Chart 
        new Morris.Donut(splashMorrisDonut);
                  
        console.log("Splash Widgets : Created Morris Donut Chart");
    });
}

/*
 * INITIALIZE MORRIS COMMONS CHARTS
 */
function SplashWidgets_MorrisCharts() 
{
    if (!Morris.Area) {
        return false;
    }        
    
    $('.morris-chart:not(:has(>svg))').each(function() {
        var $this = $(this);
        // Prepare Chart Type 
        var splashMorrisType  = $this.data('morris-type') || "Line";
        // Prepare Main Option Object
        var splashMorrisChart = {
            // ID of the element in which to draw the chart.
            element:    $this[0].id,
            // Chart data records
             data:      $this.data('morris-dataset'),
            // The name of the data record attribute that contains x-values.
            xkey:       $this.data('morris-xkey'),
            // A list of names of data record attributes that contain y-values.
            ykeys:      $this.data('morris-ykeys'),
            // Labels for the ykeys
            labels:     $this.data('morris-labels')
        };
        // Merge with Chart Options 
        jQuery.extend(splashMorrisChart, $this.data('morris-options') || []);
            
            
        switch(splashMorrisType) 
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
                  
        console.log("Splash Widgets : Created Morris " + splashMorrisType + " Chart");
    });
}

/*
 * INITIALIZE CHARTS
 * Description: Sparklines, PieCharts
 */
function SplashWidgets_runAllCharts() {
    
    SplashWidgets_SparkLineBarCharts();
    SplashWidgets_SparkLineLineCharts();
    SplashWidgets_SparkLinePieCharts();
    SplashWidgets_MorrisCharts();
    SplashWidgets_MorrisDonutCharts();
    
}
/* ~ END: INITIALIZE CHARTS */

/* 
 * Wait then Reload Page
 */
function SplashWidgets_Reload( Delay ){
    setTimeout(function() { window.location.reload();   }, Delay | 1000);  
}