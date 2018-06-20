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
    
    // Create a string variable that has the code entered in the editor with the test_code
    // appended to the end of it
    var codeToRun = ""
    if (pre_code_editor) {
        codeToRun = pre_code_editor.getValue() + "\n";
    }
    codeToRun += editor.getValue() + "\n";
    if (test_code_editor) {
        codeToRun += document.getElementById("pythonTestCode").innerText + "\n" +
            test_code_editor.getValue();
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

    // This runs when there were no errors in the code
    myPromise.then(function (mod) {
        clearErrors();

        var run_method = mod.tp$getattr('__TEST');
        var ret = Sk.misceval.callsim(run_method, Sk.builtin.str(editor.getValue()), Sk.builtin.str(outputArea.innerText));

        if (ret.v.length > 0) {
            //print errors ( test errors?)
            for (var i = 0, l = ret.v.length; i < l; i++) {
                console.log(ret.v[i]);
                document.getElementById("test_output").innerText += ret.v[i].v + "\n";
            }
        }
    },

        // This runs when there were errors in the code
        function (err) {
            clearErrors();
            var line_num = Number(err.toString().split("on line", 2)[1]);
            if (err.args !== undefined) {
                if (err.args.v[0].v === "EOF in multi-line string") {
                    document.getElementById("error_output").innerHTML += "ERROR: It looks like you have an open multi-line comment." + "\n";
                }
                else {
                    document.getElementById("error_output").innerHTML += err.toString() + "\n";
                }
            }
            else {
                document.getElementById("error_output").innerHTML += err.toString() + "\n";
            }
        });
}

// Takes in an error message as a string and prints it to the "error_output" element
function printError(err_msg) {
    document.getElementById("error_output").innerHTML = err_msg;
}

// Clears any text inside the "error_output" element
function clearErrors() {
    document.getElementById("error_output").innerHTML = "";
    document.getElementById("test_output").innerHTML = "";
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
