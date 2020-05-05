export default class Import {

  constructor ($el) {
    this.$el = $el;
    this.$file = $el.querySelector('#import-file');

    this.$submit = $el.querySelector('.import-submit');
    this.$submitProcessing = $el.querySelector('.import-submit-processing');
    this.$submitDefault = $el.querySelector('.import-submit-default');

    this.$alertNetworkError = $el.querySelector('.import-alert-networkerror');
    this.$alertWarning = $el.querySelector('.import-alert-warning');
    this.$alertSuccess = $el.querySelector('.import-alert-success');

    this.$submit.addEventListener('click', this.onSubmit.bind(this));
  }

  onSubmit () {

    this.toggleSubmitBtnState();

    const formData = new FormData();
    formData.append('import-file', this.$file.files[0]);
    formData.append('_token', window.appData.user.token);

    fetch(this.$el.dataset.action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {'Accept': 'application/json'},
      body: formData
    }).then((response) => {

      if (response.ok === false) {
        console.log(response);
        this.$alertNetworkError.classList.remove('d-none');
        return response;
      }

      return response.json();
    }).then((result) => {
      this.toggleSubmitBtnState();

      if (result.ok === false) {
        return;
      }

      if (result.success) {
        this.$alertSuccess.innerText = result.message;
        this.$alertSuccess.classList.remove('d-none');
      } else {
        this.$alertWarning.innerText = result.message;
        this.$alertWarning.classList.remove('d-none');
      }

    });
  }

  toggleSubmitBtnState (isProcessing) {
    this.$submit.disabled = !isProcessing;

    this.$submitProcessing.classList.toggle('d-none');
    this.$submitDefault.classList.toggle('d-none');
  }
}
