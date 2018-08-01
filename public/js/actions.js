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

function expandCollapseAll(expand, collapsable) {

    if (expand) {

    } else {

    }
}


/**
 * Set contentController Events and handle bubbled events.
 * @param containerId parent element that contains all elements.
 * @param collapsingChildSelector css selector based as child of containterId 
 * that will be the element that collapses and expands.
 * @param success event that will fire if collaps was successful.
 */
function handleContentControllers(containerId, collapsingChildSelector, scrollToParent = false,success) {
    var wasAction = false;
    $(containerId).click(function (e) {
        e = e || window.event;
        var t = e.target || e.srcElement;
        if (t.tagName === "BUTTON") {
            // had to name these expander and collapser because of BootStrap
            expandCollapse(t,collapsingChildSelector,success);
        }
    });
    return wasAction;
}

function expandCollapse(ele,collapsable,success) {
    wasAction = true;
    var t = $(ele);
    if (t.hasClass("expander")) {
        // only handle content Controls
        t.removeClass("expander").addClass("collapser");
    } else if (t.hasClass("collapser")) {
        t.removeClass("collapser").addClass("expander");
    } else {
        // other buttons we are not handling here.
        wasAction = false;
    }
    if (wasAction) {
        t.parent().find(collapsable).animate({ height: "toggle" });
        if (scrollToParent) {
            $("html,body").animate({
                scrollTop: t.parent().offset().top - (parseInt($("body").css("padding-top")))
            }, 2000
            );

        }
        if (event != undefined) {
            if (success != undefined)
                success();
        }
    }
}