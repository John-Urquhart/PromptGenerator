// --- Function Definitions ---

// Safely get the value from an input/select
function getValue(id) {
    const element = document.getElementById(id);
    return element ? element.value : '';
}

// Open the modal
function openModal() {
    const modal = document.getElementById('promptModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Close the modal
function closeModal() {
    const modal = document.getElementById('promptModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Generate the prompt text
function generatePrompt() {
    const treatment = getValue('treatment');
    const artStyle = getValue('artStyle');
    const character = getValue('character');
    const subcategory = getValue('subcategory');
    const pose = getValue('pose');
    const catchphrase = getValue('catchphrase');
    const humour = getValue('humour');
    const custom = getValue('custom');

    let parts = [];

    if (treatment) {
        parts.push(`(${treatment})`);
    }

    if (character && pose) {
        let subject = subcategory ? subcategory : character;
        parts.push(`A ${subject} doing ${pose}`);
    } else if (character) {
        let subject = subcategory ? subcategory : character;
        parts.push(`A ${subject}`);
    } else if (pose) {
        parts.push(`Doing ${pose}`);
    }

    if (artStyle) {
        parts.push(`in a ${artStyle} style`);
    }
    if (catchphrase) {
        parts.push(`Catchphrase: "${catchphrase}"`);
    }
    if (humour) {
        parts.push(`Humour Style: ${humour}`);
    }
    if (custom) {
        parts.push(`Additional Notes: ${custom}`);
    }

    if (parts.length === 0) {
        alert("Please select at least one field to generate a prompt.");
        return;
    }

    const prompt = parts.join('. ') + '.';

    document.getElementById('modalPrompt').innerText = prompt;
    openModal();
}

// Copy the prompt to clipboard
function copyToClipboard() {
    const promptText = document.getElementById('modalPrompt').innerText;
    if (!promptText) {
        alert("No prompt to copy yet!");
        return;
    }
    navigator.clipboard.writeText(promptText)
        .then(() => alert("Prompt copied to clipboard!"))
        .catch(err => alert("Failed to copy text: " + err));
}

// Save prompt to localStorage
function savePrompt() {
    const promptText = document.getElementById('modalPrompt').innerText;
    if (!promptText) {
        alert("No prompt to save yet!");
        return;
    }

    let history = JSON.parse(localStorage.getItem('promptHistory')) || [];
    history.push(promptText);
    localStorage.setItem('promptHistory', JSON.stringify(history));
    displayHistory();
}

// Display saved prompt history
function displayHistory() {
    const historyList = document.getElementById('historyList');
    historyList.innerHTML = '';

    let history = JSON.parse(localStorage.getItem('promptHistory')) || [];

    history.forEach((item) => {
        const li = document.createElement('li');
        li.textContent = item;
        historyList.appendChild(li);
    });
}

// Clear all history
function clearHistory() {
    if (confirm("Are you sure you want to clear all saved prompts? This cannot be undone.")) {
        localStorage.removeItem('promptHistory');
        displayHistory();
        alert("Prompt history cleared.");
    }
}

// --- Event Listeners ---

document.addEventListener('DOMContentLoaded', function() {
    // Close modal when "X" is clicked
    document.getElementById('closeModal').onclick = closeModal;

    // Close modal if user clicks outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('promptModal');
        if (event.target == modal) {
            closeModal();
        }
    };

    // Show subcategory if "Cat" is selected
    document.getElementById('character').addEventListener('change', function() {
        const character = this.value;
        const subcategoryContainer = document.getElementById('subcategoryContainer');

        if (character === "Cat") {
            subcategoryContainer.style.display = 'block';
        } else {
            subcategoryContainer.style.display = 'none';
            document.getElementById('subcategory').value = ""; // Reset subcategory
        }
    });

    // Display saved history on page load
    displayHistory();
});
