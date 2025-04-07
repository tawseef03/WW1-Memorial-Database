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
            const pages = [
                '../TownShipPage/township.html',
                '../MemorialPage/memorial.html',
                '../BurialPage/burial.html',
                '../NewspaperPage/newspaper.html',
                '../BiographyPage/biography.html'
            ];
            window.location.href = pages[index];
        });
    });
});