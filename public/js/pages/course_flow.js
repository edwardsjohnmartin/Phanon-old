function toggleVisibilityByClass(className){
    if($(className).css('visibility') == 'hidden'){
        $(className).css('visibility', 'visible');
        $(className).css('display', 'initial');
    } else {
        $(className).css('visibility', 'hidden');
        $(className).css('display', 'none');
    }
}

function toggleEditButtonText(editBtn){
    if(editBtn.innerText == "Enable Edit Mode"){
        editBtn.innerText = "Turn Off Edit Mode";
        addBlurEvents();
        expandModules();
    } else {
        editBtn.innerText = "Enable Edit Mode";
        removeBlurEvents();
    }

    toggleVisibilityByClass('.edit-button-div');
    toggleEditableElements();
}

function toggleEditableElements(){
    if($('.editable').attr('contentEditable') == 'true'){
        $('.editable').attr('contentEditable', 'false');
    } else {
        $('.editable').attr('contentEditable', 'true');
    }

    $('.editable').toggleClass('edit-on');
}

function addBlurEvents(){
    $('.editable').blur(function(){
        console.log("blur happened");
        console.log(this);
    });
}

function expandModules(){
    var expandButtons = $('.expander');
    $.each(expandButtons, function(e, btn){
        $(btn).addClass('collapser').removeClass('expander');
        $(btn).parent().find('.components').animate({ height: "toggle" });
    });
}

function removeBlurEvents(){
    $('.editable').off("blur");
}

function createConcept(course_id, url){
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

function createModule(ele, concept_id, url){
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

function createLesson(ele, module_id, url){
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

function createProject(ele, module_id, url){
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
