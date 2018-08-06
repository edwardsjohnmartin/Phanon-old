var turnScrollingOff = true; // Chrome requires this here.; for now turning off all scrolling.

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

/**
 * Set contentController Events and handle bubbled events.
 * @param containerId parent element that contains all elements.
 * @param collapsingChildSelector css selector based as child of containterId 
 * that will be the element that collapses and expands.
 * @param success event that will fire if collaps was successful.
 */
function handleContentControllers(containerId, collapsingChildSelector, scrollToParent = false, success = undefined) {
    var wasAction = false;
    $(containerId).click(function (e) {
        e = e || window.event;
        var t = e.target || e.srcElement;
        if (t.tagName === "BUTTON") {
            var itemId = t.getAttribute("data-item-id");
            if (itemId != undefined && t.classList.contains("edit")) {
                showEditForm(t);
            } else {
                // had to name these expander and collapser because of BootStrap
                expandCollapse(t, collapsingChildSelector, scrollToParent, success);
            }
        }
    });
    return wasAction;
}

function expandCollapse(ele, collapsable, scrollToParent, success) {
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
    if (turnScrollingOff != undefined) {
        scrollToParent = !turnScrollingOff;
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

function handleToggleSwitches(selector, yesText, noText) {
    buildYesNoToggle(selector, yesText, noText);
    $("div.yesNo span").click(function () {
        //$(this).css("border", "2px dashed #0A0");
        var chk = $(this).parent().parent().find("input").eq(0);
        $(this).parent().find("span").removeClass("selected");
        $(this).addClass("selected");
        if (this.classList.contains("spanShowYes")) {
            chk.attr("checked", "checked");
        } else {
            chk.attr("checked", null);
        }
        setToggleSelection(chk);
    });
}
function buildYesNoToggle(selector, yesText = "Yes", noText = "No") {
    //$(selector).css("display", "none");
    $(selector).each(function (indexI, chk) {
        var box = $(chk);
        var storageKey = box[0].id || selector + "_" + indexI;
        box.attr("data-storage-key", storageKey);
        var yesClass, noClass;

        var isSelected = getToggleSelection(box)
        if (isSelected) {
            box.attr("checked", "checked");
            yesClass = "spanShowYes selected";
            noClass = "spanShowNo";
        } else {
            box.attr("checked", null);
            yesClass = "spanShowYes";
            noClass = "spanShowNo selected";
        }
        $('<div id="divYesNo" class="yesNo">'
            + '<span class="' + yesClass + '">' + yesText + '</span>'
            + '<span class="' + noClass + '">' + noText + '</span>'
            + '</div>').insertAfter(box);
    });
}

/**
 * Display a popup message over the IDE to the user.
 * @param {string} msg message to give the user
 * @param {string} className additional classname(s) to add to the popup.
 */
function addPopup(msg, className) {
    var showPopups = localStorage.getItem("popupsToggle");
    if (showPopups == "true") {
        // add to popup
        var popHolder = document.getElementById("popups");
        var popUp = document.createElement("p");
        popUp.innerHTML = msg;
        popUp.className = "popup " + className;
        popHolder.appendChild(popUp);
    }
    // append to Log
    var logBook = document.getElementById("ideLog");
    if (logBook != undefined) {
        var newEntryTitle = document.createElement("dt");
        newEntryTitle.innerText = (new Date()).toLocaleTimeString();
        var newEntry = document.createElement("dd");
        newEntry.innerHTML = msg;
        newEntry.className = className;
        logBook.appendChild(newEntryTitle);
        logBook.appendChild(newEntry);
    }
}

function getToggleSelection(jEle) {
    var storageKey = jEle.attr("data-storage-key");
    var isSelected = jEle.is(":checked");
    if (localStorage.getItem(storageKey) === null) {
        // does not exist in storage
        // store value in local storage.
        localStorage.setItem(storageKey, isSelected);
    } 
    isSelected = localStorage.getItem(storageKey);
    
    return isSelected == "true";
}

function setToggleSelection(jEle) {
    var storageKey = jEle.attr("data-storage-key");
    var isSelected = jEle.is(":checked");
    localStorage.setItem(storageKey, isSelected);
}
