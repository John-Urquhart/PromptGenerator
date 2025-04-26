// --- modal.js ---

// Open the prompt modal
export function openModal() {
  const modal = document.getElementById('promptModal');
  if (modal) {
      modal.style.display = 'flex';
  }
}

// Close the prompt modal
export function closeModal() {
  const modal = document.getElementById('promptModal');
  if (modal) {
      modal.style.display = 'none';
  }
}

// Copy the prompt text to clipboard
export function copyToClipboard() {
  const promptText = document.getElementById('modalPrompt').innerText;
  if (!promptText) {
      alert("No prompt to copy yet!");
      return;
  }

  navigator.clipboard.writeText(promptText)
      .then(() => {
          // Optional: Show success message
          alert("Prompt copied to clipboard!");
      })
      .catch(err => {
          console.error("Failed to copy text:", err);
          alert("Failed to copy prompt.");
      });
}
