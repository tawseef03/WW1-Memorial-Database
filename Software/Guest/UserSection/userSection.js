function openPage(n) {
    switch (n) {
        case 1:
            window.location.href = '../RecordsDisplay/township.php';
            break;
        case 2:
            window.location.href = '../RecordsDisplay/memorial.php';
            break;
        case 3:
            window.location.href = '../RecordsDisplay/buried.php';
            break;
        case 4:
            window.location.href = '../RecordsDisplay/newspaper.php';
            break;
        case 5:
            window.location.href = '../RecordsDisplay/biographies.php';
            break;
        default:
            return;
    }
}

function logout() {
    fetch('../../Global/logout.php', {
        method: 'POST'
    }).then(() => {
        window.location.href = '../../LoginPage/login.php';
    });
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
        img.style.backgroundImage = "url('../../Resource/Images/township.jpg')";
    }
    sec1.onclick = function() {
        openPage(1); // Redirect to township.php
    }

    
    sec2.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec2").innerHTML;
        img.style.backgroundImage = "url('../../Resource/Images/memorial.jpg')";
    }
    sec2.onclick = function() {
        openPage(2); // Redirect to memorial.php
    }

    
    sec3.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec3").innerHTML;
        img.style.backgroundImage = "url('../../Resource/Images/buried.jpg')";
    }
    sec3.onclick = function() {
        openPage(3); // Redirect to burials.php
    }

    
    sec4.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        img.style.backgroundImage = "url('../../Resource/Images/newspaper.jpg')";
    }
    sec4.onclick = function() {
        openPage(4); // Redirect to newspaper.php
    }

    
    sec5.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        img.style.backgroundImage = "url('../../Resource/Images/biography.jpg')";
    }
    sec5.onclick = function() {
        openPage(5); // Redirect to biographies.php
    }
    
    document.getElementById("overlay").onclick = function() {
        document.getElementById("overlay").style.display = "none";
    }

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