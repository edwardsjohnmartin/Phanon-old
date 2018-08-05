function toggleEditMode(editBtn) {
    // Toggle visibility of all divs that contain a create object button
    var allDivs = $('.creation');
    $(allDivs).each(function (index, d) {
        toggleDivVisibility(d);
    });

    // Toggle edit mode for all elements relating to the course
    toggleCourseEditMode();

    // Toggle edit mode for all elements relating to concepts
    toggleConceptEditMode();

    // Toggle edit mode for all elements relating to modules
    toggleModuleEditMode();

    if ($(editBtn).text() == "Enable Edit Mode") {
        // Expand all the modules so their contents can be seen
        expandModules();
    }

    // Toggle edit button text
    toggleEditButtonText(editBtn);
}

function toggleCourseEditMode() {
    // Toggle course field elements contentEditable attribute

    // name
    // open date
    // close date

    var allFields = $('#courseDetails').find('.editable');
    $(allFields).each(function (index, f) {
        toggleContentEditable(f);
        addBlurEvent(f, function () {
            saveCourseEdit();
        });
    });
}

function toggleConceptEditMode() {
    // Toggle concept fields editability

    // name

    var allFields = $('article.concept').find('.editable');
    $(allFields).each(function (index, f) {
        toggleContentEditable(f);
    });
}

function toggleModuleEditMode() {
    // Toggle module fields editability

    // name
    // open date
}

function toggleContentEditable(element) {
    var ele = $(element); // calling this first and only once is more optimized.
    var par = ele.parent();
    if (ele.hasClass('edit-on')) {
        ele.removeClass('edit-on');
        ele.attr('contentEditable', false);
        var oldLink = par.attr("data-old-link");
        par.attr("href", oldLink);
        par.attr("data-old-link", null);
        if (p.hasClass("dates"))
            addDateButton(ele);
    } else {
        ele.addClass('edit-on');
        ele.attr('contentEditable', true);
        var oldLink = par.attr("href");
        par.attr("data-old-link", oldLink);
        par.attr("href", "#");
    }
}

function addDateButton(obj) {
    //var dateBtn = $("<button>").text("Set Date");
    //obj.after(dateBtn);
}

/**
 * Toggles a div to be shown or hidden.
 * @param {*} divName 
 */
function toggleDivVisibility(myDiv) {
    if ($(myDiv).hasClass('hidden')) {
        // Make div visible
        $(myDiv).removeClass('hidden');
    } else {
        // Make div hidden
        $(myDiv).addClass('hidden');
    }
}

function toggleEditButtonText(editBtn) {
    if ($(editBtn).text() == "Enable Edit Mode") {
        $(editBtn).text("Turn Off Edit Mode");
    } else {
        $(editBtn).text("Enable Edit Mode");
    }
}

function expandModules() {
    var btns = $('.expander');
    //$.each(expandButtons, function(e, btn){
    //    $(btn).addClass('collapser').removeClass('expander');
    //    $(btn).parent().find('.components').animate({ height: "toggle" });
    //});
    // just call the click event, it already expands everything.
    turnScrollingOff = true;
    $.each(btns, function (e, btn) {
        btn.click();
    });
    turnScrollingOff = false;
}

function collapseModules() {
    var btns = $('.collapser');
    turnScrollingOff = true;
    $.each(btns, function (e, btn) {
        btn.click();
    });
    turnScrollingOff = false;
}


function addBlurEvent(element, blurFunction) {
    if ($(element).hasClass('edit-on')) {
        $(element).blur(blurFunction);
    } else {
        $(element).off('blur');
    }
}

function createConcept(course_id, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { course_id: course_id, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            var courseFlow = document.getElementById('courseFlow');
            courseFlow.innerHTML += data;
        },
        error: function () {
            addPopup("Could not create a new Concept.", "error");
        }
    });
}

function createModule(ele, concept_id, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { concept_id: concept_id, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            var newEle = document.createElement('div');
            newEle.innerHTML = data;

            var moduleArticle = newEle.getElementsByTagName('article')[0];

            var container = ele.parentNode.parentNode;
            var possibleLists = container.getElementsByTagName("div");
            var moduleList;
            for (var i = 0; i < possibleLists.length; i++) {
                if (possibleLists[i].classList.contains("moduleContainer")) {
                    moduleList = possibleLists[i];
                    break; // stop after first. It should be the right one.
                }
            }
            moduleList.appendChild(moduleArticle);
        },
        error: function () {
            addPopup("Could not create a new Module.", "error");
        }
    });
}

function createLesson(ele, module_id, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { module_id: module_id, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            var newEle = document.createElement('div');
            newEle.innerHTML = data;

            var listItem = newEle.getElementsByTagName('li')[0];

            var buttonDiv = ele.parentNode;

            var moduleArticle = buttonDiv.parentNode;

            var componentsList = moduleArticle.getElementsByTagName('ul')[0];
            componentsList.appendChild(listItem);
        },
        error: function () {
            addPopup("Could not create a new Lesson.", "error");
        }
    });
}

function createProject(ele, module_id, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { module_id: module_id, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            var newEle = document.createElement('div');
            newEle.innerHTML = data;

            var listItem = newEle.getElementsByTagName('li')[0];

            var buttonDiv = ele.parentNode;

            var moduleArticle = buttonDiv.parentNode;

            var componentsList = moduleArticle.getElementsByTagName('ul')[0];
            componentsList.appendChild(listItem);
        },
        error: function () {
            addPopup("Could not create a new Project.", "error");
        }
    });
}

function saveCourseEdit() {
    var url = $('#courseDetails').data('course-url');

    var course_id = $('#courseDetails').data('course-id');
    var name = $('#courseName').text();
    var open_date = $('#courseOpenDate').text();
    var close_date = $('#courseCloseDate').text();

    $.ajax({
        type: "POST",
        url: url,
        data: {
            course_id: course_id,
            name: name,
            open_date: open_date,
            close_date: close_date,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
        },
        error: function () {
            alert("Could not edit course.");
        }
    });
}

function showEditForm(btn) {
    var itemType = btn.getAttribute("data-item-type");
    var itemId = btn.getAttribute("data-item-id");

    if (itemType == "lesson") {
        //load lesson edit form
    } else if (itemType == "project") {
        //load project edit form
    } else {
        // type not yet defined.
    }

    $.ajax({
        type: "GET",
        url: "../" + itemType + "s/miniEditForm/" + itemId,
        success: function (data) {
            showModal(data);
            $("#modal form").on('submit', overriddeFormSave);
        },
        error: function () {
            alert("Could not get edit form.");
        }
    });

}

/**
* Overrides the save form behavior to allow AJAX behavior
* --This will keep the need from reloading the page to log in.
* @param evt document event for form submission
*/
function overriddeFormSave(evt) {
    evt.preventDefault(); // stop the form from officially submitting,
    // return false did not work below.
    var frm = $(this);
    var url = frm.attr("action");
    $.ajax({
        url: url
        , method: 'POST'
        , cache: false
        , data: frm.serialize()
        , success: function (mess) {
            if (mess.type == "success") {
                addPopup(mess.message, "success");
                // update visuals
                if (mess.identifier == "modal") {
                    //show results back in modal
                    showModal(mess.html);
                    // keep modal open
                } else {
                    // replace page element with content.
                    $("#" + mess.identifier).replaceWith(mess.html);
                    closeModal();
                }
                // clear form
            } else {
                addPopup(mess.message, "error");
            }
        }
        , error: function () {
            addPopup("I am sorry we ran into a problem", "error");
        }
    });

    return false;
}

/**
 * Set modal content to the given html content and show the modal.
 * @param {any} html - content to place in modal.
 */
function showModal(html) {
    $("#modal").html(html);
    $("#modal").append('<button class="closer" tooltip="Close Form" '
        + 'onclick = "closeModal()" > Close Form</button >');
    $("#fader").css("display", "block");
}

/**
 * Clear modal content and hide the modal form.
 */
function closeModal() {
    $("#modal").empty();
    $("#fader").css("display", "none");
}
/**
 * Display the form of team for the given project in the modal.
 * @param {any} projId
 */
function displayTeamsForm(projId) {
    var url = "../projects/" + projId + "/teams";
    $.ajax({
        url: url
        , method: 'GET'
        , cache: false
        , data: { version: "modal" }
        , success: function (data) {
            showModal(data);
            $("#modal form").on('submit', overriddeFormSave);
        }
        , error: function () {
            addPopup("I am sorry we ran into a problem -- teams form", "error");
        }
    });
}
/**
 * Display the form of team for the given project in the modal.
 * @param {any} projId
 */
function displayTeamsList(projId) {
    var url = "../teams/showForProject/" + projId;
    $.ajax({
        url: url
        , method: 'GET'
        , cache: false
        , data: { version: "modal" }
        , success: function (data) {
            showModal(data);
        }
        , error: function () {
            addPopup("I am sorry we ran into a problem - teams list", "error");
        }
    });
}

// course sorting logic starts here
function makeCourseContentSortable() {
    //make modules sortable 
    $(".moduleContainer").sortable({
        items: ".module",
        handle: ".dragHandleModule",
        placeholder: "module",
        connectWith: ".moduleContainer",
        stop: function (evt, ui) {
            var t = ui.item;
            var p = t.prev();
            var newParId = t.parent().attr("data-concept-id");
            var url = $("#courseContent").attr("data-module-move-url");

            var currId = t.attr("data-module-id");
            var prevId;
            var hasPrevious = p.length > 0;
            if (hasPrevious)
                prevId = p.attr("data-module-id");
            else
                prevId = -1; // no previous; at start of list.

            //alert("c: " + currId + " p: " + prevId + " r: " + newParId);

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    current_id: currId,
                    previous_id: prevId,
                    concept_id: newParId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        addPopup(data.message, "save");
                        // reorder numbers
                        var count;
                        if (hasPrevious) {
                            count = parseInt(p.attr("data-item-count"));
                            count++; // increment to set to this element
                        } else {
                            count = 1; // start of list; start at 1
                        }
                    } else {
                        addPopup(data.message, "error");
                    }
                },
                error: function (x, d) {
                    console.log(x);
                    console.log(d);
                    addPopup("Could not save exercise", "error");
                }
            });
        }
    });
    $(".module").disableSelection();

    //make components sortable 
    $(".components").sortable({
        items: ".component",
        handle: ".dragHandleComponent",
        placeholder: "component",
        connectWith: ".components",
        stop: function (evt, ui) {
            var t = ui.item;
            var p = t.prev();
            var n = t.next();
            var newParId = t.parents(".module").attr("data-module-id");
            var url = $("#courseContent").attr("data-component-move-url");

            var currParts = t[0].id.split("_");
            var currType = currParts[0];
            var currId = currParts[1];

            if (currType == "project") {
                url = url.replace("/lessons/", "/projects/");
            }

            var prevId;
            var prevType = "";
            var hasPrevious = p.length > 0;
            if (hasPrevious) {
                var prevParts = p[0].id.split("_");
                prevType = prevParts[0];
                prevId = prevParts[1];
            } else {
                prevId = -1; // no previous; at start of list.
            }

            var nextId;
            var nextType = "";
            var hasNext = n.length > 0;
            if (hasNext) {
                var nextParts = n[0].id.split("_");
                nextType = nextParts[0];
                nextId = nextParts[1];
            } else {
                nextId = -1; // no next; at end of list.
            }
            
            //alert("c: (" + currType + "|" + currId + ") p: (" + prevType + "|"
            //    + prevId + ") r: " + newParId );

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    previous_id: prevId,
                    previous_type: prevType,
                    current_id: currId,
                    current_type: currType,
                    next_id: nextId,
                    next_type: nextType,
                    module_id: newParId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        addPopup(data.message, "save");
                        // reorder numbers
                        var count;
                        if (hasPrevious) {
                            count = parseInt(p.attr("data-item-count"));
                            count++; // increment to set to this element
                        } else {
                            count = 1; // start of list; start at 1
                        }

                    } else {
                        addPopup(data.message, "error");
                    }
                },
                error: function (x, d) {
                    console.log(x);
                    console.log(d);
                    addPopup("Could not save component", "error");
                }
            });
        }
    });
    $(".module").disableSelection();

}