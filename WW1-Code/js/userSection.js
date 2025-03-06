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
    let isHovering = false;

    function showSection(index) {
        stopCarousel(); // 停止轮播
        var sectionContent = document.getElementById(sections[index]).innerHTML;
        desc.innerHTML = sectionContent;
        currentIndex = index; // 更新当前索引为鼠标悬停的section
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
        if (!isHovering) { // 只有在没有鼠标悬停时才开始轮播
            showSection(currentIndex);
            currentIndex = (currentIndex + 1) % sections.length;
            interval = setTimeout(startCarousel, 6000); // 每6秒切换一次 / Switch every 6 seconds
        }
    }

    function stopCarousel() {
        clearTimeout(interval);
    }

    // 修改鼠标悬停事件处理
    sec1.onmouseover = function() {
        isHovering = true;
        stopCarousel();
        showSection(0);
    }
    sec1.onmouseout = function() {
        isHovering = false;
        startCarousel();
    }
    sec1.onclick = function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    }
    
    sec2.onmouseover = function() {
        isHovering = true;
        stopCarousel();
        showSection(1);
    }
    sec2.onmouseout = function() {
        isHovering = false;
        startCarousel();
    }
    sec2.onclick = function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    }
    
    sec3.onmouseover = function() {
        isHovering = true;
        stopCarousel();
        showSection(2);
    }
    sec3.onmouseout = function() {
        isHovering = false;
        startCarousel();
    }
    sec3.onclick = function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    }
    
    sec4.onmouseover = function() {
        isHovering = true;
        stopCarousel();
        showSection(3);
    }
    sec4.onmouseout = function() {
        isHovering = false;
        startCarousel();
    }
    sec4.onclick = function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    }
    
    sec5.onmouseover = function() {
        isHovering = true;
        stopCarousel();
        showSection(4);
    }
    sec5.onmouseout = function() {
        isHovering = false;
        startCarousel();
    }
    sec5.onclick = function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
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

    let isMouseScrolling = false; // 添加滑动冷却控制变量

    // 修改边缘滚动检测
    sectionsEl.addEventListener('mousemove', (e) => {
        if (isMouseScrolling) return; // 如果在冷却中，直接返回
        
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
                isMouseScrolling = true; // 设置冷却状态
                setTimeout(() => {
                    isMouseScrolling = false; // 1秒后重置冷却状态
                }, 1000);
            } else if (mouseX > totalWidth - edgeWidth && sectionIndex < totalSections - 3) {
                // 右边缘滚动
                updatePosition(sectionIndex + 1);
                isMouseScrolling = true; // 设置冷却状态
                setTimeout(() => {
                    isMouseScrolling = false; // 1秒后重置冷却状态
                }, 1000);
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

    // 添加点击事件处理
    document.getElementById("section1").addEventListener("click", function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    });
    
    document.getElementById("section2").addEventListener("click", function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    });
    
    document.getElementById("section3").addEventListener("click", function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    });
    
    document.getElementById("section4").addEventListener("click", function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    });
    
    document.getElementById("section5").addEventListener("click", function() {
        window.location.href = "../../MockDatabaseSection/MockSection.html";
    });
};

let isScrolling = false;

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelector('.sections');
    const leftIndicator = document.querySelector('.scroll-indicator.left');
    const rightIndicator = document.querySelector('.scroll-indicator.right');

    function scroll(direction) {
        if (isScrolling) return; // 如果正在冷却中，直接返回
        
        isScrolling = true;
        const scrollAmount = direction === 'left' ? -300 : 300;
        sections.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });

        // 0.5秒后重置冷却状态
        setTimeout(() => {
            isScrolling = false;
        }, 500);
    }

    leftIndicator.addEventListener('click', () => scroll('left'));
    rightIndicator.addEventListener('click', () => scroll('right'));
});