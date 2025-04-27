// assets/js/prompt.js

import { openModal } from "./modal.js";
import { getValue } from "./utils.js";

export function generatePrompt() {
  const treatment = getValue("treatment");
  const artStyle = getValue("artStyle");
  const character = getValue("character");
  const subcategory = getValue("subcategory");
  const pose = getValue("pose");
  const catchphrase = getValue("catchphrase");
  const humour = getValue("humour");
  const custom = getValue("custom");

  let parts = [];

  if (treatment) {
    parts.push(`(${treatment})`);
  }

  if (character && pose) {
    const subject = subcategory ? subcategory : character;
    parts.push(`A ${subject} doing ${pose}`);
  } else if (character) {
    const subject = subcategory ? subcategory : character;
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
    parts.push(`Also: ${custom}`);
  }

  if (parts.length === 0) {
    alert("Please select at least one field to generate a prompt.");
    return;
  }

  const prompt = parts.join(". ") + ".";

  document.getElementById("modalPrompt").innerText = prompt;
  openModal();
}
