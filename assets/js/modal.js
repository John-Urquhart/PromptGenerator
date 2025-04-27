// --- modal.js ---

// Open the prompt modal
export function openModal() {
  const modal = document.getElementById("promptModal");
  if (modal) {
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
  popup.style.zIndex = "9999";
  popup.style.opacity = "0";
  popup.style.transition = "opacity 0.5s ease";

  document.body.appendChild(popup);

  // Animate popup
  setTimeout(() => {
    popup.style.opacity = "1";
  }, 100);

  // Remove popup after 2.5 seconds
  setTimeout(() => {
    popup.style.opacity = "0";
    setTimeout(() => {
      document.body.removeChild(popup);
    }, 500);
  }, 2500);
}

// Copy the prompt text to clipboard
export function copyToClipboard() {
  console.log("Copy button clicked."); // DEBUG

  const promptText = document.getElementById("modalPrompt").innerText;
  console.log("Prompt to copy:", promptText); // DEBUG

  if (!promptText) {
    alert("No prompt to copy yet!");
    return;
  }

  navigator.clipboard
    .writeText(promptText)
    .then(() => {
      console.log("Copied successfully."); // DEBUG
      showCopiedPopup();
    })
    .catch((err) => {
      console.error("Failed to copy text:", err);
      alert("Failed to copy prompt.");
    });
}
