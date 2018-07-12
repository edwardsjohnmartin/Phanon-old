var concept_count = 1;
var module_count = 1;
var component_count = 1;

function addConcept(){
    var input_name = "course[concepts][" + concept_count + "]";

    // Find course_contents div
    var courseContentsDiv = document.getElementById("course_contents");

    // Create concept article
    var conceptArticle = document.createElement("article");
    conceptArticle.id = "concept_article";
    conceptArticle.dataset.concept_id = concept_count;

        // Create concept div
        var conceptDiv = document.createElement("div");
        conceptDiv.classList.add("form-group");
        conceptDiv.id = "concept_div";

            // Create concept_fields div
            var conceptFieldsDiv = document.createElement("div");
            conceptFieldsDiv.id = "concept_fields";

                // Create hidden id input
                var conceptIdInput = document.createElement("input");
                conceptIdInput.type = "hidden";
                conceptIdInput.name = input_name + "[id]";
                conceptIdInput.value = concept_count;

                // Create name label
                var conceptNameLabel = document.createElement("label");
                conceptNameLabel.htmlFor = "concept_name";
                conceptNameLabel.innerText = "Concept Name";
    
                // Create name input
                var conceptNameInput = document.createElement("input");
                conceptNameInput.classList.add("form-control");
                conceptNameInput.placeholder = "Name";
                conceptNameInput.name = input_name + "[name]";
                conceptNameInput.required = true;
                conceptNameInput.type = "text";
                conceptNameInput.value = "";

                // Add all field elements to div
                conceptFieldsDiv.appendChild(conceptIdInput);
                conceptFieldsDiv.appendChild(conceptNameLabel);
                conceptFieldsDiv.appendChild(conceptNameInput);

            // Create course_contents div
            var conceptContentsDiv = document.createElement("div");
            conceptContentsDiv.id = "concept_contents";

            // Create course_buttons div
            var conceptButtonsDiv = document.createElement("div");
            conceptButtonsDiv.id = "concept_buttons";

                // Create add module button
                var addModuleButton = document.createElement("button");
                addModuleButton.type = "button";
                addModuleButton.innerText = "Add Module";
                addModuleButton.dataset.concept_id = concept_count;
                addModuleButton.setAttribute("onclick","addModule(this.dataset.concept_id)");

                // Create delete concept button
                var deleteConceptButton = document.createElement("button");
                deleteConceptButton.type = "button";
                deleteConceptButton.innerText = "Delete Concept";
                deleteConceptButton.dataset.concept_id = concept_count;
                deleteConceptButton.setAttribute("onclick","deleteConcept(this.dataset.concept_id)");

                // Add all buttons to div
                conceptButtonsDiv.appendChild(addModuleButton);
                conceptButtonsDiv.appendChild(deleteConceptButton);

            // Add the three divs to concept div
            conceptDiv.appendChild(conceptFieldsDiv);
            conceptDiv.appendChild(conceptContentsDiv);
            conceptDiv.appendChild(conceptButtonsDiv);

        // Add concept div to article
        conceptArticle.appendChild(conceptDiv);

    // Add article to course_contents div
    courseContentsDiv.appendChild(conceptArticle);

    // Increment concept_count
    concept_count++;
}

function addModule(concept_id){
    var input_name = "course[concepts][" + concept_id + "][modules][" + module_count + "]";

    // Find concept article by the passed in concept_id
    var conceptArticle = document.querySelectorAll('article[data-concept_id="' + concept_id + '"]')[0];
    var conceptDivs = conceptArticle.getElementsByTagName("div");
    
    // Find concept_contents div within concept article
    var courseContentsDiv;
    for(var i = 0; i < conceptDivs.length; i++){
        if(conceptDivs[i].id == "concept_contents"){
            conceptContentsDiv = conceptDivs[i];
        }
    }

    // Create module article
    var moduleArticle = document.createElement("article");
    moduleArticle.id = "concept_article";
    moduleArticle.classList.add("module");
    moduleArticle.dataset.module_id = module_count;

        // Create module div
        var moduleDiv = document.createElement("div");
        moduleDiv.id = "module_div";
        moduleDiv.classList.add("form-group");

            // Create module_fields div
            var moduleFieldsDiv = document.createElement("div");
            moduleFieldsDiv.id = "module_fields";

                // Create hidden id input
                var moduleIdInput = document.createElement("input");
                moduleIdInput.type = "hidden";
                moduleIdInput.name = input_name + "[id]";
                moduleIdInput.value = module_count;

                // Create name label
                var moduleNameLabel = document.createElement("label");
                moduleNameLabel.htmlFor = "module_name";
                moduleNameLabel.innerText = "Module Name";

                // Create name input
                var moduleNameInput = document.createElement("input");
                moduleNameInput.classList.add("form-control");
                moduleNameInput.placeholder = "Name";
                moduleNameInput.name = input_name + "[name]";
                moduleNameInput.required = true;
                moduleNameInput.type = "text";
                moduleNameInput.value = "";

                // Create open date label
                var moduleOpenDateLabel = document.createElement("label");
                moduleOpenDateLabel.htmlFor = "module_open_date";
                moduleOpenDateLabel.innerText = "Module Open Date";

                // Create open date input
                var moduleOpenDateInput = document.createElement("input");
                moduleOpenDateInput.classList.add("form-control");
                moduleOpenDateInput.name = input_name + "[open_date]";
                moduleOpenDateInput.required = true;
                moduleOpenDateInput.type = "datetime-local";
                moduleOpenDateInput.value = "2013-03-18T13:00";

                // Add all field elements to div
                moduleFieldsDiv.appendChild(moduleIdInput);
                moduleFieldsDiv.appendChild(moduleNameLabel);
                moduleFieldsDiv.appendChild(moduleNameInput);
                moduleFieldsDiv.appendChild(moduleOpenDateLabel);
                moduleFieldsDiv.appendChild(moduleOpenDateInput);

            // Create module_contents div
            var moduleContentsDiv = document.createElement("div");
            moduleContentsDiv.id = "module_contents";

                // Create module_components_list
                var moduleComponentsList = document.createElement("ul");
                moduleComponentsList.classList.add("components");
                moduleComponentsList.classList.add("authoring");
                moduleComponentsList.dataset.module_id = module_count;
                moduleComponentsList.style.display = "block";

                // Add module components_list to module_contents div
                moduleContentsDiv.appendChild(moduleComponentsList);

            // Create module_buttons div
            var moduleButtonsDiv = document.createElement("div");
            moduleButtonsDiv.id = "module_buttons";

                // Create add lesson button
                var addLessonButton = document.createElement("button");
                addLessonButton.type = "button";
                addLessonButton.innerText = "Add Lesson";
                addLessonButton.dataset.module_id = module_count;
                addLessonButton.setAttribute("onclick","addLesson(" + concept_id + ",this.dataset.module_id)");

                // Create add project button
                var addProjectButton = document.createElement("button");
                addProjectButton.type = "button";
                addProjectButton.innerText = "Add Project";
                addProjectButton.dataset.module_id = module_count;
                addProjectButton.setAttribute("onclick","addProject(" + concept_id + ",this.dataset.module_id)");

                // Create delete module button
                var deleteModuleButton = document.createElement("button");
                deleteModuleButton.type = "button";
                deleteModuleButton.innerText = "Delete Module";
                deleteModuleButton.dataset.module_id = module_count;
                deleteModuleButton.setAttribute("onclick","deleteModule(this.dataset.module_id)");

                // Add all buttons to div
                moduleButtonsDiv.appendChild(addLessonButton);
                moduleButtonsDiv.appendChild(addProjectButton);
                moduleButtonsDiv.appendChild(deleteModuleButton);

            // Add the three divs to module div
            moduleDiv.appendChild(moduleFieldsDiv);
            moduleDiv.appendChild(moduleContentsDiv);
            moduleDiv.appendChild(moduleButtonsDiv);

        // Add module div to article
        moduleArticle.appendChild(moduleDiv);

    // Add article to concept_contents div
    conceptContentsDiv.appendChild(moduleArticle);

    // Increment module_count 
    module_count++;
}

function addLesson(concept_id, module_id){
    var input_name = "course[concepts][" + concept_id + "][modules][" + module_id + "][components][" + component_count + "]";

    // Find module components_list by the passed in module_id
    var moduleComponentsList = document.querySelectorAll('ul[data-module_id="' + module_id + '"]')[0];

    // Create lesson div
    var lessonDiv = document.createElement("div");
    lessonDiv.id = "lesson_div";
    lessonDiv.dataset.lesson_id = component_count;

        // Create lesson list_item
        var lessonListItem = document.createElement("li");
        lessonListItem.classList.add("lesson");

            // Create lesson_fields div
            var lessonFieldsDiv = document.createElement("div");
            lessonFieldsDiv.id = "lesson_fields";

                // Create hidden id input
                var lessonIdInput = document.createElement("input");
                lessonIdInput.type = "hidden";
                lessonIdInput.name = input_name + "[id]";
                lessonIdInput.value = component_count;

                // Create hidden type input
                var lessonTypeInput = document.createElement("input");
                lessonTypeInput.type = "hidden";
                lessonTypeInput.name = input_name + "[type]";
                lessonTypeInput.value = "lesson";

                // Create name label
                var lessonNameLabel = document.createElement("label");
                lessonNameLabel.htmlFor = "lesson_name";
                lessonNameLabel.innerText = "Lesson Name";

                // Create name input
                var lessonNameInput = document.createElement("input");
                lessonNameInput.classList.add("form-control");
                lessonNameInput.placeholder = "Name";
                lessonNameInput.name = input_name + "[name]";
                lessonNameInput.required = true;
                lessonNameInput.type = "text";
                lessonNameInput.value = "";

                // Add all field elements to div
                lessonFieldsDiv.appendChild(lessonIdInput);
                lessonFieldsDiv.appendChild(lessonTypeInput);
                lessonFieldsDiv.appendChild(lessonNameLabel);
                lessonFieldsDiv.appendChild(lessonNameInput);

            // Create lesson_buttons div
            var lessonButtonsDiv = document.createElement("div");
            lessonButtonsDiv.id = "lesson_buttons";

                // Create edit lesson button
                var editLessonButton = document.createElement("button");
                editLessonButton.type = "button";
                editLessonButton.innerText = "Edit Lesson";
                editLessonButton.dataset.lesson_id = component_count;
                editLessonButton.setAttribute("onclick","editLesson(this.dataset.lesson_id)");

                // Create delete lesson button
                var deleteLessonButton = document.createElement("button");
                deleteLessonButton.type = "button";
                deleteLessonButton.innerText = "Delete Lesson";
                deleteLessonButton.dataset.lesson_id = component_count;
                deleteLessonButton.setAttribute("onclick","deleteLesson(this.dataset.lesson_id)");

                // Add all buttons to lesson_buttons div
                lessonButtonsDiv.appendChild(editLessonButton);
                lessonButtonsDiv.appendChild(deleteLessonButton);

            // Add the two divs to lesson list_item
            lessonListItem.appendChild(lessonFieldsDiv);
            lessonListItem.appendChild(lessonButtonsDiv);

        // Add lesson list_item to lesson div
        lessonDiv.appendChild(lessonListItem);

    // Add article to module components_list
    moduleComponentsList.appendChild(lessonDiv);

    // Increment component_count 
    component_count++;
}

function addProject(concept_id, module_id){
    var input_name = "course[concepts][" + concept_id + "][modules][" + module_id + "][components][" + component_count + "]";

    // Find module components_list by the passed in module_id
    var moduleComponentsList = document.querySelectorAll('ul[data-module_id="' + module_id + '"]')[0];

    // Create project div
    var projectDiv = document.createElement("div");
    projectDiv.id = "project_div";
    projectDiv.dataset.project_id = component_count;

        // Create project list_item
        var projectListItem = document.createElement("li");
        projectListItem.classList.add("project");

            // Create project_fields div
            var projectFieldsDiv = document.createElement("div");
            projectFieldsDiv.id = "project_fields";

                // Create hidden id input
                var projectIdInput = document.createElement("input");
                projectIdInput.type = "hidden";
                projectIdInput.name = input_name + "[id]";
                projectIdInput.value = component_count;

                // Create hidden type input
                var projectTypeInput = document.createElement("input");
                projectTypeInput.type = "hidden";
                projectTypeInput.name = input_name + "[type]";
                projectTypeInput.value = "project";

                // Create name label
                var projectNameLabel = document.createElement("label");
                projectNameLabel.htmlFor = "project_name";
                projectNameLabel.innerText = "Project Name";

                // Create name input
                var projectNameInput = document.createElement("input");
                projectNameInput.classList.add("form-control");
                projectNameInput.placeholder = "Name";
                projectNameInput.name = input_name + "[name]";
                projectNameInput.required = true;
                projectNameInput.type = "text";
                projectNameInput.value = "";

                // Add all field elements to div
                projectFieldsDiv.appendChild(projectIdInput);
                projectFieldsDiv.appendChild(projectTypeInput);
                projectFieldsDiv.appendChild(projectNameLabel);
                projectFieldsDiv.appendChild(projectNameInput);

            // Create project_buttons div
            var projectButtonsDiv = document.createElement("div");
            projectButtonsDiv.id = "project_buttons";

                // Create edit project button
                var editProjectButton = document.createElement("button");
                editProjectButton.type = "button";
                editProjectButton.innerText = "Edit Project";
                editProjectButton.dataset.project_id = component_count;
                editProjectButton.setAttribute("onclick","editProject(this.dataset.project_id)");

                // Create delete project button
                var deleteProjectButton = document.createElement("button");
                deleteProjectButton.type = "button";
                deleteProjectButton.innerText = "Delete Project";
                deleteProjectButton.dataset.project_id = component_count;
                deleteProjectButton.setAttribute("onclick","deleteProject(this.dataset.project_id)");

                // Add all buttons to project_buttons div
                projectButtonsDiv.appendChild(editProjectButton);
                projectButtonsDiv.appendChild(deleteProjectButton);

            // Add the two divs to project list_item
            projectListItem.appendChild(projectFieldsDiv);
            projectListItem.appendChild(projectButtonsDiv);

        // Add project list_item to project div
        projectDiv.appendChild(projectListItem);

    // Add article to module components_list
    moduleComponentsList.appendChild(projectDiv);

    // Increment component_count 
    component_count++;
}

function deleteConcept(concept_id){
    if(confirm("Are you sure you want to delete this concept?")){
        // Find concept article by the passed in concept_id
        var conceptArticle = document.querySelectorAll('article[data-concept_id="' + concept_id + '"]')[0];
        conceptArticle.parentNode.removeChild(conceptArticle);
    }
}

function deleteModule(module_id){
    if(confirm("Are you sure you want to delete this module?")){
        // Find module article by the passed in module_id
        var moduleArticle = document.querySelectorAll('article[data-module_id="' + module_id + '"]')[0];
        moduleArticle.parentNode.removeChild(moduleArticle);
    }
}

function deleteLesson(lesson_id){
    if(confirm("Are you sure you want to delete this lesson?")){
        // Find lesson div by the passed in lesson_id
        var lessonDiv = document.querySelectorAll('div[data-lesson_id="' + lesson_id + '"]')[0];
        lessonDiv.parentNode.removeChild(lessonDiv);
    }
}

function deleteProject(project_id){
    if(confirm("Are you sure you want to delete this project?")){
        // Find project div by the passed in project_id
        var projectDiv = document.querySelectorAll('div[data-project_id="' + project_id + '"]')[0];
        projectDiv.parentNode.removeChild(projectDiv);
    }
}
