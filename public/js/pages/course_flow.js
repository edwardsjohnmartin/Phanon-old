/**
 * Expands and collapses a modules component list
 * @param {button} e Button that called the function
 * @param {int} module_id The id of which modules list to expand/collapse
 */
function toggleExpandCollapse(e, module_id){
    // Find module list by the passed in module_id
    var module_list = document.querySelectorAll('#module_list[data-module_id="' + module_id + '"]')[0];

    // Toggle the height of the modules list
    $(document).ready(function(){
        $(module_list).toggle(function(){
            $(this).animate({height:"100%"},200);
        },function(){
            $(this).animate({height:"0%"},200);
        });
    });

    // Toggle the class of the button to control which icon it shows and where on the module article it shows
    if(e.classList.contains("collapser")){
        e.classList.remove("collapser");
        e.classList.add("expander");
    } else if(e.classList.contains("expander")){
        e.classList.remove("expander");
        e.classList.add("collapser");
    }
}
