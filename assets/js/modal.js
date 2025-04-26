// assets/js/modal.js

export function openModal() {
  const modal = document.getElementById("promptModal");
  if (modal) {
    modal.style.display = "flex";
  }
}

export function closeModal() {
  const modal = document.getElementById("promptModal");
  if (modal) {
    modal.style.display = "none";
  }
}
