/** 
 * Verify that action is what is intended. Proper use for this funciton 
 *      is /return action(eventObj, stringDescribingAction, URIToCallOnSuccess)
 * @param {string} action What text you intend to do.
 * @param {event} e event object for click event.
 * @param {string} url URI that is meant to be called on success. 
 *      This will be used for AJAX calls when this part gets implemented.
 * 
 * @return {boolean} True if the action should happen, false if not.
 *      Make sure to return the returned value of this method to make
 *      sure that the event is cancelled when needed.
 */
function actionVerify(action, e, url) {
    //e = e || window.event;
    //var clicked = e.target || e.srcElement;
    //$(clicked).parent().append("<p>Hello</p>");
    alert(action);
    var answer = confirm("Are you sure you want to " + action + "?");
    return answer;
}