import { debounce } from '../lib/helper';

export default class GenerateApiToken {

  constructor ($el) {
    this.$el = $el;

    this.$input = $el.querySelector('.api-token-input');
    this.$btn = $el.querySelector('.api-token-generate');
    this.$failureMsg = $el.querySelector('.api-token-generate-failure');

    this.$btn.addEventListener('click', this.onButtonClick.bind(this));
  }

  onButtonClick () {
    this.$btn.disabled = true;

    this.fetchNewToken();
  }

  fetchNewToken () {

    const fetchURL = window.appData.routes.fetch.generateApiToken;

    fetch(fetchURL, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        _token: window.appData.user.token
      })
    }).then((response) => {
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
}
