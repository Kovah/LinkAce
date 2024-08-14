export default class Base {

  constructor () {
    this.initAppData();
    this.initBootstrapTooltips();
  }

  initAppData () {
    // Load data passed by the backend to the JS
    let data = document.querySelector('meta[property="la-app-data"]')?.getAttribute('content');
    if (data) {
      window.appData = JSON.parse(data);
    }
  }

  initBootstrapTooltips () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  }
}
