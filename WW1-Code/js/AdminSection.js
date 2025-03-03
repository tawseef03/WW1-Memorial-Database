window.onload = function() {
    var desc = document.getElementById("description");
    var sec1 = document.getElementById("section1");
    var sec2 = document.getElementById("section2");
    var sec3 = document.getElementById("section3");
    var sec4 = document.getElementById("section4");
    var sec5 = document.getElementById("section5");
    
    sec1.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec1").innerHTML; // Hovering replaces the description box
        desc.style.backgroundImage = "url('../rsc/township.jpg')";
    }
    sec1.onclick = function() {
        window.location.href = "admin/editSection1.html"; // Redirect to admin edit page for section 1
    }

    
    sec2.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec2").innerHTML;
        desc.style.backgroundImage = "url('../rsc/memorial.jpg')";
    }
    sec2.onclick = function() {
        window.location.href = "admin/editSection2.html"; // Redirect to admin edit page for section 2
    }

    
    sec3.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec3").innerHTML;
        desc.style.backgroundImage = "url('../rsc/buried.jpg')";
    }
    sec3.onclick = function() {
        window.location.href = "admin/editSection3.html"; // Redirect to admin edit page for section 3
    }

    
    sec4.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        desc.style.backgroundImage = "url('../rsc/newspaper.jpeg')";
    }
    sec4.onclick = function() {
        window.location.href = "admin/editSection4.html"; // Redirect to admin edit page for section 4
    }

    
    sec5.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        desc.style.backgroundImage = "url('../rsc/biography.jpg')";
    }
    sec5.onclick = function() {
        window.location.href = "admin/editSection5.html"; // Redirect to admin edit page for section 5
    }
}