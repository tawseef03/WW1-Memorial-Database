window.onload = function() {
    var desc = document.getElementById("description");
    var sec1 = document.getElementById("section1");
    var sec2 = document.getElementById("section2");
    var sec3 = document.getElementById("section3");
    var sec4 = document.getElementById("section4");
    var sec5 = document.getElementById("section5");
    var sections = ["sec1", "sec2", "sec3", "sec4", "sec5"];
    var currentIndex = 0;
    var interval;

    function showSection(index) {
        var sectionContent = document.getElementById(sections[index]).innerHTML;
        desc.innerHTML = sectionContent;
        switch (index) {
            case 0:
                desc.style.backgroundImage = "url('../rsc/township.jpg')";
                break;
            case 1:
                desc.style.backgroundImage = "url('../rsc/memorial.jpg')";
                break;
            case 2:
                desc.style.backgroundImage = "url('../rsc/buried.jpg')";
                break;
            case 3:
                desc.style.backgroundImage = "url('../rsc/newspaper.jpeg')";
                break;
            case 4:
                desc.style.backgroundImage = "url('../rsc/biography.jpg')";
                break;
        }
    }

    function startCarousel() {
        showSection(currentIndex);
        currentIndex = (currentIndex + 1) % sections.length;
        interval = setTimeout(startCarousel, 6000); // 每6秒切换一次 / Switch every 6 seconds
    }

    function stopCarousel() {
        clearTimeout(interval);
    }

    sec1.onmouseover = function() {
        stopCarousel();
        desc.innerHTML = document.getElementById("sec1").innerHTML; // Hovering replaces the description box
        desc.style.backgroundImage = "url('../rsc/township.jpg')";
    }
    sec1.onmouseout = function() {
        startCarousel();
    }
    sec1.onclick = function() {
        alert("Clicked section 1"); // Clicking section 1 deletes the icon
    }

    sec2.onmouseover = function() {
        stopCarousel();
        desc.innerHTML = document.getElementById("sec2").innerHTML;
        desc.style.backgroundImage = "url('../rsc/memorial.jpg')";
    }
    sec2.onmouseout = function() {
        startCarousel();
    }
    sec2.onclick = function() {
        alert("Clicked section 2"); // Clicking other sections gives a pop-up
    }

    sec3.onmouseover = function() {
        stopCarousel();
        desc.innerHTML = document.getElementById("sec3").innerHTML;
        desc.style.backgroundImage = "url('../rsc/buried.jpg')";
    }
    sec3.onmouseout = function() {
        startCarousel();
    }
    sec3.onclick = function() {
        alert("Clicked section 3");
    }

    sec4.onmouseover = function() {
        stopCarousel();
        desc.innerHTML = document.getElementById("sec4").innerHTML;
        desc.style.backgroundImage = "url('../rsc/newspaper.jpeg')";
    }
    sec4.onmouseout = function() {
        startCarousel();
    }
    sec4.onclick = function() {
        alert("Clicked section 4");
    }

    sec5.onmouseover = function() {
        stopCarousel();
        desc.innerHTML = document.getElementById("sec5").innerHTML;
        desc.style.backgroundImage = "url('../rsc/biography.jpg')";
    }
    sec5.onmouseout = function() {
        startCarousel();
    }
    sec5.onclick = function() {
        alert("Clicked section 5");
    }

    startCarousel();
};