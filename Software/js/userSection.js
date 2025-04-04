function openAbout(n) {
    switch (n) {
        case 1:
            document.getElementById("popup").src = "township.php";
            break;
        case 2:
            document.getElementById("popup").src = "memorial.php";
            break;
        case 3:
            document.getElementById("popup").src = "buried.html";
            break;
        case 4:
            document.getElementById("popup").src = "newspaper.html";
            break;
        case 5:
            document.getElementById("popup").src = "biographies.php";
            break;
        default:
            alert("Invalid about section id: "+n);
            return;
    }
    document.getElementById("overlay").style.display = "block";
}

function openPage(n) {
    switch (n) {
        case 1:
            window.location.href = 'township.php';
            break;
        case 2:
            window.location.href = 'memorial.php';
            break;
        case 3:
            window.location.href = 'buried.php';
            break;
        case 4:
            window.location.href = 'newspaper.php';
            break;
        case 5:
            window.location.href = 'biographies.php';
            break;
        default:
            alert("Invalid about section id: "+n);
            return;
    }
}

window.onload = function() {
    var desc = document.getElementById("description");
    var img = document.getElementById("bgimg");
    var sec1 = document.getElementById("section1");
    var sec2 = document.getElementById("section2");
    var sec3 = document.getElementById("section3");
    var sec4 = document.getElementById("section4");
    var sec5 = document.getElementById("section5");
    
    sec1.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec1").innerHTML; // Hovering replaces the description box
        img.style.backgroundImage = "url('../rsc/township.jpg')";
    }
    sec1.onclick = function() {
        window.location.href = 'township.php'; // Redirect to township.php
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
        window.location.href = 'burials.php'; // Redirect to burials.php
    }

    
    sec4.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        img.style.backgroundImage = "url('../rsc/newspaper.jpg')";
    }
    sec4.onclick = function() {
        window.location.href = 'newspaper.php'; // Redirect to newspaper.php
    }

    
    sec5.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        img.style.backgroundImage = "url('../rsc/biography.jpg')";
    }
    sec5.onclick = function() {
        window.location.href = 'biographies.php'; // Redirect to biographies.php
    }
    
    document.getElementById("overlay").onclick = function() {
        document.getElementById("overlay").style.display = "none";
    }
}

window.onload = function() {
    const openBtn1 = document.getElementById("openModal1");
    const closeBtn1 = document.getElementById("closeModal1");
    const modal1 = document.getElementById("modal1");

    openBtn1.addEventListener("click", () => {
        modal1.classList.add("open");
    });

    closeBtn1.addEventListener("click", () => {
        modal1.classList.remove("open");
    });
}