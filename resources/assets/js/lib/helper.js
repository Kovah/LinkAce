/**
 * Debounce a function with a given timeout
 *
 * @see https://gist.github.com/makenova/7885923
 * @param {CallableFunction} callback
 * @param {int} [timeout=500] timeout
 */
export function debounce (callback, timeout = 500) {
  if (window.timeoutId) {
    window.clearTimeout(window.timeoutId);
  }

  window.timeoutId = window.setTimeout(function () {
    callback();
  }, timeout);
}
