window.onload = function() {
    var desc = document.getElementById("description");
    var img = document.getElementById("bgimg");
    var sec1 = document.getElementById("section1");
    var sec2 = document.getElementById("section2");
    var sec3 = document.getElementById("section3");
    var sec4 = document.getElementById("section4");
    var sec5 = document.getElementById("section5");
    var ovly = document.getElementById("overlay");
    
    sec1.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec1").innerHTML; // Hovering replaces the description box
        img.style.backgroundImage = "url('../rsc/township.jpg')";
    }
    sec1.onclick = function() {
        alert("Clicked section 1"); // Clicking section 1 deletes the icon
    }

    
    sec2.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec2").innerHTML;
        img.style.backgroundImage = "url('../rsc/memorial.jpg')";
    }
    sec2.onclick = function() {
        window.location.href = 'memorial.php'; // Redirect to memorial.php
    }

    
    sec3.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec3").innerHTML;
        img.style.backgroundImage = "url('../rsc/buried.jpg')";
    }
    sec3.onclick = function() {
        alert("Clicked section 3");
    }

    
    sec4.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        img.style.backgroundImage = "url('../rsc/newspaper.jpeg')";
    }
    sec4.onclick = function() {
        alert("Clicked section 4");
    }

    
    sec5.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        img.style.backgroundImage = "url('../rsc/biography.jpg')";
    }
    sec5.onclick = function() {
        alert("Clicked section 5");
    }
    
    ovly.onclick = function() {
        ovly.style.display = "none";
    }
}