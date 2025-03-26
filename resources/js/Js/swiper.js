// console.log(swiper.activeIndex)

// const swiper = new Swiper('.swiper', {
//     pagination: {
//         el: '.swiper-pagination',
//     },
// });

function sliderMouseSlideInit(){
    console.log("13");
    document.addEventListener('mousemove', function (e) {
        const targetElement = e.target;
        if (targetElement.closest('[data-mousemove-swipe]')){
            const sliderElement = targetElement.closest('[data-mousemove-swipe]');
            const sliderItem = swiper[getIndex(sliderElement)];
            const sliderLength = sliderItem.slides.length;
            if (sliderLength > 0){
                const sliderWidth = sliderItem.width;
                const sliderPath = Math.round(sliderWidth / sliderLength);
                const sliderMousePos = e.clientX - sliderElement.offsetLeft;
                const sliderSlide = Math.floor(sliderMousePos / sliderPath);
                sliderItem.slideTo(sliderSlide);
            }
        }
    });

    function getIndex(el){
        return Array.from(el.parentNode.children).indexOf(el);
    }
}
if (document.querySelector('[data-mousemove-swipe]')){
    console.log("13");
    sliderMouseSlideInit();
}
