/* Theme name prepend to localstorage key*/
const themeName = "ki-admin-template";

/* Get item in local store */
function setLocalStorageItem(key,value){
    localStorage.setItem(`${themeName}-${key}`, value);
}

$(document).on('click','#darkDemoBtn',function () {
    setLocalStorageItem("theme-mode", 'dark');
    window.location.href ='index'
})


$(function() {
    $(window).on("scroll", function() {
        if($(window).scrollTop() > 0) {
            $(".landing-navbar").addClass("landing-navbar-active");
            $(".landing-navbar-container.container").addClass("container-fluid");
        } else {
            $(".landing-navbar").removeClass("landing-navbar-active");
            $(".landing-navbar-container.container").removeClass("container-fluid");
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const lists = document.querySelectorAll(".features-box-list"); // Select all elements with this class

    lists.forEach((list) => {
        const items = [...list.children]; // Convert NodeList to an array

        // Clone items dynamically for infinite effect
        items.forEach((item) => {
            let clone = item.cloneNode(true);
            list.appendChild(clone);
        });
    });
});

//  Highlight Text Animation js

document.addEventListener("DOMContentLoaded", function () {
    const highlightText = document.getElementById("highlight-text");
    const words = ["Management", "Analytics", "Performance", "Dashboard", "Insights"];
    let index = 0;

    function changeText() {
        highlightText.innerHTML = `${words[index]} `;
        highlightText.classList.add("animate");

        setTimeout(() => {
            highlightText.classList.remove("animate");
        }, 1500);

        index = (index + 1) % words.length;
    }

    setInterval(changeText, 2000);
});


// tooltip js
"use strict";
$(function() {
    var tooltip_init = {
        init: function () {
            $("a").tooltip();
        }
    };
    tooltip_init.init()
});
