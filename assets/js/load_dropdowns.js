// assets/js/load_dropdowns.js

document.addEventListener("DOMContentLoaded", function () {
  loadTreatments();
  loadArtStyles();
  loadCharacters();
  loadPoses();
  loadCatchphrases();
  loadHumourStyles();
});

// Generic function to load dropdowns
function loadDropdown(apiEndpoint, dropdownId) {
  fetch(apiEndpoint)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      const dropdown = document.getElementById(dropdownId);
      if (!dropdown) {
        throw new Error(`Dropdown with id ${dropdownId} not found`);
      }

      // Clear existing options
      dropdown.innerHTML = '<option value="">Select an option</option>';

      // Handle both array of objects and array of strings
      data.forEach((item) => {
        const option = document.createElement("option");
        if (typeof item === "object" && item !== null) {
          option.value = item.value || item[Object.keys(item)[0]];
          option.textContent = item.label || item[Object.keys(item)[0]];
        } else {
          option.value = item;
          option.textContent = item;
        }
        dropdown.appendChild(option);
      });

      // Initialize Choices.js
      try {
        new Choices(dropdown, {
          searchEnabled: true,
          removeItemButton: true,
          placeholder: true,
          placeholderValue: "Select an option",
        });
      } catch (e) {
        console.error(`Error initializing Choices.js for ${dropdownId}:`, e);
      }
    })
    .catch((error) => {
      console.error(`Error loading ${dropdownId}:`, error);
    });
}

// Individual loaders
function loadTreatments() {
  loadDropdown(
    "get_list.php?table=treatments&column=treatment_text",
    "treatmentDropdown"
  );
}

function loadArtStyles() {
  loadDropdown(
    "get_list.php?table=art_styles&column=style_name",
    "artStyleDropdown"
  );
}

function loadCharacters() {
  loadDropdown(
    "get_list.php?table=animal_characters&column=character_name",
    "characterDropdown"
  );
}

function loadPoses() {
  loadDropdown("get_list.php?table=poses&column=pose_name", "poseDropdown");
}

function loadCatchphrases() {
  loadDropdown(
    "get_list.php?table=catchphrases&column=catchphrase_text",
    "catchphraseDropdown"
  );
}

function loadHumourStyles() {
  loadDropdown(
    "get_list.php?table=humour_styles&column=humour_name",
    "humourDropdown"
  );
}
