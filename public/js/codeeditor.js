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

function makeFormCodeMirror(textarea) {
    var myTextArea = document.getElementById(textarea);

    var myCodeMirror = CodeMirror.fromTextArea(myTextArea, {
        value: myTextArea.value,
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true
    });
}

/**
 * Updates the onclick event of the button element to run the Python code in a CodeMirror editor.
 * @param {*} button The id of the button element who's onclick will be used for the run function.
 */
function setRunButtonEvent(button) {
    document.getElementById(button).onclick = function () {
        // clear previous messages. 
        var logBook = document.getElementById("ideLog");
        logBook.innerText = ""; 
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
        addPopup("Code reset to starter code.", "reset");
    };
}


//TODO: this do not technically make the button; should consider renaming to a
//     more accurate name like - setSaveButtonEvents
/**
 * 
 * @param {*} buttonId 
 */
function makeSaveButton(buttonId) {
    var btnSave = document.getElementById(buttonId);
    var divName = getCurrentCodeWindowId();
    var itemType = btnSave.attributes["data-item-type"].value;
    var itemId = btnSave.attributes["data-item-id"].value;
    var url = btnSave.attributes["data-save-url"].value;

    btnSave.onclick = function () {
        var codeEditor = getCodeMirrorByParentNode(divName);

        if (itemType == "project") {
            saveProjectCode(itemId, codeEditor.getValue(), url)
        } else if (itemType == "exercise") {
            saveExerciseCode(itemId, codeEditor.getValue(), url, false);
        } else {
            addPopup("Save failed; type not known.", "error");
        }
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

                    saveExerciseCode(itemId, userCode, url, true);
                } else {
                    saveExerciseCode(itemId, userCode, url, false);
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
                saveExerciseCode(itemId, userCode, url, false);
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
    var divName = getCurrentCodeWindowId();

    // Get Python code to run from the various CodeMirror editors that exist
    var codeToRun = "";
    var codeEditor = getCodeMirrorByParentNode(divName);

    // Why are we expecting preexisting/global variables from codeWindow.blade.php?
    // This is dangerous.
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

function getCurrentCodeWindowId() {
    var divName = "ideCodeWindow"; // use codeWindow as default.

    // This will only be true when the user had edit permissions and tabs were created
    // It will run the code of the active tab
    var activeli = $('#ideCodeWindow li.nav-item.active');
    if (activeli.length > 0) {
        var alink = activeli.find('a');
        divName = alink.attr('href').substring(1);
    }

    return divName;
}

/**
 * 
 * @param {*} exercise_id The id of the exercise being attempted.
 * @param {*} contents  The code the user entered.
 * @param {*} success Indicates whether the exercise was completed correctly or not.
 * @param {*} url  The url of the route to save the exercise to the database.
 */
function saveExerciseCode(exercise_id, contents, url, success = false) {
    $.ajax({
        type: "POST",
        url: url,
        data: { contents: contents, exercise_id: exercise_id, success: success, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function (data) {
            addPopup("Code saved!", "save")
        },
        error: function () {
            addPopup("Error saving Code!", "save")
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
        },
        error: function () {
            addPopup("Error saving Code!", "error")
        }
    });
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
    if ($('#addExerciseBtn').hasClass('hidden')) {
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
    if (itemType == "exercise") {
        toggleAddExerciseVisibility();

        toggleDivVisibility('#ideTestCode');
    }
    if(itemType == "choice_exercise") {
        toggleDivVisibility('#btnAddChoice');

        var allChoiceSpans = $('#choices_div').find('span');
        $(allChoiceSpans).each(function (index, f) {
            toggleDivVisibility(f);
        });

        var allChoiceInputs = $('#choices_div').find('input:text');
        $(allChoiceInputs).each(function (index, f) {
            toggleDivVisibility(f);
        });
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
    if ($(editBtn).text() == "Turn Off Edit Mode") {
        if (itemType == "project") {
            saveProjectEdit(url);
        } else if (itemType == "exercise") {
            saveExerciseEdit(url);
        } else if (itemType == "choice_exercise") {
            saveChoiceExerciseEdit(url);

            for(var i = 0; i < allChoiceInputs.length; i++){
                allChoiceSpans[i].innerText = allChoiceInputs[i].value;
            }
        }
    }

    // Change edit button text
    toggleButtonText(editBtn);
}

/**
 * Toggles the contentEditable attribute of any elements with the editable class.
 */
function toggleEditableElements() {
    if ($('.editable').attr('contentEditable') == 'true') {
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
    var btn = $(editBtn);
    if (btn.text() == "Enable Edit Mode") {
        btn.text("Turn Off Edit Mode");
        btn.attr("title", "Turn Off Edit Mode and Save");
        btn.attr("tooltip", "Turn Off Edit Mode and Save");
        btn.addClass("selected");
        btn.removeClass("edit").addClass("save");
    } else {
        btn.text("Enable Edit Mode");
        btn.attr("title", "Enable Edit Mode");
        btn.attr("tooltip", "Enable Edit Mode");
        btn.removeClass("selected");
        btn.removeClass("save").addClass("edit");
    }
}

/**
 * Toggles the prompt between its raw html and its html parsed.
 * @param {*} promptSection 
 */
function togglePromptText(promptSection) {
    if ($(promptSection).hasClass("edit-on")) {
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
    if ($(myDiv).hasClass('hidden')) {
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
            addPopup("Project changes saved!", "save");
            console.log(data);
        },
        error: function (x,d,o,p) {
            addPopup("Could not save project", "error");
            console.log(x);
            console.log(d);
        }
    });
}

/**
 * Makes an AJAX call with an exercises details to save to the database.
 * @param {*} url 
 */
function saveExerciseEdit(url) {
    var exercise_id = $('#exerciseId').text();
    // cannot have null texts in these defaulting to empty string.
    var prompt = $('#promptInstructions').data("raw-prompt") || "";
    var pre_code = $('#idePreCode').find('.CodeMirror')[0].CodeMirror.getValue() || "";
    var test_code = $('#ideTestCode').find('.CodeMirror')[0].CodeMirror.getValue() || " ";
    var start_code = $('#startcode').find('.CodeMirror')[0].CodeMirror.getValue() || " ";
    var solution = $('#solution').find('.CodeMirror')[0].CodeMirror.getValue() || " ";

    $.ajax({
        type: "POST",
        url: url,
        data: {
            exercise_id: exercise_id,
            prompt: prompt,
            pre_code: pre_code,
            test_code: test_code,
            start_code: start_code,
            solution: solution,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            addPopup("Exercise changes saved!", "save");
        },
        error: function (x, d, o, p) {
            console.log(x);
            console.log(d);
            addPopup("Could not save exercise", "error");
        }
    });
}

function createProjectSurveyResponse(difficultyRating, enjoymentRating, maxDifficulty = 9, maxEnjoyment = 9) {
    var url = $('#projectRatings').data('survey-response-create-url');
    var project_id = $('#projectId').text();

    // Validate response amounts are between 0 and 9 before making AJAX call
    if ((difficultyRating >= 0 && difficultyRating <= maxDifficulty)
        && (enjoymentRating >= 0 && enjoymentRating <= maxEnjoyment)) {
        $.ajax({
            type: "POST",
            url: url,
            cache: false,
            data: {
                project_id: project_id,
                difficulty_rating: difficultyRating,
                enjoyment_rating: enjoymentRating,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                var i;
                for (i = 0; i < data.successes.length; i++) {
                    addPopup(data.successes[i], "save");
                }
                for (i = 0; i < data.errors.length; i++) {
                    addPopup(data.errors[i], "error");
                }
                //console.log(data);
            },
            error: function (data) {

                addPopup("Could not send ratings to server.", "error");
                //console.log(data);
            }
        });
    }
}

function addNewExerciseToLesson(url, associatedID, exerciseId = -1) {
    // Get lesson id of exercise
    var lessonId = $("#exerciseList").data("lesson-id");
    var number = $("#" + associatedID).attr("data-item-count");
    var type;

    if(associatedID == "addExercise"){
        type = "code";
    } else if(associatedID == "addChoiceExercise"){
        type = "choice";
    } else if(associatedID == "addScaleExercise"){
        type = "scale";
    }

    $.ajax({
        type: "POST",
        url: url,
        data: {
            lesson_id: lessonId,
            exercise_id: exerciseId,
            exercise_count: number,
            type: type,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            var o = $("#" + associatedID);
            var count = parseInt(o.attr("data-item-count"));
            count++; // increment before setting

            if (parseInt(exerciseId) === -1) {
                // Add tile icon for newly created exercise to end.
                o.before(data);
                o.attr("data-item-count", count);
            } else {
                // add to next place in line
                o.after(data);
                // shift all in list.
                shiftExerciseNumbers(o,count);
            }
        }
    });
}

function copyItem(itemType, url, itemId) {
    addNewExerciseToLesson(url, itemType + "_" + itemId, itemId);
}

function insertItem(itemType, url, itemId) {
    addNewExerciseToLesson(url, itemType + "_" + itemId, itemId);
}

/**
 * 
 * @param {any} sEle starting element
 */
function shiftExerciseNumbers(sEle,count,includesEle = false) {
    if (!(sEle instanceof jQuery || 'jquery' in Object(sEle)))
        sEle = $(sEle); // make sure is jQuery Object

    var nn = sEle.nextAll();
    if (includesEle) {
        nn.splice(0, 0, sEle);
    } 
    nn.each(function (i, obj) {
        // should include newly added item.
        var n = $(obj);
        n.attr("data-item-count", count);
        if (!n.hasClass("addNew")) {
            n.find("a").text(count);
        }
        count++; // advance for next item.
    });
}

function saveChoiceExerciseEdit(url) {
    var exercise_id = $('#exerciseId').text();
    var prompt = $('#promptInstructions').data("raw-prompt") || "";

    var choices = [];
    var allChoiceInputs = $('#choices_div').find('input:text');
    $(allChoiceInputs).each(function (index, f) {
        choices.push(f.value);
    });

    var solution = $('#choices_div').find('input[name=choice_selection]:checked').val();

    $.ajax({
        type: "POST",
        url: url,
        data: {
            exercise_id: exercise_id,
            prompt: prompt,
            choices: choices,
            solution: solution,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
        }
    });
}
