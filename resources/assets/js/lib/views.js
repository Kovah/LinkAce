/**
 * View elements
 * @type {HTMLElement[]}
 */
const $views = [];

/**
 * View instances
 * @type {object[]}
 */
const views = [];

/**
 * Registers a view to a name and saves a reference.
 * @param {string} name View name
 * @param {function} invokable View class
 * @param {HTMLElement} [$root=document.documentElement] Root element
 * @return {void}
 */
export function register (name, invokable, $root = document.documentElement) {
  // Retrieve all view elements in root
  const $elements = [...$root.querySelectorAll(`${name}`)];

  // Create an instance for each view
  $elements.forEach($element => getInstance($element, invokable));
}

/**
 * Returns the view instance by given element.
 * @return {HTMLElement} $element Element
 * @return {function} invokable Element class
 * @return {object}|null View instance or null if there is none
 */
export function getInstance ($element, invokable = null) {
  const index = $views.indexOf($element);
  if (index !== -1) {
    return views[index];
  } else if (invokable !== null && $views.indexOf($element) === -1) {
    const view = new invokable($element);
    $views.push($element);
    views.push(view);
    return view;
  } else {
    return null;
  }
}
