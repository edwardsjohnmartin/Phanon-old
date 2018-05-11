// var myCodeMirror = CodeMirror.fromTextArea(document.getElementById("code"),{
//     smartIndent: true,
//     lineNumbers: true,
//     cursorBlinkRate: 0,
//     autoCloseBrackets: true,
//     tabSize: 4,
//     indentUnit: 4,
//     matchBrackets: true,
//     autofocus: true
// });

function makeCodeMirror (editorEl){
    CodeMirror.fromTextArea(editorEl, {
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true 
    });
}

function makeClassCodeMirror(sel){
    return Array.apply(null, document.querySelectorAll(sel));
}

function makeRunButton(btn_id){
    document.getElementById(btn_id).onclick = function () {
        run();
    };
}

function outf(text){
    var mypre = document.getElementById("output");
    mypre.innerHTML = mypre.innerHTML + text;
}

function builtinRead(x) {
    if (Sk.builtinFiles === undefined || Sk.builtinFiles["files"][x] === undefined)
        throw "File not found: '" + x + "'";
    return Sk.builtinFiles["files"][x];
}

function run(){
    //var codeToRun = myCodeMirror.getValue();

    //This will only get the first instance of CodeMirror
    //Code found at
    //https://stackoverflow.com/questions/11581516/get-codemirror-instance?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
    var editor = $('.CodeMirror')[0].CodeMirror;

    var codeToRun = editor.getValue();

    var outputArea = document.getElementById("output");
    outputArea.innerHTML = "";

    Sk.pre = "output";
    Sk.configure({
        output: outf, read: builtinRead
    });

    (Sk.TurtleGraphics || (Sk.TurtleGraphics = {})).target = 'mycanvas';
    var myPromise = Sk.misceval.asyncToPromise(function () {
       return Sk.importMainWithBody("<stdin>", false, codeToRun, true);
    });

    myPromise.then(function(mod) {
        console.log('success');
    },
        function(err) {
        console.log(err.toString());
    });
}