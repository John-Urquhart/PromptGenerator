// assets/js/history.js

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
