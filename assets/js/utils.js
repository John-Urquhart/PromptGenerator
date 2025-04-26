// assets/js/utils.js

export function getValue(id) {
  const element = document.getElementById(id);
  return element ? element.value : "";
}
