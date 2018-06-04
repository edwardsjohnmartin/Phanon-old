// Add any selected items from the multiselect to the ordered list
function updateList(olName, multiselectName)
{
    // Get the ordered list object
    var objectList = document.getElementById(olName);

    // Remove all options from the sortable list
    objectList.innerHTML = "";

    // Get all options from multiselect element
    var allOptions = document.getElementById(multiselectName).options;

    var ids = [];
    var details = [];

    // If an option was selected, add the id and name/prompt of the object to arrays
    for(var i = 0; i < allOptions.length; i++){
        if(allOptions[i].selected){
            ids.push(allOptions[i].value);
            details.push(allOptions[i].innerHTML);
        }
    }

    // Add all selected options from multiselect to the sortable list
    for(var i = 0; i < ids.length; i++){
        var li = document.createElement("li");

        li.appendChild(document.createTextNode(details[i]));
        li.setAttribute("id", ids[i]);

        objectList.appendChild(li);
    }
}

// Create hidden form inputs to pass the order with the rest of the data
function addInputsToForm(formName, listName, varName){
    $("#" + formName).submit(function(){
        var objects = document.getElementById(listName).getElementsByTagName("li");

        for(var i = 0; i < objects.length; i++){
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", varName + "[]");
            input.setAttribute("value", objects[i].id);

            document.getElementById(formName).appendChild(input);
        }
    });
}