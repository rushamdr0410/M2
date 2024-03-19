var swiper = new Swiper(".swiper", {
  effect: "coverflow",
  grabCursor: true,
  centeredSlides: true,
  coverflowEffect: {
    rotate: 0,
    stretch: 0,
    depth: 100,
    modifier: 3,
    slideShadows: true
  },
  keyboard: {
    enabled: true
  },
  mousewheel: {
    thresholdDelta: 70
  },
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true
  },
  breakpoints: {
    640: {
      slidesPerView: 2
    },
    768: {
      slidesPerView: 1
    },
    1024: {
      slidesPerView: 2
    },
    1560: {
      slidesPerView: 3
    }
  }
});

var swiper = new Swiper(".coming-container",{
  spaceBetween: 20,
  loop: true,
  autoplay:{
    delay: 55000,
    disableOnInteraction: false,
  },
  centeredSlides: true,
  breakpoints:{
    0:{
      slidesPerView: 2,
    },
    568:{
      slidesPerView: 3,
    },
    768:{
      slidesPerView: 4,
    },
    968:{
      slidesPerView: 5,
    },
  }
})
