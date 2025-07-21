document.addEventListener("DOMContentLoaded", () => {
  const topo = document.getElementById("topo");

  if (topo) {
    topo.innerHTML = `
      <div class="scrolling-text">Preservação da Fauna e Flora</div>
    `;
  } else {
    console.warn("Elemento com id 'topo' não encontrado.");
  }
});
const topo = document.getElementById("topo");
topo.innerHTML = `<div class="scrolling-text">Preservação da Fauna e Flora</div>`;

