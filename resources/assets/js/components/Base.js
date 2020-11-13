export default class Base {

  constructor () {
    this.initAppData();
  }

  initAppData () {
    // Load data passed by the backend to the JS
    let data = document.querySelector('meta[property="la-app-data"]').getAttribute('content');
    window.appData = JSON.parse(data);
  }
}
