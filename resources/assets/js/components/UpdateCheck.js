import { debounce } from '../lib/helper';

export default class UpdateCheck {

  constructor ($el) {
    this.$el = $el;

    this.result = null;
    this.currentVersion = this.$el.dataset.currentVersion;

    this.$running = $el.querySelector('.update-check-running');
    this.$versionFound = $el.querySelector('.update-check-version-found');
    this.$success = $el.querySelector('.update-check-success');
    this.$failed = $el.querySelector('.update-check-failed');

    this.init();
  }

  init () {
    this.checkForUpdate().then(result => {
      this.result = result;
      this.updateCheckStatus();
    });
  }

  updateCheckStatus () {
    this.$running.classList.add('d-none');

    if (typeof this.result === 'string') {
      this.$versionFound.innerText = this.$versionFound.innerText.replace('#VERSION#', this.result);
      this.$versionFound.classList.remove('d-none');
    } else if (this.result === true) {
      this.$success.classList.remove('d-none');
    } else {
      this.$failed.classList.remove('d-none');
    }
  }

  async checkForUpdate () {
    return fetch(window.appData.routes.fetch.updateCheck, {
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'}
    }).then((response) => {
      return response.json();
    }).then((results) => {
      return results.checkResult;
    }).catch(() => {
      return false;
    });
  }
}
