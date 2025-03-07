window.onload = function() {
    const desc = document.getElementById("description");
    const sections = {
        section1: {
            content: "sec1",
            image: "../../rsc/township.jpg"
        },
        section2: {
            content: "sec2",
            image: "../../rsc/memorial.jpg"
        },
        section3: {
            content: "sec3",
            image: "../../rsc/buried.jpg"
        },
        section4: {
            content: "sec4",
            image: "../../rsc/newspaper.jpeg"
        },
        section5: {
            content: "sec5",
            image: "../../rsc/biography.jpg"
        }
    };

    // 初始化显示第一个section的内容
    const firstSection = document.getElementById("sec1");
    desc.innerHTML = firstSection.innerHTML;
    desc.style.backgroundImage = "url('../../rsc/township.jpg')";
    desc.style.backgroundSize = 'cover';
    desc.style.backgroundPosition = 'center';

    Object.keys(sections).forEach(sectionId => {
        const section = document.getElementById(sectionId);
        
        section.onmouseover = function() {
            const content = document.getElementById(sections[sectionId].content);
            desc.innerHTML = content.innerHTML;
            desc.style.backgroundImage = `url('${sections[sectionId].image}')`;
            desc.style.backgroundSize = 'cover';
            desc.style.backgroundPosition = 'center';
        }
        
        section.onclick = function() {
            alert(`Clicked ${sectionId}`);
        }
    });
}