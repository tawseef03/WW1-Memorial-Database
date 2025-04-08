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
        openPage(1); // Redirect to township.php
    }

    
    sec2.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec2").innerHTML;
        img.style.backgroundImage = "url('../rsc/memorial.jpg')";
    }
    sec2.onclick = function() {
        openPage(2); // Redirect to memorial.php
    }

    
    sec3.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec3").innerHTML;
        img.style.backgroundImage = "url('../rsc/buried.jpg')";
    }
    sec3.onclick = function() {
        openPage(3); // Redirect to burials.php
    }

    
    sec4.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        img.style.backgroundImage = "url('../rsc/newspaper.jpg')";
    }
    sec4.onclick = function() {
        openPage(4); // Redirect to newspaper.php
    }

    
    sec5.onmouseover = function() {
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        img.style.backgroundImage = "url('../rsc/biography.jpg')";
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

const sections = [
    {
        title: "Bradford and Surrounding Townships",
        desc: "Source of information and knowledge relating to Bradford and its surrounding townships and their contributions to World War 1.",
        countKey: "township",
        aboutID: 1,
        dbID: 1
    },
    {
        title: "Names Recorded on Bradford Memorials",
        desc: "Find all those remembered on our Bradford Memorials for WW1.",
        countKey: "memorial",
        aboutID: 2,
        dbID: 2
    },
    {
        title: "Buried in Bradford",
        desc: "Search those who served and are buried in Bradford from WW1.",
        countKey: "buried",
        aboutID: 3,
        dbID: 3
    },
    {
        title: "Newspaper references",
        desc: "Events, articles and perspectives related to Bradford in WW1.",
        countKey: "newspaper",
        aboutID: 4,
        dbID: 4
    },
    {
        title: "Biographies",
        desc: "Biographies of men and women from Bradford in WW1.",
        countKey: "biography",
        aboutID: 5,
        dbID: 5
    }
];

fetch('../php_html/userSectionData.php')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('hidden-sections-container');
        const template = document.getElementById('section-template');

        sections.forEach((s, index) => {
            const clone = template.content.cloneNode(true);

            clone.querySelector('.title-text').textContent = s.title;
            clone.querySelector('.desc-text').textContent = s.desc;
            clone.querySelector('.record-count').textContent = data[s.countKey];

            clone.querySelector('.about-btn').setAttribute('onclick', `openAbout(${s.aboutID})`);
            clone.querySelector('.db-btn').setAttribute('onclick', `openPage(${s.dbID})`);

            const div = clone.querySelector('.section-block');
            div.id = `sec${index + 1}`;
            container.appendChild(div);
        });
    });