/**
 * Turns a normal textarea element into a CodeMirror editor.
 * @param {*} textarea The id of the textarea element to be turned into a CodeMirror editor.
 */
function makeCodeMirror(textarea) {
    var myTextArea = document.getElementById(textarea);

    var myCodeMirror = CodeMirror(function (elt) {
        myTextArea.parentNode.replaceChild(elt, myTextArea);
    }, {
            value: myTextArea.value,
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true
        });

    return myCodeMirror;
}

/**
 * Updates the onclick event of the button element to run the Python code in a CodeMirror editor.
 * @param {*} button The id of the button element who's onclick will be used for the run function.
 */
function setRunButtonEvent(button) {
    document.getElementById(button).onclick = function () {
        run();
    };
}

//TODO: this do not technically make the button; should consider renaming to a
//     more accurate name like - setResetButtonEvents
/**
 * 
 * @param {*} buttonId 
 */
function makeResetButton(buttonId) {
    var btnReset = document.getElementById(buttonId);

    btnReset.onclick = function () {
        var resetCode = btnReset.attributes["data-reset-code"].value;
        replaceEditorText("ideCodeWindow", resetCode);
        addPopup("Code reset to starter code.","reset");
    };
}

/**
 * 
 * @param {*} prompt 
 */
function inf(prompt) {
    return window.prompt(String(prompt));
}

/**
 * 
 * @param {*} x 
 */
function builtinRead(x) {
    if (Sk.builtinFiles === undefined || Sk.builtinFiles["files"][x] === undefined)
        throw "File not found: '" + x + "'";
    return Sk.builtinFiles["files"][x];
}

/**
 * 
 * @param {*} text 
 */
function outf(text) {
    if (text && typeof text !== "undefined") {
        text = text.replace("<", "&lt;").replace(">", "&gt;");
        var mypre = document.getElementById("output");
        mypre.innerHTML = mypre.innerHTML + text;
    }
}

/**
 * Defines what functions Skulpt will use to run Python code and where to output the results.
 * @param {*} textOutput The id of the element the text output will go.
 * @param {*} graphicsOutput The id of the element any Turtle graphics will be drawn to.
 */
function configSkulpt(textOutput, graphicsOutput) {
    Sk.pre = textOutput;
    Sk.configure({
        output: outf,
        read: builtinRead,
        inputfun: inf,
        inputfunTakesPrompt: true
    });

    (Sk.TurtleGraphics || (Sk.TurtleGraphics = {})).target = graphicsOutput;
}

/**
 * Runs Python code using Skulpt and handles the output, showing errors or saving to the database.
 * @param {*} codeToRun The Python code to run.
 */
function runCode(codeToRun, outputArea, userCode = "") {
    //TODO: Some of the complexity can be alleviated here by creating more functions

    if (codeToRun.length == 0) {
        codeToRun = " ";
    }

    var myPromise = Sk.misceval.asyncToPromise(function () {
        return Sk.importMainWithBody("<stdin>", false, codeToRun, true);
    });

    var runBtn = document.getElementById("btnRunCode");
    var itemType = runBtn.attributes["data-item-type"].value;
    var itemId = runBtn.attributes["data-item-id"].value;
    var url = runBtn.attributes["data-save-url"].value;

    if (itemType == "project") {
        saveProjectCode(itemId, userCode, url);
    }

    myPromise.then(
        function (retSuccess) {
            // This will run the Python code using the specified function in the Python code of the python.py file
            if (hasTestCode) {
                var ret = Sk.misceval.callsim(
                    retSuccess.tp$getattr('__TEST'),
                    Sk.builtin.str(userCode),
                    Sk.builtin.str(outputArea.innerText)
                );

                var msg = "";
                var success = true;
                var successCount = 0;
                var testErrorsMessage = "";

                var testResults = parseTestResults(ret.v);
                for (var i = 0; i < testResults.length; i++) {
                    if (!testResults[i].success) {
                        success = false;
                        msg = testResults[i].message;
                        //break; // Do not break on the first error
                        testErrorsMessage += "<li>" + testResults[i].message + "</li>";
                    } else {
                        successCount++;
                    }
                }
            }

            if (itemType == "exercise") {
                var testsPassed = "<h3>" + successCount + "/" + testResults.length + " tests passed.</h3>" +
                    "<ol>" + testErrorsMessage + "</ol>";
                addPopup(testsPassed, (success ? "success" : "error") + " test");

                if (success) {
                    var nextLink = "<a href='" + getLinkFromButton("btnNext")
                        + "' >Next</a>";
                    msg = "Well done. Click " + nextLink + " to go to the next exercise.";
                    addPopup(msg, "success permanent");

                    toggleCurrentStep("btnRunCode", "btnNext");

                    saveExerciseCode(itemId, userCode, true, url);
                } else {
                    saveExerciseCode(itemId, userCode, false, url);
                }
            }

            //displayMessage(msg);
        },
        function (retError) {
            var msg = "";

            if (retError.args !== undefined) {
                if (retError.args.v[0].v === "EOF in multi-line string") {
                    msg = "ERROR: There is an open multi-line comment.";
                }
                else {
                    msg = retError.toString().replace("^", "").trim();
                }
            }

            if (itemType == "exercise") {
                saveExerciseCode(itemId, userCode, false, url);
            }
            addPopup(msg, "error");

            //displayMessage(msg);
        }
    );
}

function toggleCurrentStep(idToRemove, idToAdd) {
    // enable next button
    var btnToGetCurrent = document.getElementById(idToAdd);
    btnToGetCurrent.classList.remove("disabled");
    btnToGetCurrent.classList.add("currentStep");
    // remove focus from run
    var btnToLoseCurrent = document.getElementById(idToRemove);
    btnToLoseCurrent.classList.remove("currentStep");
}

/**
 * Parses the Skulpt array of test results into a more usable array. 
 * @param {*} ret The array returned from Skulpt when tests were present. 
 */
function parseTestResults(ret) {
    var testResults = [];

    if (ret.length > 0) {
        for (var i = 0; i < ret.length; i++) {
            testResults[i] = {
                success: !!+ret[i].v[0].v,
                message: ret[i].v[1].v
            };
        }
    }

    return testResults;
}

/**
 * Displays a string to the element with the id "alerts". Can erase any messages by calling without any parameters.
 * @param {*} errorMsg The message to display in the "alerts" element.
 */
function displayMessage(msg = "") {
    var output = document.getElementById("alerts");

    if (output != undefined) {
        output.innerHTML = msg;
    }
}

/**
 * Retrieves the CodeMirror editor instance from a parent element.
 * @param {*} parentNode The id of the element the CodeMirror is contained in.
 */
function getCodeMirrorByParentNode(parentNode) {
    var codeWindows = $(".CodeMirror");

    for (var i = 0; i < codeWindows.length; i++) {
        if (codeWindows[i].parentNode.id == parentNode) {
            return codeWindows[i].CodeMirror;
        }
    }
}

/**
 * Returns the contents of a CodeMirror editor with or without the Python test code.
 * @param {*} editor CodeMirror editor instance to get contents from .
 * @param {*} includeTestCode Include the Python code that defines the various tests to run the code against.
 */
function getCodeFromEditor(editor, includeTestCode = false) {
    var codeToRun = "";

    if (includeTestCode) {
        codeToRun += document.getElementById("pythonTestCode").innerText.trim() + "\n";
    }

    if (editor != undefined) {
        codeToRun += (editor.getValue() + "\n");
    }

    return codeToRun;
}

/**
 * Compiles the Python code from the various sources and sends it to Skulpt to be ran.
 */
function run() {
    // Get Python code to run from the various CodeMirror editors that exist
    var codeToRun = "";
    var codeEditor = getCodeMirrorByParentNode("ideCodeWindow");

    if (hasPreCode) {
        var preCodeEditor = getCodeMirrorByParentNode("idePreCode");
        codeToRun += getCodeFromEditor(preCodeEditor);
    }

    codeToRun += getCodeFromEditor(codeEditor);

    if (hasTestCode) {
        var testCodeEditor = getCodeMirrorByParentNode("ideTestCode");
        codeToRun += getCodeFromEditor(testCodeEditor, true);
    }

    // Reset text output
    var outputArea = document.getElementById("output");
    outputArea.innerHTML = "";

    // Reset graphics output
    var canvasArea = document.getElementsByTagName("canvas");
    if (canvasArea.length > 0) {
        for (var i = 0; i < canvasArea.length; i++) {
            canvasArea[i].classList.add("hidden");
        }
    }

    // Reset alert messages output
    //displayMessage();

    // Configure Skulpt and run code
    configSkulpt(outputArea.id, "mycanvas");
    runCode(codeToRun, outputArea, codeEditor.getValue());
}

/**
 * 
 * @param {*} exercise_id The id of the exercise being attempted.
 * @param {*} contents  The code the user entered.
 * @param {*} success Indicates whether the exercise was completed correctly or not.
 * @param {*} url  The url of the route to save the exercise to the database.
 */
function saveExerciseCode(exercise_id, contents, success, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { contents: contents, exercise_id: exercise_id, success: success, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            addPopup("Code saved!", "save")
        }
    });
}

//TODO: Can these two functions be combined? 
function saveProjectCode(project_id, contents, url) {
    $.ajax({
        type: "POST",
        url: url,
        data: { contents: contents, project_id: project_id, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            addPopup("Project Code saved!", "save")
        }
    });
}

/**
 * Display a popup message over the IDE to the user.
 * @param {string} msg message to give the user
 * @param {string} className additional classname(s) to add to the popup.
 */
function addPopup(msg, className) {
    var popHolder = document.getElementById("popups");
    var popUp = document.createElement("p");
    popUp.innerHTML = msg;
    popUp.className = "popup " + className;
    popHolder.appendChild(popUp);
    var logBook = document.getElementById("ideLog");
    var newEntryTitle = document.createElement("dt");
    newEntryTitle.innerText = (new Date()).toLocaleTimeString();
    var newEntry = document.createElement("dd");
    newEntry.innerHTML = msg;
    newEntry.className = className;
    logBook.appendChild(newEntryTitle);
    logBook.appendChild(newEntry);
}

/**
 * Replaces the contents of a CodeMirror editor to the passed-in string.
 * @param {*} parentNode The id of the element the CodeMirror is contained in.
 * @param {*} text The text to change the contents of the CodeMirror editor to.
 */
function replaceEditorText(parentNode, text) {
    var editor = $("#" + parentNode + " .CodeMirror")[0];
    editor.CodeMirror.setValue(decodeURI(text));
}

/**
 * Get the link from data attributes from a given button
 * @param string btnId the identifier for the button to get the link from
 * @returns url from button's data attributes.
 */
function getLinkFromButton(btnId) {
    var url = "";
    var btn = document.getElementById(btnId);
    if (btn != null) {
        url = btn.getAttribute("data-url");
    }
    
    return url
}

function toggleAddExerciseVisibility() {
    if($('#addExerciseBtn').hasClass('hidden')){
        $('#addExerciseBtn').removeClass('hidden');
    } else {
        $('#addExerciseBtn').addClass('hidden');
    }
}

/**
 * Turns edit mode on so an exercise or projects details can be edited.
 * @param {*} editBtn 
 * @param {*} itemType 
 * @param {*} url 
 */
function toggleEditMode(editBtn, itemType, url) {
    if(itemType == "exercise"){
        toggleAddExerciseVisibility();

        toggleDivVisibility('#ideTestCode');
    }

    // Change the contentEditable attribute of all elements with the editable class
    toggleEditableElements();

    // Make prompt editable
    togglePromptText($('#promptInstructions'));

    // Toggle pre code div visibility
    toggleDivVisibility('#idePreCode');

    // Toggle dates div visibility
    toggleDivVisibility('#ideProjectDates');

    // Toggle team settings div visibility
    toggleDivVisibility('#ideTeamsSetting');

    // If edit mode is being turned off, save to database through AJAX call
    if($(editBtn).text() == "Turn Off Edit Mode"){
        if(itemType == "project"){
            saveProjectEdit(url);
        } else if(itemType == "exercise"){
            saveExerciseEdit(url);
        } 
    }

    // Change edit button text
    toggleButtonText(editBtn);
}

/**
 * Toggles the contentEditable attribute of any elements with the editable class.
 */
function toggleEditableElements() {
    if($('.editable').attr('contentEditable') == 'true'){
        $('.editable').attr('contentEditable', 'false');
    } else {
        $('.editable').attr('contentEditable', 'true');
    }

    // Add styling to the elements when edit mode is on
    $('.editable').toggleClass('edit-on');
}

/**
 * Toggles the text on the button that turns edit mode on.
 * @param {*} editBtn 
 */
function toggleButtonText(editBtn) {
    if($(editBtn).text() == "Enable Edit Mode"){
        $(editBtn).text("Turn Off Edit Mode");
    } else {
        $(editBtn).text("Enable Edit Mode");
    }
}

/**
 * Toggles the prompt between its raw html and its html parsed.
 * @param {*} promptSection 
 */
function togglePromptText(promptSection) {
    if($(promptSection).hasClass("edit-on")){
        // Change the text shown for the prompt to its raw html
        $(promptSection).text($(promptSection).data("raw-prompt"));
    } else {
        // Change the text shown for the prompt to its text with the html parsed and update its data attribute with the new raw html
        $(promptSection).data("raw-prompt", $(promptSection).text());
        $(promptSection).html($(promptSection).text());
    }
}

/**
 * Toggles a div to be shown or hidden.
 * @param {*} divName 
 */
function toggleDivVisibility(divName) {
    var myDiv = $(divName);
    if($(myDiv).hasClass('hidden')){
        // Make div visible
        $(myDiv).removeClass('hidden');
    } else {
        // Make div hidden
        $(myDiv).addClass('hidden');
    }
}

/**
 * Makes an AJAX call with a projects details to save to the database.
 * @param {*} url 
 */
function saveProjectEdit(url) {
    var project_id = $('#projectId').text();
    var name = $('#projectName').text();
    var prompt = $('#promptInstructions').data("raw-prompt");
    var pre_code = $('#idePreCode').find('.CodeMirror')[0].CodeMirror.getValue();
    var open_date = $('#projectOpenDate').val();
    var close_date = $('#projectCloseDate').val();
    var teams_enabled = $("#projectTeamsSetting").is(':checked');

    $.ajax({
        type: "POST",
        url: url,
        data: {
            project_id: project_id,
            name: name,
            prompt: prompt,
            pre_code: pre_code,
            open_date: open_date,
            close_date: close_date,
            teams_enabled: teams_enabled,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            addPopup("Project Edited Successfully!", "save");
            console.log(data);
        }
    });
}

/**
 * Makes an AJAX call with an exercises details to save to the database.
 * @param {*} url 
 */
function saveExerciseEdit(url) {
    var exercise_id = $('#exerciseId').text();
    var prompt = $('#promptInstructions').data("raw-prompt");
    var pre_code = $('#idePreCode').find('.CodeMirror')[0].CodeMirror.getValue();
    var test_code = $('#ideTestCode').find('.CodeMirror')[0].CodeMirror.getValue();
    
    $.ajax({
        type: "POST",
        url: url,
        data: {
            exercise_id: exercise_id,
            prompt: prompt,
            pre_code: pre_code,
            test_code: test_code,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            addPopup("Exercise Edited Successfully!", "save");
            console.log(data);
        }
    });
}

function createProjectSurveyResponse(difficultyRating, enjoymentRating) {
    var url = $('#projectRatings').data('survey-response-create-url');
    var project_id = $('#projectId').text();

    // Validate response amounts are between 0 and 9 before making AJAX call
    if((difficultyRating >= 0 && difficultyRating <= 9) && (enjoymentRating >= 0 && enjoymentRating <= 9)){
        $.ajax({
            type: "POST",
            url: url,
            data: {
                project_id: project_id,
                difficulty_rating: difficultyRating,
                enjoyment_rating: enjoymentRating,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                addPopup("Project survey answered successfully!", "save")
                console.log(data);
            }
        });
    }
}
