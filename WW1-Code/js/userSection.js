window.onload = function() {
    var desc = document.getElementById("description");
    var sec1 = document.getElementById("section1");
    sec1.onmouseover = function() {
        desc.innerHTML = "Bradford and surrounding townships"; // Hovering replaces the description box
    }
    sec1.onclick = function() {
        sec1.style.display = "none"; // Clicking section 1 deletes the icon
    }
    var sec2 = document.getElementById("section2");
    sec2.onmouseover = function() {
        desc.innerHTML = "Names recorded on Bradford Memorials";
    }
    sec2.onclick = function() {
        alert("Clicked section 2"); // Clicking other sections gives a pop-up
    }
    var sec3 = document.getElementById("section3");
    sec3.onmouseover = function() {
        desc.innerHTML = "Buried in Bradford";
    }
    sec3.onclick = function() {
        alert("Clicked section 3");
    }
    var sec4 = document.getElementById("section4");
    sec4.onmouseover = function() {
        desc.innerHTML = "Newspaper references";
    }
    sec4.onclick = function() {
        alert("Clicked section 4");
    }
    var sec5 = document.getElementById("section5");
    sec5.onmouseover = function() {
        desc.innerHTML = "Biographies";
    }
    sec5.onclick = function() {
        alert("Clicked section 5");
    }
}