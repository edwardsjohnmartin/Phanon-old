function actionVerify(e, action, url) {
    //e = e || window.event;
    //var clicked = e.target || e.srcElement;
    //$(clicked).parent().append("<p>Hello</p>");
    alert(action);
    var answer = confirm("Are you sure you want to " + action + "?");
    return answer;
}