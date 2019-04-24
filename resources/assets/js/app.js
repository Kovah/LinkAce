// Set CSS based on
const preferDarkmode = window.matchMedia('(prefers-color-scheme: dark)').matches;
const darkmodeAuto = document.documentElement.querySelector('meta[name="darkmode"]');

if (darkmodeAuto) {
  const stylesheet = document.documentElement.querySelector('[rel="stylesheet"]');
  if (preferDarkmode) {
    stylesheet.href = stylesheet.dataset.darkHref;
  } else {
    stylesheet.href = stylesheet.dataset.lightHref;
  }
}
