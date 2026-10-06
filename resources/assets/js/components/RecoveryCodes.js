export default class RecoveryCodes {

  constructor ($el) {
    this.$el = $el;

    this.$showBtn = $el.querySelector('.recovery-codes-show');
    this.$form = $el.querySelector('.recovery-codes-form');
    this.$password = $el.querySelector('.recovery-codes-password');
    this.$submitBtn = $el.querySelector('.recovery-codes-submit');
    this.$error = $el.querySelector('.recovery-codes-error');
    this.$output = $el.querySelector('.recovery-codes-output');

    this.$showBtn.addEventListener('click', this.onShowClick.bind(this));
    this.$submitBtn.addEventListener('click', this.onSubmitClick.bind(this));
    this.$password.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        this.onSubmitClick();
      }
    });
  }

  onShowClick () {
    this.$showBtn.classList.add('d-none');
    this.$form.classList.remove('d-none');
    this.$password.focus();
  }

  onSubmitClick () {
    this.$submitBtn.disabled = true;
    this.hideError();

    fetch(window.appData.routes.fetch.recoveryCodes, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        _token: window.appData.user.token,
        current_password: this.$password.value
      })
    }).then(response => {
      return response.json().then(body => ({ status: response.status, body }));
    }).then(({ status, body }) => {
      this.handleResponse(status, body);
    }).catch(() => {
      this.showError(this.$el.dataset.failureMessage);
    });
  }

  handleResponse (status, body) {
    if (status === 200 && Array.isArray(body.codes)) {
      this.renderCodes(body.codes);
      return;
    }

    this.$submitBtn.disabled = false;
    this.$password.value = '';

    const validationError = body.errors?.current_password?.[0];
    this.showError(validationError || body.message || this.$el.dataset.failureMessage);
  }

  renderCodes (codes) {
    this.$form.remove();

    this.$output.innerHTML = '';
    codes.forEach(code => {
      const $code = document.createElement('code');
      $code.innerText = code;
      this.$output.appendChild($code);
      this.$output.appendChild(document.createElement('br'));
    });

    this.$output.classList.remove('d-none');
  }

  showError (message) {
    this.$error.innerText = message;
    this.$error.classList.remove('d-none');
  }

  hideError () {
    this.$error.classList.add('d-none');
  }
}
