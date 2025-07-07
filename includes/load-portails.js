function loadHTML(selector, url) {
  fetch(url)
    .then(res => res.text())
    .then(html => {
      document.querySelector(selector).innerHTML = html;
    })
    .catch(console.error);
}
window.addEventListener("DOMContentLoaded", () => {
  loadHTML("#site-header", "header.html");
  loadHTML("#site-footer", "footer.html");
});
