/* 
 * Load OpenWidget Contents with Ajax ACtion
 */
function SplashWidgets_LoadContents(Service, Type, Edit)
{
    $.ajax({
        type:   "POST",
        url:    Routing.generate("splash_widgets_render_widget", {Service : Service, Type : Type, Edit : Edit }),
        data:   false,
        cache:  true,
        success: function(data){
            WidgetBlock = document.getElementById(Type);
            if (WidgetBlock) {
                WidgetBlock.innerHTML = data;
                runAllCharts();
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
                SplashWidgets_Reload()
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
        
        console.log("Splash Widgets : Created SparkLine Line Chart");
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

        $this.sparkline('html', {
            type : 'pie',
            width :             $this.data('sparkline-piesize') || 90,
            height :            $this.data('sparkline-piesize') || 90,
            tooltipFormat :     '<span style="color: {{color}}">&#9679;</span> ({{percent.1}}%)',
            sliceColors :       $this.data('sparkline-piecolor') || ["CornflowerBlue ", "DarkOrange", "Crimson", "DarkViolet","ForestGreen", "LightCoral", "LightSeaGreen ", "MediumSpringGreen"],
            borderWidth :       $this.data('sparkline-border') || 1,
            offset :            $this.data('sparkline-offset') || 0,
            borderColor :       $this.data('border-color') || '#45494C'
        });
                                
        $this = null;
        
        console.log("Splash Widgets : Created SparkLine Pie Chart");
    });
}

/*
 * INITIALIZE MORRIS LINE CHARTS
 */
function SplashWidgets_MorrisLineCharts() 
{
    if (!Morris.Line) {
        return false;
    }        
    
    $('.morris-line:not(:has(>svg))').each(function() {
        var $this = $(this);
        // Prepare Main Option Object
        var splashMorrisLine = {
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
        jQuery.extend(splashMorrisLine, $this.data('morris-options') || []);
            
        // Render Morris Line Chart 
        new Morris.Line(splashMorrisLine);
                  
        console.log("Splash Widgets : Created Morris Line Chart");
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
 * INITIALIZE CHARTS
 * Description: Sparklines, PieCharts
 */
function runAllCharts() {
    
    SplashWidgets_SparkLineBarCharts();
    SplashWidgets_SparkLineLineCharts();
    SplashWidgets_SparkLinePieCharts();
    SplashWidgets_MorrisLineCharts();
    SplashWidgets_MorrisDonutCharts();
    
    /*
     * SPARKLINES
     * DEPENDENCY: js/plugins/sparkline/jquery.sparkline.min.js
     * See usage example below...
     */

    /* Usage:
     * 		<div class="sparkline-line txt-color-blue" data-fill-color="transparent" data-sparkline-height="26px">
     *			5,6,7,9,9,5,9,6,5,6,6,7,7,6,7,8,9,7
     *		</div>
     */

    if ($.fn.sparkline) {

                // variable declearations:

                var barColor,
                    sparklineHeight,
                    sparklineBarWidth,
                    sparklineBarSpacing,
                    sparklineNegBarColor,
                    sparklineStackedColor,
                    thisLineColor,
                    thisLineWidth,
                    thisFill,
                    thisSpotColor,
                    thisMinSpotColor,
                    thisMaxSpotColor,
                    thishighlightSpotColor,
                    thisHighlightLineColor,
                    thisSpotRadius,			        
                        pieColors,
                    pieWidthHeight,
                    pieBorderColor,
                    pieOffset,
                        thisBoxWidth,
                    thisBoxHeight,
                    thisBoxRaw,
                    thisBoxTarget,
                    thisBoxMin,
                    thisBoxMax,
                    thisShowOutlier,
                    thisIQR,
                    thisBoxSpotRadius,
                    thisBoxLineColor,
                    thisBoxFillColor,
                    thisBoxWhisColor,
                    thisBoxOutlineColor,
                    thisBoxOutlineFill,
                    thisBoxMedianColor,
                    thisBoxTargetColor,
                        thisBulletHeight,
                    thisBulletWidth,
                    thisBulletColor,
                    thisBulletPerformanceColor,
                    thisBulletRangeColors,
                        thisDiscreteHeight,
                    thisDiscreteWidth,
                    thisDiscreteLineColor,
                    thisDiscreteLineHeight,
                    thisDiscreteThrushold,
                    thisDiscreteThrusholdColor,
                        thisTristateHeight,
                    thisTristatePosBarColor,
                    thisTristateNegBarColor,
                    thisTristateZeroBarColor,
                    thisTristateBarWidth,
                    thisTristateBarSpacing,
                    thisZeroAxis,
                    thisBarColor,
                    sparklineWidth,
                    sparklineValue,
                    sparklineValueSpots1,
                    sparklineValueSpots2,
                    thisLineWidth1,
                    thisLineWidth2,
                    thisLineColor1,
                    thisLineColor2,
                    thisSpotRadius1,
                    thisSpotRadius2,
                    thisMinSpotColor1,
                    thisMaxSpotColor1,
                    thisMinSpotColor2,
                    thisMaxSpotColor2,
                    thishighlightSpotColor1,
                    thisHighlightLineColor1,
                    thishighlightSpotColor2,
                    thisFillColor1,
                    thisFillColor2;

                $('.sparkline:not(:has(>canvas))').each(function() {
                        var $this = $(this),
                                sparklineType = $this.data('sparkline-type') || 'bar';


//                        // PIE CHART
//                        if (sparklineType == 'pie') {
//
//                                        pieColors = $this.data('sparkline-piecolor') || ["#B4CAD3", "#4490B1", "#98AA56", "#da532c","#6E9461", "#0099c6", "#990099", "#717D8A"];
//                                    pieWidthHeight = $this.data('sparkline-piesize') || 90;
//                                    pieBorderColor = $this.data('border-color') || '#45494C';
//                                    pieOffset = $this.data('sparkline-offset') || 0;
//
//                                $this.sparkline('html', {
//                                        type : 'pie',
//                                        width : pieWidthHeight,
//                                        height : pieWidthHeight,
//                                        tooltipFormat : '<span style="color: {{color}}">&#9679;</span> ({{percent.1}}%)',
//                                        sliceColors : pieColors,
//                                        borderWidth : 1,
//                                        offset : pieOffset,
//                                        borderColor : pieBorderColor
//                                });
//
//                                $this = null;
//
//                        }

                        // BOX PLOT
                        if (sparklineType == 'box') {

                                        thisBoxWidth = $this.data('sparkline-width') || 'auto';
                                    thisBoxHeight = $this.data('sparkline-height') || 'auto';
                                    thisBoxRaw = $this.data('sparkline-boxraw') || false;
                                    thisBoxTarget = $this.data('sparkline-targetval') || 'undefined';
                                    thisBoxMin = $this.data('sparkline-min') || 'undefined';
                                    thisBoxMax = $this.data('sparkline-max') || 'undefined';
                                    thisShowOutlier = $this.data('sparkline-showoutlier') || true;
                                    thisIQR = $this.data('sparkline-outlier-iqr') || 1.5;
                                    thisBoxSpotRadius = $this.data('sparkline-spotradius') || 1.5;
                                    thisBoxLineColor = $this.css('color') || '#000000';
                                    thisBoxFillColor = $this.data('fill-color') || '#c0d0f0';
                                    thisBoxWhisColor = $this.data('sparkline-whis-color') || '#000000';
                                    thisBoxOutlineColor = $this.data('sparkline-outline-color') || '#303030';
                                    thisBoxOutlineFill = $this.data('sparkline-outlinefill-color') || '#f0f0f0';
                                    thisBoxMedianColor = $this.data('sparkline-outlinemedian-color') || '#f00000';
                                    thisBoxTargetColor = $this.data('sparkline-outlinetarget-color') || '#40a020';

                                $this.sparkline('html', {
                                        type : 'box',
                                        width : thisBoxWidth,
                                        height : thisBoxHeight,
                                        raw : thisBoxRaw,
                                        target : thisBoxTarget,
                                        minValue : thisBoxMin,
                                        maxValue : thisBoxMax,
                                        showOutliers : thisShowOutlier,
                                        outlierIQR : thisIQR,
                                        spotRadius : thisBoxSpotRadius,
                                        boxLineColor : thisBoxLineColor,
                                        boxFillColor : thisBoxFillColor,
                                        whiskerColor : thisBoxWhisColor,
                                        outlierLineColor : thisBoxOutlineColor,
                                        outlierFillColor : thisBoxOutlineFill,
                                        medianColor : thisBoxMedianColor,
                                        targetColor : thisBoxTargetColor

                                });

                                $this = null;

                        }

                        // BULLET
                        if (sparklineType == 'bullet') {

                                var thisBulletHeight = $this.data('sparkline-height') || 'auto';
                                    thisBulletWidth = $this.data('sparkline-width') || 2;
                                    thisBulletColor = $this.data('sparkline-bullet-color') || '#ed1c24';
                                    thisBulletPerformanceColor = $this.data('sparkline-performance-color') || '#3030f0';
                                    thisBulletRangeColors = $this.data('sparkline-bulletrange-color') || ["#d3dafe", "#a8b6ff", "#7f94ff"];

                                $this.sparkline('html', {

                                        type : 'bullet',
                                        height : thisBulletHeight,
                                        targetWidth : thisBulletWidth,
                                        targetColor : thisBulletColor,
                                        performanceColor : thisBulletPerformanceColor,
                                        rangeColors : thisBulletRangeColors

                                });

                                $this = null;

                        }

                        // DISCRETE
                        if (sparklineType == 'discrete') {

                                        thisDiscreteHeight = $this.data('sparkline-height') || 26;
                                    thisDiscreteWidth = $this.data('sparkline-width') || 50;
                                    thisDiscreteLineColor = $this.css('color');
                                    thisDiscreteLineHeight = $this.data('sparkline-line-height') || 5;
                                    thisDiscreteThrushold = $this.data('sparkline-threshold') || 'undefined';
                                    thisDiscreteThrusholdColor = $this.data('sparkline-threshold-color') || '#ed1c24';

                                $this.sparkline('html', {

                                        type : 'discrete',
                                        width : thisDiscreteWidth,
                                        height : thisDiscreteHeight,
                                        lineColor : thisDiscreteLineColor,
                                        lineHeight : thisDiscreteLineHeight,
                                        thresholdValue : thisDiscreteThrushold,
                                        thresholdColor : thisDiscreteThrusholdColor

                                });

                                $this = null;

                        }

                        // TRISTATE
                        if (sparklineType == 'tristate') {

                                        thisTristateHeight = $this.data('sparkline-height') || 26;
                                    thisTristatePosBarColor = $this.data('sparkline-posbar-color') || '#60f060';
                                    thisTristateNegBarColor = $this.data('sparkline-negbar-color') || '#f04040';
                                    thisTristateZeroBarColor = $this.data('sparkline-zerobar-color') || '#909090';
                                    thisTristateBarWidth = $this.data('sparkline-barwidth') || 5;
                                    thisTristateBarSpacing = $this.data('sparkline-barspacing') || 2;
                                    thisZeroAxis = $this.data('sparkline-zeroaxis') || false;

                                $this.sparkline('html', {

                                        type : 'tristate',
                                        height : thisTristateHeight,
                                        posBarColor : thisBarColor,
                                        negBarColor : thisTristateNegBarColor,
                                        zeroBarColor : thisTristateZeroBarColor,
                                        barWidth : thisTristateBarWidth,
                                        barSpacing : thisTristateBarSpacing,
                                        zeroAxis : thisZeroAxis

                                });

                                $this = null;

                        }

                        //COMPOSITE: BAR
                        if (sparklineType == 'compositebar') {

                                sparklineHeight = $this.data('sparkline-height') || '20px';
                            sparklineWidth = $this.data('sparkline-width') || '100%';
                            sparklineBarWidth = $this.data('sparkline-barwidth') || 3;
                            thisLineWidth = $this.data('sparkline-line-width') || 1;
                            thisLineColor = $this.data('data-sparkline-linecolor') || '#ed1c24';
                            thisBarColor = $this.data('data-sparkline-barcolor') || '#333333';

                                $this.sparkline($this.data('sparkline-bar-val'), {

                                        type : 'bar',
                                        width : sparklineWidth,
                                        height : sparklineHeight,
                                        barColor : thisBarColor,
                                        barWidth : sparklineBarWidth
                                        //barSpacing: 5

                                });

                                $this.sparkline($this.data('sparkline-line-val'), {

                                        width : sparklineWidth,
                                        height : sparklineHeight,
                                        lineColor : thisLineColor,
                                        lineWidth : thisLineWidth,
                                        composite : true,
                                        fillColor : false

                                });

                                $this = null;

                        }

                        //COMPOSITE: LINE
                        if (sparklineType == 'compositeline') {

                                        sparklineHeight = $this.data('sparkline-height') || '20px';
                                    sparklineWidth = $this.data('sparkline-width') || '90px';
                                    sparklineValue = $this.data('sparkline-bar-val');
                                    sparklineValueSpots1 = $this.data('sparkline-bar-val-spots-top') || null;
                                    sparklineValueSpots2 = $this.data('sparkline-bar-val-spots-bottom') || null;
                                    thisLineWidth1 = $this.data('sparkline-line-width-top') || 1;
                                    thisLineWidth2 = $this.data('sparkline-line-width-bottom') || 1;
                                    thisLineColor1 = $this.data('sparkline-color-top') || '#333333';
                                    thisLineColor2 = $this.data('sparkline-color-bottom') || '#ed1c24';
                                    thisSpotRadius1 = $this.data('sparkline-spotradius-top') || 1.5;
                                    thisSpotRadius2 = $this.data('sparkline-spotradius-bottom') || thisSpotRadius1;
                                    thisSpotColor = $this.data('sparkline-spot-color') || '#f08000';
                                    thisMinSpotColor1 = $this.data('sparkline-minspot-color-top') || '#ed1c24';
                                    thisMaxSpotColor1 = $this.data('sparkline-maxspot-color-top') || '#f08000';
                                    thisMinSpotColor2 = $this.data('sparkline-minspot-color-bottom') || thisMinSpotColor1;
                                    thisMaxSpotColor2 = $this.data('sparkline-maxspot-color-bottom') || thisMaxSpotColor1;
                                    thishighlightSpotColor1 = $this.data('sparkline-highlightspot-color-top') || '#50f050';
                                    thisHighlightLineColor1 = $this.data('sparkline-highlightline-color-top') || '#f02020';
                                    thishighlightSpotColor2 = $this.data('sparkline-highlightspot-color-bottom') ||
                                        thishighlightSpotColor1;
                                    thisHighlightLineColor2 = $this.data('sparkline-highlightline-color-bottom') ||
                                        thisHighlightLineColor1;
                                    thisFillColor1 = $this.data('sparkline-fillcolor-top') || 'transparent';
                                    thisFillColor2 = $this.data('sparkline-fillcolor-bottom') || 'transparent';

                                $this.sparkline(sparklineValue, {

                                        type : 'line',
                                        spotRadius : thisSpotRadius1,

                                        spotColor : thisSpotColor,
                                        minSpotColor : thisMinSpotColor1,
                                        maxSpotColor : thisMaxSpotColor1,
                                        highlightSpotColor : thishighlightSpotColor1,
                                        highlightLineColor : thisHighlightLineColor1,

                                        valueSpots : sparklineValueSpots1,

                                        lineWidth : thisLineWidth1,
                                        width : sparklineWidth,
                                        height : sparklineHeight,
                                        lineColor : thisLineColor1,
                                        fillColor : thisFillColor1

                                });

                                $this.sparkline($this.data('sparkline-line-val'), {

                                        type : 'line',
                                        spotRadius : thisSpotRadius2,

                                        spotColor : thisSpotColor,
                                        minSpotColor : thisMinSpotColor2,
                                        maxSpotColor : thisMaxSpotColor2,
                                        highlightSpotColor : thishighlightSpotColor2,
                                        highlightLineColor : thisHighlightLineColor2,

                                        valueSpots : sparklineValueSpots2,

                                        lineWidth : thisLineWidth2,
                                        width : sparklineWidth,
                                        height : sparklineHeight,
                                        lineColor : thisLineColor2,
                                        composite : true,
                                        fillColor : thisFillColor2

                                });

                                $this = null;

                        }

                });

        }// end if

    /*
     * EASY PIE CHARTS
     * DEPENDENCY: js/plugins/easy-pie-chart/jquery.easy-pie-chart.min.js
     * Usage: <div class="easy-pie-chart txt-color-orangeDark" data-pie-percent="33" data-pie-size="72" data-size="72">
     *			<span class="percent percent-sign">35</span>
     * 	  	  </div>
     */

    if ($.fn.easyPieChart) {

            $('.easy-pie-chart').each(function() {
                    var $this = $(this),
                            barColor = $this.css('color') || $this.data('pie-color'),
                        trackColor = $this.data('pie-track-color') || 'rgba(0,0,0,0.04)',
                        size = parseInt($this.data('pie-size')) || 25;

                    $this.easyPieChart({

                            barColor : barColor,
                            trackColor : trackColor,
                            scaleColor : false,
                            lineCap : 'butt',
                            lineWidth : parseInt(size / 8.5),
                            animate : 1500,
                            rotate : -90,
                            size : size,
                            onStep: function(from, to, percent) {
                    $(this.el).find('.percent').text(Math.round(percent));
                    }

                    });

                    $this = null;
            });

    } // end if

}
/* ~ END: INITIALIZE CHARTS */

/* 
 * Wait then Reload Page
 */
function SplashWidgets_Reload(){
    setTimeout(function() { window.location.reload();   }, 1000);  
}