var swiper = new Swiper(".swiper", {
  effect: "coverflow",
  grabCursor: true,
  centeredSlides: true,
  slidesPerView: "auto",
  initialSlide: 0,
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
  },
  on: {
    init: function() {
      this.slides.forEach((slide, index) => {
        if (index !== this.activeIndex) {
          slide.style.pointerEvents = 'none';
        }
      });
    },
    slideChange: function() {
      this.slides.forEach((slide, index) => {
        if (index !== this.activeIndex) {
          slide.style.pointerEvents = 'none';
        } else {
          slide.style.pointerEvents = 'auto';
        }
      });
    }
  }
});

