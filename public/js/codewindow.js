function makeCodeMirror(editorEl) {
    CodeMirror.fromTextArea(editorEl, {
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true
    });
}

function makeClassCodeMirror(sel) {
    return Array.apply(null, document.querySelectorAll(sel));
}

function makeRunButton(btn_id) {
    document.getElementById(btn_id).onclick = function () {
        run();
    };
}

// Must copy the prompt string for some reason
function inf(prompt) {
    return window.prompt(String(prompt));
}

function outf(text) {
    if (text && typeof text !== "undefined") {
        text = text.replace("<", "&lt;").replace(">", "&gt;");
        var mypre = document.getElementById("output");
        mypre.innerHTML = mypre.innerHTML + text;
    }
}

function builtinRead(x) {
    if (Sk.builtinFiles === undefined || Sk.builtinFiles["files"][x] === undefined)
        throw "File not found: '" + x + "'";
    return Sk.builtinFiles["files"][x];
}

function run() {
    //fixed this to be dynamic.
    // the biggest problem is that it expected the code mirror windows
    // to be in a set order, this will not always be true.
    //Code found at
    //https://stackoverflow.com/questions/11581516/get-codemirror-instance?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa

    var editor = getEditor('#ideCodeWindow',"");
    // check to see if we have pre and test code 
    // Sandbox will not have these.
    var pre_code_editor = getEditor('#idePreCode', "#pre_code");
    var test_code_editor = getEditor('#ideTestCode', '#test_code');
    
    // Create a string that will store all parts to pass to Skulpt to compile the Python code
    var codeToRun = "";

    // Pre code
    if (pre_code_editor) {
        codeToRun += pre_code_editor.getValue().trim() + "\n";
    }

    // User typed code
    codeToRun += editor.getValue();

    if (test_code_editor) {
        // Python code (from python.py)
        codeToRun += document.getElementById("pythonTestCode").innerText.trim() + "\n";
        // Test code
        codeToRun += test_code_editor.getValue().trim();
    }

    var outputArea = document.getElementById("output");
    outputArea.innerHTML = "";

    Sk.pre = "output";
    Sk.configure({
        output: outf,
        read: builtinRead,
        inputfun: inf,
        inputfunTakesPrompt: true
    });

    (Sk.TurtleGraphics || (Sk.TurtleGraphics = {})).target = 'mycanvas';

    var myPromise = Sk.misceval.asyncToPromise(function () {
        return Sk.importMainWithBody("<stdin>", false, codeToRun, true);
    });

    clearMessages();

    // This runs when there were no errors in the code
    myPromise.then(function (mod) {
        // This will run the Python code using the specified function in the Python code of the python.py file
        var run_method = mod.tp$getattr('__TEST');
        var ret = Sk.misceval.callsim(run_method, Sk.builtin.str(editor.getValue()), Sk.builtin.str(outputArea.innerText));

        // Create an array of objects that contain the results of each test
        var testMessages = makeTestMessagesArray(ret.v);
        //console.log(testMessages);

        printTestMessages(testMessages);
    },
        // This will print any Python errors that were in the code that was ran
        function (err) {
            printPythonErrors(err);
        }
    );
}

// Takes in the return from the Python tests and turns into a usable array
// Each element of the array will be an object with the following properties:
//     message: string - Contains the pass or fail message for a test.
//     passed: boolean - Indicates whether the given test was successful or not.
function makeTestMessagesArray(ret){
    var testMessages = [];

    if(ret.length > 0){
        for(var i = 0; i < ret.length; i++){
            testMessages.push({passed: ret[i].v[0].v, message: ret[i].v[1].v});

            // Converts the integer in ret to its boolean value
            testMessages[i].passed = !!+testMessages[i].passed;
        }
    }

    return testMessages;
}

// Takes in an array of TestMessage objects and outputs them to hard-coded elements
function printTestMessages(testMessages) {
    testEl = document.getElementById("test_output");
    testEl.parentNode.classList.add("showMessage");
    // Add each test's message to the element
    if (testMessages.length > 0) {
        for (var i = 0; i < testMessages.length; i++) {
            testEl.innerText += testMessages[i].message + "\n";
        }
    }
}

// Takes in the errors object returned from Skulpt when Python errors occurred and outputs them
function printPythonErrors(err) {
    // Gets the line the error occurred on
    // This could be used to offset the line number with the amount of lines of pre_code
    var line_num = Number(err.toString().split("on line", 2)[1]);
    
    //TODO: Figure out some way of correcting the line number
    // Different error types makes it so its not always the same pattern
    // Injecting "\n" into various parts of run() also increase complexity

    // var pre_code_editor = getEditor('#idePreCode', "#pre_code");
    // var preCode = pre_code_editor.getValue().trim();
    // var editor = getEditor('#ideCodeWindow',"");
    // var userCode = editor.getValue();
    // var pythonCode = document.getElementById("pythonTestCode").innerText.trim();
    // var test_code_editor = getEditor('#ideTestCode', '#test_code');
    // var testsCode = test_code_editor.getValue().trim();
    // var codeToRun = preCode + userCode + pythonCode + testsCode;

    // var preLines = preCode.split(/\r\n|\r|\n/).length;
    // var userLines = userCode.split(/\r\n|\r|\n/).length;
    // var testLines = testsCode.split(/\r\n|\r|\n/).length;

    // console.clear();
    // console.log("line_num: " + line_num);
    // console.log("precode lines: " + preCode.split(/\r\n|\r|\n/).length);
    // console.log("precode length: " + preCode.length);
    // console.log("usercode lines: " + userLines);
    // console.log("pythoncode lines: " + pythonCode.split(/\r\n|\r|\n/).length);
    // console.log("testscode lines: " + testsCode.split(/\r\n|\r|\n/).length);
    // console.log("total lines: " + codeToRun.split(/\r\n|\r|\n/).length);

    // console.log("line_num: " + line_num);
    // console.log("preLines: " + preLines);
    // console.log("userLines: " + userLines);
    // console.log("testLines: " + testLines);

    // var newline_num;

    // if(line_num - preLines <= userLines){
    //     newline_num = line_num - (preLines + 1);
    // }
    // else{
    //     newline_num = line_num - preLines - testLines;
    // }

    var msg = "";

    if (err.args !== undefined) {
        if (err.args.v[0].v === "EOF in multi-line string") {
            msg = "ERROR: There is an open multi-line comment." + "\n";
        }
        else{
            msg = err.toString() + "\n";
        }
    }

    errorEl = document.getElementById("error_output");
    errorEl.innerHTML = msg;
    errorEl.parentNode.classList.add("showMessage");
}

// Clears any text inside the elements used to show Python errors and test results
function clearMessages() {
    errorEl = document.getElementById("error_output");
    if(errorEl != "undefined" && errorEl != null){
        errorEl.innerHTML = "";
    }
    
    testEl = document.getElementById("test_output");
    if(testEl != "undefined" && testEl != null){
        testEl.innerHTML = "";
    }
}

// object to mimic CodeMirror for value
var valueGetterPrototype = { value: "", getValue: function () { return this.value; } };
/**
 * Get editor that contains text for each component.
 * @param {any} ideCodeName ID of html element that contains CodeMirror window.
 * @param {any} hiddenCodeName ID of html hidden element that contains code if CodeMirror window does not exist.
 */
function getEditor(ideCodeName, hiddenCodeName) {
    var editor = $(ideCodeName).find('.CodeMirror');
    // check if code mirror window exists
    editor = editor.length > 0 ? editor[0].CodeMirror : false;
    // if not, check for hidden field.
    if (!editor) {
        editor = $(hiddenCodeName);
        if (editor.length > 0) {
            // create an object to mimic code mirror window.
            var content = editor.val();
            editor = Object.create(valueGetterPrototype);
            editor.value = content;
        } else {
            editor = false;
        }
    }
    return editor;
}
