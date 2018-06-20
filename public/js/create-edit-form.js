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

// Add any selected items from the multiselect to the table
function updateTable(tableName, multiselectName)
{
    // Get the table's body object
    var tableBody = document.getElementById(tableName).getElementsByTagName("tbody")[0];

    // Remove all options from the sortable list
    tableBody.innerHTML = "";

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

    // Add all selected options from multiselect to the table's body
    for(var i = 0; i < ids.length; i++){

        var tr = document.createElement("tr");
        var td1 = document.createElement("td");
        var td2 = document.createElement("td");

        td1.appendChild(document.createTextNode(details[i]));
        td1.setAttribute("id", ids[i]);

        td2.appendChild(makeRoleDropdown());

        tr.appendChild(td1);
        tr.appendChild(td2);
        tableBody.appendChild(tr);
    }
}

// Returns a dropdown of all roles except the admin role
function makeRoleDropdown()
{
    var selectEl = document.createElement("select");

    for(var i = 0; i < rolesArray.length; i++){
        var newOption = document.createElement("option");
        newOption.appendChild(document.createTextNode(rolesArray[i]["name"]));
        newOption.setAttribute("id", rolesArray[i]["id"]);
        selectEl.appendChild(newOption);
    }

    return selectEl;
}

// Create hidden form inputs to pass along the specified order of items in the list element
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

// Create hidden form inputs on submission to pass along the selection of users and roles to add to the course
function addUserRoleInputToForm(formName)
{
    $("#" + formName).submit(function(){
        var usersToAdd = [];
        var tableBody = document.getElementById("roleSelection").getElementsByTagName("tbody")[0];

        for(var i = 0; i < tableBody.getElementsByTagName("tr").length; i++){
            var row = tableBody.getElementsByTagName("tr")[i];
            var cells = row.getElementsByTagName("td");
            var user_id = cells[0].id;
            var dropdown = cells[1].getElementsByTagName("select")[0];
            var role_id = dropdown.options[dropdown.selectedIndex].id;
            usersToAdd.push([user_id, role_id]);
        }

        for(var i = 0; i < usersToAdd.length; i++){
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "usersToAdd" + "[]");
            input.setAttribute("value", usersToAdd[i]);

            document.getElementById(formName).appendChild(input);
        }
    });
}

// Enables the use of JQuery-UI's multiselect on a given select element
// The default text in the multiselect must also be supplied
function makeMultiSelect(sel, defaultText){
    sel = '#' + sel;

    $(document).ready(function(){
        $(sel).multiselect({
            nonSelectedText: defaultText,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            buttonWidth: '400px'
        });
    });
}