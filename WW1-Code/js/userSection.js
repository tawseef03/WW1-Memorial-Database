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

    // 修改自动滚动功能
    var sectionsEl = document.querySelector('.sections');
    var namesEl = document.querySelector('.names');
    var sectionIndex = 0;
    let currentTranslate = 0;
    const totalSections = 5;
    
    function updatePosition(index, immediate = false) {
        sectionIndex = Math.max(0, Math.min(index, totalSections - 3)); // 限制在合理范围内
        const offset = -(sectionIndex * 340);
        
        if (immediate) {
            sectionsEl.style.transition = 'none';
            namesEl.style.transition = 'none';
        } else {
            sectionsEl.style.transition = 'transform 0.3s ease';
            namesEl.style.transition = 'transform 0.3s ease';
        }

        // 限制移动范围
        if (offset <= 0 && offset >= -((totalSections - 3) * 340)) {
            sectionsEl.style.transform = `translateX(${offset}px)`;
            namesEl.style.transform = `translateX(${offset}px)`;
            currentTranslate = offset;
        }
    }

    // 修改边缘滚动检测
    sectionsEl.addEventListener('mousemove', (e) => {
        const target = e.target.closest('.section');
        if (!target) return;

        const rect = target.getBoundingClientRect();
        const edgeWidth = 60; // 增加边缘检测区域宽度
        const mouseX = e.clientX - rect.left;
        const totalWidth = rect.width;
        
        // 只在边缘区域触发滚动
        if (mouseX < edgeWidth || mouseX > totalWidth - edgeWidth) {
            clearInterval(autoScrollInterval);
            
            // 获取当前section的索引
            const currentSectionIndex = Array.from(sectionsEl.children).indexOf(target);
            
            if (mouseX < edgeWidth && sectionIndex > 0) {
                // 左边缘滚动
                updatePosition(sectionIndex - 1);
            } else if (mouseX > totalWidth - edgeWidth && sectionIndex < totalSections - 3) {
                // 右边缘滚动
                updatePosition(sectionIndex + 1);
            }
        }
    });

    // 修改自动滚动定时器
    let autoScrollInterval = setInterval(function() {
        if (sectionIndex >= totalSections - 3) {
            sectionIndex = 0;
        }
        updatePosition((sectionIndex + 1) % (totalSections - 2));
    }, 4000);

    sectionsEl.addEventListener('mouseleave', () => {
        clearInterval(autoScrollInterval);
        autoScrollInterval = setInterval(function() {
            updatePosition((sectionIndex + 1) % (totalSections - 2));
        }, 4000);
    });

    // 添加触摸事件支持
    sectionsEl.addEventListener('touchstart', (e) => {
        isDown = true;
        startX = e.touches[0].pageX - sectionsEl.offsetLeft;
        scrollLeft = currentTranslate;
        clearInterval(autoScrollInterval);
    });

    sectionsEl.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.touches[0].pageX - sectionsEl.offsetLeft;
        const walk = (x - startX) * 1.5;
        const newTranslate = scrollLeft + walk;
        
        if (newTranslate <= 0 && newTranslate >= -680) {
            currentTranslate = newTranslate;
            sectionsEl.style.transform = `translateX(${newTranslate}px)`;
            namesEl.style.transform = `translateX(${newTranslate}px)`;
        }
    });

    sectionsEl.addEventListener('touchend', () => {
        isDown = false;
        snapToNearestSection();
        autoScrollInterval = setInterval(function() {
            sectionIndex = (sectionIndex + 1) % 3;
            updatePosition(sectionIndex);
        }, 4000);
    });

    // 添加箭头点击事件
    document.querySelector('.scroll-indicator.left').addEventListener('click', () => {
        clearInterval(autoScrollInterval);
        if (sectionIndex > 0) {
            updatePosition(sectionIndex - 1);
        }
        // 重启自动滚动
        autoScrollInterval = setInterval(function() {
            if (sectionIndex >= totalSections - 3) {
                sectionIndex = 0;
            }
            updatePosition((sectionIndex + 1) % (totalSections - 2));
        }, 4000);
    });

    document.querySelector('.scroll-indicator.right').addEventListener('click', () => {
        clearInterval(autoScrollInterval);
        if (sectionIndex < totalSections - 3) {
            updatePosition(sectionIndex + 1);
        }
        // 重启自动滚动
        autoScrollInterval = setInterval(function() {
            if (sectionIndex >= totalSections - 3) {
                sectionIndex = 0;
            }
            updatePosition((sectionIndex + 1) % (totalSections - 2));
        }, 4000);
    });
};