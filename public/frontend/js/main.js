// ── Menu Open/Close ──
const overlay  = document.getElementById('menuOverlay');
const menuImg  = document.getElementById('menuImage');
const allLinks = document.querySelectorAll('.menu-link');

function openMenu() {
  overlay.classList.add('is-open');
  document.body.style.overflow = 'hidden';
}

function closeMenu() {
  overlay.classList.remove('is-open');
  document.body.style.overflow = '';
}

// ── Hover Image Swap ──
function hoverLink(el) {
  allLinks.forEach(l => l.style.opacity = '0.25');
  el.style.opacity = '1';

  const newSrc = el.dataset.img;
  if (!newSrc || !menuImg) return;

  menuImg.style.opacity = '0';
  menuImg.style.transform = 'scale(1.08)';
  menuImg.style.transition = 'opacity 0.3s ease, transform 0.5s ease';

  const tempImg = new Image();
  tempImg.onload = () => {
    setTimeout(() => {
      menuImg.src = newSrc;
      menuImg.style.opacity = '1';
      menuImg.style.transform = 'scale(1.03)';
    }, 200);
  };
  tempImg.onerror = () => {

    document.getElementById('menuImageWrap').style.background = '#1e3525';
    menuImg.style.display = 'none';
  };
  tempImg.src = newSrc;
}

function unhoverLink(el) {
  allLinks.forEach(l => l.style.opacity = '0.5');
}

    let current = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function goToSlide(index) {
        slides[current].classList.remove('opacity-100');
        slides[current].classList.add('opacity-0');
        dots[current].classList.remove('h-8', 'opacity-100');
        dots[current].classList.add('h-8', 'opacity-40');

        current = index;

        slides[current].classList.remove('opacity-0');
        slides[current].classList.add('opacity-100');
        dots[current].classList.remove('h-8', 'opacity-40');
        dots[current].classList.add('h-8', 'opacity-100');
    }

   

// ESC key close
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeMenu();
});
