// assets/js/history.js

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
    treatment: getValue("treatment"),
    artStyle: getValue("artStyle"),
    animal_character: getValue("character"), // Match PHP field name
    subcategory: getValue("subcategory"),
    pose: getValue("pose"),
    catchphrase: getValue("catchphrase"),
    humour: getValue("humour"),
    custom: getValue("custom"),
  };

  console.log("Saving to database:", promptData); // Debug log
  savePromptToDatabase(promptData);
}

export function displayHistory() {
  const historyList = document.getElementById("historyList");
  historyList.innerHTML = "";

  let history = JSON.parse(localStorage.getItem("promptHistory")) || [];

  history.forEach((item) => {
    const li = document.createElement("li");
    li.textContent = item;
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

function savePromptToDatabase(promptData) {
  fetch("save_prompt.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(promptData),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("Server response:", data);
      if (!data.success) {
        alert("Failed to save to database: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error saving to database:", error);
      alert("Error saving to database.");
    });
}
