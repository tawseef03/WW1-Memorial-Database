document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.section');
    const descriptionDiv = document.querySelector('.description');
    const descriptionText = document.getElementById('description');

    // 设置初始背景图片和描述
    descriptionDiv.style.backgroundImage = "url('../../rsc/township.jpg')";
    
    sections.forEach((section, index) => {
        section.addEventListener('mouseover', function() {
            const images = [
                '../../rsc/township.jpg',
                '../../rsc/memorial.jpg',
                '../../rsc/buried.jpg',
                '../../rsc/newspaper.jpeg',
                '../../rsc/biography.jpg'
            ];
            descriptionDiv.style.backgroundImage = `url('${images[index]}')`;
            descriptionText.textContent = this.getAttribute('data-description');
        });

        section.addEventListener('click', function() {
            // 管理员点击跳转到用户页面
            const pages = [
                '../UserHonourPage/UserHonour.php',
                '../UserMemorialPage/UserMemorial.php',
                '../UserBuriedPage/UserBuried.php',
                '../UserNewspaperPage/userNewspaper.php',
                '../UserBiographiesPage/UserBiographies.php'
            ];
            window.location.href = pages[index];
        });
    });
});