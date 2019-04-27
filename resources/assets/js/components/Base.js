export default class Base {

  constructor ($el) {
    this.initAppData();
    this.initAutoDarkmode();
  }

  initAppData () {
    // Load data passed by the backend to the JS
    let data = document.querySelector('meta[property="la-app-data"]').getAttribute('content');
    window.appData = JSON.parse(data);
  }

  initAutoDarkmode () {
    // Set CSS based on user preference for dark mode
    const preferDarkmode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const darkmodeAuto = document.querySelector('meta[name="darkmode"]');

    if (darkmodeAuto) {
      const stylesheet = document.querySelector('[rel="stylesheet"]');
      if (preferDarkmode) {
        stylesheet.href = stylesheet.dataset.darkHref;
      } else {
        stylesheet.href = stylesheet.dataset.lightHref;
      }
    }
  }
}
