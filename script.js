document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', (e) => {
      if (link.getAttribute('href').startsWith('#')) {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });
  
  const searchInput = document.querySelector('.search-input');
  const searchButton = document.querySelector('.search-button');
  
  searchButton.addEventListener('click', () => {
    performSearch(searchInput.value);
  });
  
  searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      performSearch(searchInput.value);
    }
  });
  
  function performSearch(query) {
    console.log('Searching for:', query);
  }
  
  function createParticles() {
    const particles = document.querySelector('.particles');
    for (let i = 0; i < 50; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      particle.style.cssText = `
        position: absolute;
        width: 2px;
        height: 2px;
        background: rgba(165, 214, 167, 0.5);
        border-radius: 50%;
        left: ${Math.random() * 100}%;
        top: ${Math.random() * 100}%;
        animation: particleFloat ${5 + Math.random() * 10}s linear infinite;
      `;
      particles.appendChild(particle);
    }
  }
  
  createParticles();
  const topo = document.getElementById("topo");
topo.innerHTML = `<div class="scrolling-text">Preservação da Fauna e Flora</div>`;
document.addEventListener("DOMContentLoaded", () => {
  const topo = document.getElementById("topo");

  if (topo) {
    topo.innerHTML = `
      <div class="scrolling-text">Preservação da Fauna e Flora</div>
    `;
  } else {
    console.warn("⚠️ Elemento #topo não encontrado!");
  }
});
