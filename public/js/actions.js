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
function handleContentControllers(containerId, collapsingChildSelector, successHandler) {
    var wasAction = false;
    $(containerId).click(function (e) {
        e = e || window.event;
        var target = e.target || e.srcElement;
        if (target.tagName === "BUTTON") {
            var itemId = target.getAttribute("data-item-id");
            if (itemId && target.classList.contains("edit")) {
                showEditForm(target);
            } else {
                // had to name these expander and collapser because of BootStrap
                expandCollapse(target, collapsingChildSelector, successHandler);
            }
        }
    });
    return wasAction;
}

function expandCollapse(el, collapsable, successHandler) {
    wasAction = true;
    var t = $(el);
    if (t.hasClass("expander")) {
        // only handle content Controls
        t.removeClass("expander").addClass("collapser");
    } else if (t.hasClass("collapser")) {
        t.removeClass("collapser").addClass("expander");
    } else {
        wasAction = false;
    }

    if (wasAction) {
        t.parent().find(collapsable).animate({ height: "toggle" }, 200);
        if (event && successHandler) {
          successHandler();
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
    if (logBook) {
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
