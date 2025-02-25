let currentIndex = 0;

function updateCarousel() {
  const track = document.querySelector('.carousel-track');
  const images = document.querySelectorAll('.carousel-image');
  const imageWidth = images[0].clientWidth;

  track.style.transform = `translateX(-${currentIndex * imageWidth}px)`;
}

function prevSlide() {
  const images = document.querySelectorAll('.carousel-image');
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  updateCarousel();
}

function nextSlide() {
  const images = document.querySelectorAll('.carousel-image');
  currentIndex = (currentIndex + 1) % images.length;
  updateCarousel();
}

window.addEventListener('resize', updateCarousel);

