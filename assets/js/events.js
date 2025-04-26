// assets/js/events.js

import { generatePrompt } from "./prompt.js";
import { closeModal } from "./modal.js";
import { savePrompt, displayHistory, clearHistory } from "./history.js";

document.addEventListener("DOMContentLoaded", function () {
  // Button Listeners
  document.getElementById("generateBtn").onclick = generatePrompt;
  document.getElementById("saveBtn").onclick = savePrompt;
  document.getElementById("clearBtn").onclick = clearHistory;
  document.getElementById("closeModal").onclick = closeModal;

  // Close modal on outside click
  window.onclick = function (event) {
    const modal = document.getElementById("promptModal");
    if (event.target == modal) {
      closeModal();
    }
  };

  // Show subcategory if "Cat" selected
  document.getElementById("character").addEventListener("change", function () {
    const character = this.value;
    const subcategoryContainer = document.getElementById(
      "subcategoryContainer"
    );

    if (character === "Cat") {
      subcategoryContainer.style.display = "block";
    } else {
      subcategoryContainer.style.display = "none";
      document.getElementById("subcategory").value = "";
    }
  });

  // Load history on startup
  displayHistory();
});
