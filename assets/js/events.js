// assets/js/events.js

import { generatePrompt } from "./prompt.js";
import { closeModal } from "./modal.js";
import { savePrompt, displayHistory, clearHistory } from "./history.js";
import { copyToClipboard } from "./modal.js";

document.addEventListener("DOMContentLoaded", function () {
  // Button Listeners
  document.getElementById("generateBtn").onclick = generatePrompt;
  document.getElementById("saveBtn").onclick = savePrompt;
  document.getElementById("clearBtn").onclick = clearHistory;
  document.getElementById("closeModal").onclick = closeModal;
  document.getElementById("copyBtn").onclick = copyToClipboard;

  // Close modal on outside click
  window.onclick = function (event) {
    const modal = document.getElementById("promptModal");
    if (event.target == modal) {
      closeModal();
    }
  };

  // Show subcategory if "Cat" selected
  document.getElementById("characterDropdown").addEventListener("change", function () {
    const character = this.value;
    const subcategoryContainer = document.getElementById(
      "subcategoryContainer"
    );

    if (character === "Cat") {
      subcategoryContainer.style.display = "block";
    } else {
      subcategoryContainer.style.display = "none";
      document.getElementById("subcategoryDropdown").value = "";
    }
  });

  // Load history on startup
  displayHistory();
});
