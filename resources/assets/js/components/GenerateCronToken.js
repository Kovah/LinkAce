import { debounce } from '../lib/helper';

export default class GenerateCronToken {

  constructor ($el) {
    this.$el = $el;

    this.$input = $el.querySelector('.cron-token-input');
    this.$btn = $el.querySelector('.cron-token-generate');
    this.$failureMsg = $el.querySelector('.cron-token-generate-failure');
    this.$cronUrl = $el.querySelector('.cron-token-url');

    this.$btn.addEventListener('click', this.onButtonClick.bind(this));
  }

  onButtonClick () {
    this.$btn.disabled = true;

    this.fetchNewToken();
  }

  fetchNewToken () {

    if (!confirm(this.$el.dataset.confirmMessage)) {
      this.$btn.disabled = false;
      return;
    }

    const fetchURL = window.appData.routes.fetch.generateCronToken;

    fetch(fetchURL, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token
      })
    }).then(response => {
      return response.json();
    }).then(response => {
      this.handleResponse(response);
    }).catch(() => {
      this.showFailureMsg();
    });

  }

  handleResponse (response) {

    if (typeof response.new_token !== 'undefined') {
      debounce(() => {
        this.$input.value = response.new_token;
        this.$cronUrl.innerHTML = this.buildCronURl(response.new_token);
      }, 1000);

      window.setTimeout(() => {
        this.$btn.disabled = false;
      }, 5000);
    } else {
      this.showFailureMsg();
    }

  }

  showFailureMsg () {
    this.$failureMsg.classList.remove('d-none');
  }

  buildCronURl (newToken) {
    return this.$el.dataset.cronUrlBase + newToken;
  }
}
