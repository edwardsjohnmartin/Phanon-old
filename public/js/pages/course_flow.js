var turnScrollingOff = false;

function toggleEditMode(editBtn) {
    // Toggle visibility of all divs that contain a create object button
    var allDivs = $('.creation');
    $(allDivs).each(function(index, d){
        toggleDivVisibility(d);
    });

    // Toggle edit mode for all elements relating to the course
    toggleCourseEditMode();

    // Toggle edit mode for all elements relating to concepts
    toggleConceptEditMode();

    // Toggle edit mode for all elements relating to modules
    toggleModuleEditMode();

    if($(editBtn).text() == "Enable Edit Mode"){
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
    $(allFields).each(function(index, f){
        toggleContentEditable(f);
        addBlurEvent(f, function() {
            saveCourseEdit();
        });
    });
}

function toggleConceptEditMode() {
    // Toggle concept fields editability

    // name

    var allFields = $('article.concept').find('.editable');
    $(allFields).each(function(index, f){
        toggleContentEditable(f);
    });
}

function toggleModuleEditMode() {
    // Toggle module fields editability

    // name
    // open date
}

function toggleContentEditable(element) {
    if($(element).hasClass('edit-on')){
        $(element).removeClass('edit-on');
        $(element).attr('contentEditable', false);
    } else {
        $(element).addClass('edit-on');
        $(element).attr('contentEditable', true);
    }
}

/**
 * Toggles a div to be shown or hidden.
 * @param {*} divName 
 */
function toggleDivVisibility(myDiv) {
    if($(myDiv).hasClass('hidden')){
        // Make div visible
        $(myDiv).removeClass('hidden');
    } else {
        // Make div hidden
        $(myDiv).addClass('hidden');
    }
}

function toggleEditButtonText(editBtn) {
    if($(editBtn).text() == "Enable Edit Mode"){
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
    if($(element).hasClass('edit-on')){
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
        }
    });
}
