// assets/js/prompt.js

import { openModal } from "./modal.js";
import { getValue } from "./utils.js";

export function generatePrompt() {
  const treatment = getValue("treatmentDropdown");
  const artStyle = getValue("artStyleDropdown");
  const character = getValue("characterDropdown");
  const subcategory = getValue("subcategoryDropdown");
  const pose = getValue("poseDropdown");
  const catchphrase = getValue("catchphraseDropdown");
  const humour = getValue("humourDropdown");
  const custom = getValue("customInput");

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
    parts.push(`in ${artStyle} style`);
  }

  if (humour) {
    parts.push(`with ${humour} humour`);
  }

  if (catchphrase) {
    parts.push(`saying "${catchphrase}"`);
  }

  if (custom) {
    parts.push(custom);
  }

  const prompt = parts.join(", ");
  openModal(prompt);
  return prompt;
}
