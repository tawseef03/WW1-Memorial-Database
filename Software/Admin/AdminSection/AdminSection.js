function openPage(n) {
    switch (n) {
        case 1:
            window.location.href = '../../Guest/RecordsDisplay/township.php';
            break;
        case 2:
            window.location.href = '../../Guest/RecordsDisplay/memorial.php';
            break;
        case 3:
            window.location.href = '../../Guest/RecordsDisplay/buried.php';
            break;
        case 4:
            window.location.href = '../../Guest/RecordsDisplay/newspaper.php';
            break;
        case 5:
            window.location.href = '../../Guest/RecordsDisplay/biographies.php';
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
    
    function attachModalListeners() {
        const modals = [
            { buttonId: "openModal1", modalId: "modal1" },
            { buttonId: "openModal2", modalId: "modal2" },
            { buttonId: "openModal3", modalId: "modal3" },
            { buttonId: "openModal4", modalId: "modal4" },
            { buttonId: "openModal5", modalId: "modal5" },
        ];

        modals.forEach(({ buttonId, modalId }) => {
            const openBtn = document.getElementById(buttonId);
            const modal = document.getElementById(modalId);

            if (openBtn && modal) {
                openBtn.addEventListener("click", () => {
                    modal.classList.add("open");
                });

                window.addEventListener("click", (event) => {
                    if (event.target === modal) {
                        modal.classList.remove("open");
                    }
                });
            }
        });
    }

    function updateDescription(sectionId, imageUrl) {
        desc.innerHTML = document.getElementById(sectionId).innerHTML;
        img.style.backgroundImage = `url('${imageUrl}')`;
        attachModalListeners(); // Reattach event listeners for modals
    }

    sec1.onmouseover = function() {
        updateDescription("sec1", "../../Resource/Images/township.jpg");
    };
    sec1.onclick = function() {
        openPage(1);
    };

    sec2.onmouseover = function() {
        updateDescription("sec2", "../../Resource/Images/memorial.jpg");
    };
    sec2.onclick = function() {
        openPage(2);
    };

    sec3.onmouseover = function() {
        updateDescription("sec3", "../../Resource/Images/buried.jpg");
    };
    sec3.onclick = function() {
        openPage(3);
    };

    sec4.onmouseover = function() {
        updateDescription("sec4", "../../Resource/Images/newspaper.jpg");
    };
    sec4.onclick = function() {
        openPage(4);
    };

    sec5.onmouseover = function() {
        updateDescription("sec5", "../../Resource/Images/biography.jpg");
    };
    sec5.onclick = function() {
        openPage(5);
    };

    // Initial attachment of modal listeners
    attachModalListeners();
}