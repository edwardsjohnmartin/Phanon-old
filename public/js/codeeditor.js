/**
 * Turns a normal textarea element into a CodeMirror editor.
 * @param {*} textarea The id of the textarea element to be turned into a CodeMirror editor.
 */
function makeCodeMirror(textarea){
    var myTextArea = document.getElementById(textarea);

    var myCodeMirror = CodeMirror(function(elt) {
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
function makeRunButton(button){
    document.getElementById(button).onclick = function(){
        run();
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
function configSkulpt(textOutput, graphicsOutput){
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
function runCode(codeToRun, outputArea, userCode = ""){
    if(codeToRun.length == 0){
        codeToRun = " ";
    }

    var myPromise = Sk.misceval.asyncToPromise(function () {
        return Sk.importMainWithBody("<stdin>", false, codeToRun, true);
    });

    var runBtn = document.getElementById("btnRunCode");
    var itemType = runBtn.attributes["data-item-type"].value;
    var itemId = runBtn.attributes["data-item-id"].value;
    var url = runBtn.attributes["data-save-url"].value;

    myPromise.then(
        function (retSuccess) {
            // This will run the Python code using the specified function in the Python code of the python.py file
            var ret = Sk.misceval.callsim(
                retSuccess.tp$getattr('__TEST'), 
                Sk.builtin.str(userCode), 
                Sk.builtin.str(outputArea.innerText)
            );

            var msg = "";

            var testResults = parseTestResults(ret.v);
            for(var i = 0; i < testResults.length; i++){
                if(!testResults[i].success){
                    msg = testResults[i].message;
                    break;
                }
            }

            if(itemType == "exercise"){
                if(msg == ""){
                    msg = "Correct! Well done.";
                    saveExerciseCode(itemId, userCode, true, url);
                } else {
                    saveExerciseCode(itemId, userCode, false, url);
                }
            }

            displayMessage(msg);
        },
        function (retError) {
            var msg = "";

            if (retError.args !== undefined) {
                if (retError.args.v[0].v === "EOF in multi-line string") {
                    msg = "ERROR: There is an open multi-line comment." + "\n";
                }
                else {
                    msg = retError.toString() + "\n";
                }
            }

            if(itemType == "exercise"){
                saveExerciseCode(itemId, userCode, false, url);
            }

            displayMessage(msg);
        }
    );
}

/**
 * Parses the Skulpt array of test results into a more usable array. 
 * @param {*} ret The array returned from Skulpt when tests were present. 
 */
function parseTestResults(ret){
    var testResults = [];

    if(ret.length > 0){
        for(var i = 0; i < ret.length; i++){
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
function displayMessage(msg = ""){
    var output = document.getElementById("alerts");

    if(output != undefined){
        output.innerText = msg;
    }
}

/**
 * Retrieves the CodeMirror editor instance from a parent element.
 * @param {*} parentNode The id of the element the CodeMirror is contained in.
 */
function getCodeMirrorByParentNode(parentNode){
    var codeWindows = $(".CodeMirror");

    for(var i = 0; i < codeWindows.length; i++){
        if(codeWindows[i].parentNode.id == parentNode){
            return codeWindows[i].CodeMirror;
        }
    }
}

/**
 * Returns the contents of a CodeMirror editor with or without the Python test code.
 * @param {*} editor CodeMirror editor instance to get contents from .
 * @param {*} includeTestCode Include the Python code that defines the various tests to run the code against.
 */
function getCodeFromEditor(editor, includeTestCode = false){
    var codeToRun = "";

    if(includeTestCode){
        codeToRun += document.getElementById("pythonTestCode").innerText.trim() + "\n";
    }

    if(editor != undefined){
        codeToRun += (editor.getValue() + "\n");
    }

    return codeToRun;
}

/**
 * Compiles the Python code from the various sources and sends it to Skulpt to be ran.
 */
function run(){
    var codeEditor = getCodeMirrorByParentNode("ideCodeWindow");
    var preCodeEditor = getCodeMirrorByParentNode("idePreCode");
    var testCodeEditor = getCodeMirrorByParentNode("ideTestCode");
    
    // Get Python code to run from the various CodeMirror editors
    var codeToRun = "";
    codeToRun += getCodeFromEditor(preCodeEditor);
    codeToRun += getCodeFromEditor(codeEditor);
    codeToRun += getCodeFromEditor(testCodeEditor, true);

    // Reset text output
    var outputArea = document.getElementById("output");
    outputArea.innerHTML = "";

    // Reset graphics output
    var canvasArea = document.getElementsByTagName("canvas");
    if(canvasArea.length > 0){
        for(var i = 0; i < canvasArea.length; i++){
            canvasArea[i].classList.add("hidden");
        }
    }

    // Reset alert messages output
    displayMessage();
    
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
function saveExerciseCode(exercise_id, contents, success, url){
    $.ajax({
        type: "GET",
        dataType: "json",
        url: url,
        data: { contents: contents, exercise_id: exercise_id, success: success },
        success: function (data) {
            console.log(data);
        }
    });
}