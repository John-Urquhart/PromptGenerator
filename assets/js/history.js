// assets/js/history.js

import { savePromptToDatabase } from "./database.js";

// Helper function to safely get form values
function getValue(id) {
  const element = document.getElementById(id);
  return element ? element.value || "" : "";
}

export function savePrompt() {
  const promptText = document.getElementById("modalPrompt").innerText;
  if (!promptText) {
    alert("No prompt to save yet!");
    return;
  }

  let history = JSON.parse(localStorage.getItem("promptHistory")) || [];
  history.push(promptText);
  localStorage.setItem("promptHistory", JSON.stringify(history));
  displayHistory();

  // Build data with correct field names matching PHP backend
  const promptData = {
    treatment: getValue("treatmentDropdown"),
    artStyle: getValue("artStyleDropdown"),
    animal_character: getValue("characterDropdown"), // Match PHP field name
    subcategory: getValue("subcategoryDropdown"),
    pose: getValue("poseDropdown"),
    catchphrase: getValue("catchphraseDropdown"),
    humour: getValue("humourDropdown"),
    custom: getValue("customInput"),
  };

  // Save to database
  savePromptToDatabase(promptData);
}

export function displayHistory() {
  const history = JSON.parse(localStorage.getItem("promptHistory")) || [];
  const historyList = document.getElementById("historyList");
  historyList.innerHTML = "";

  history.forEach((prompt) => {
    const li = document.createElement("li");
    li.textContent = prompt;
    historyList.appendChild(li);
  });
}

export function clearHistory() {
  if (
    confirm(
      "Are you sure you want to clear all saved prompts? This cannot be undone."
    )
  ) {
    localStorage.removeItem("promptHistory");
    displayHistory();
    alert("Prompt history cleared.");
  }
}
