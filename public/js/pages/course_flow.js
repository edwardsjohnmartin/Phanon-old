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
    if (ele.attr("contenteditable") != null) {
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
            alert("Could not create a new Concept.");
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

            var buttonDiv = ele.parentNode;

            var conceptArticle = buttonDiv.parentNode;
            conceptArticle.insertBefore(moduleArticle, buttonDiv);
        },
        error: function () {
            alert("Could not create a new Module.");
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
            alert("Could not create a new Lesson.");
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
            alert("Could not create a new Project.");
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
            $("#modal").html(data);
            $("#fader").css("display", "block");
            $("#modalContent form").on('submit', overriddeFormSave);
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

                // clear form
                $("#modal").empty();
                $("#fader").css("display", "none");
            } else {
                alert("Error: "+mess.message);
            }
        }
        , error: function () {
            alert("oopsy");
        }
    });

    return false;
}