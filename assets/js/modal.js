// --- modal.js ---

// Open the prompt modal
export function openModal(promptText) {
  const modal = document.getElementById("promptModal");
  const modalPrompt = document.getElementById("modalPrompt");
  
  if (modal && modalPrompt) {
    modalPrompt.innerText = promptText;
    modal.style.display = "flex";
  }
}

// Close the prompt modal
export function closeModal() {
  const modal = document.getElementById("promptModal");
  if (modal) {
    modal.style.display = "none";
  }
}

// Show a small "Copied!" popup
function showCopiedPopup() {
  let popup = document.createElement("div");
  popup.innerText = "✅ Copied!";
  popup.style.position = "fixed";
  popup.style.bottom = "30px";
  popup.style.right = "30px";
  popup.style.backgroundColor = "#4CAF50";
  popup.style.color = "white";
  popup.style.padding = "10px 20px";
  popup.style.borderRadius = "8px";
  popup.style.boxShadow = "0 2px 10px rgba(0,0,0,0.3)";
  popup.style.fontSize = "16px";
  popup.style.zIndex = "10000";
  popup.style.opacity = "0";
  popup.style.transition = "opacity 0.3s ease-in-out";

  document.body.appendChild(popup);

  // Trigger fade in
  setTimeout(() => {
    popup.style.opacity = "1";
  }, 10);

  // Remove after animation
  setTimeout(() => {
    popup.style.opacity = "0";
    setTimeout(() => {
      document.body.removeChild(popup);
    }, 300);
  }, 2000);
}

// Copy the prompt text to clipboard
export function copyToClipboard() {
  const promptText = document.getElementById("modalPrompt").innerText;
  if (!promptText) {
    alert("No prompt to copy!");
    return;
  }

  navigator.clipboard
    .writeText(promptText)
    .then(() => {
      showCopiedPopup();
    })
    .catch((err) => {
      console.error("Failed to copy text: ", err);
      alert("Failed to copy to clipboard");
    });
}
