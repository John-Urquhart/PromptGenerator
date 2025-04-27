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
