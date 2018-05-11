// function editor(id){
//     CodeMirror.fromTextArea(id, {
//         lineNumbers: true,
//         cursorBlinkRate: 0,
//         autoCloseBrackets: true,
//         tabSize: 4,
//         indentUnit: 4,
//         matchBrackets: true 
//     });
// }

// var myCodeMirror = CodeMirror.fromTextArea(document.getElementById("code"),{
//     lineNumbers: true,
//     cursorBlinkRate: 0,
//     autoCloseBrackets: true,
//     tabSize: 4,
//     indentUnit: 4,
//     matchBrackets: true,
//     autofocus: true,
//     extraKeys: {
//         "Ctrl-M": function(){
//             console.log("shortcut");
//         }
//     }
//   }
// );

document.getElementById("runButton").onclick = function () {
    run();
};

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
    var codeToRun = myCodeMirror.getValue();

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