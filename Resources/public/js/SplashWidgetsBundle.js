/* 
 * Load OpenWidget Contents with Ajax ACtion
 */
function OWLoadWidget(Service, Identifer)
{
    $.ajax({
        type:   "POST",
        url:    Routing.generate("open_widgets_core_render", {Service : Service, WidgetId : Identifer }),
        data:   false,
        cache:  true,
        success: function(data){
            WidgetBlock = document.getElementById(Identifer);
            if (WidgetBlock) {
                WidgetBlock.innerHTML = data;
            }
            return data;
        }
    }); 

}
