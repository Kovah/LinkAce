export default class LoadingButton {

  constructor ($el) {
    this.$btn = $el;
    this.$form = this.$btn.form;

    this.$btn.addEventListener('click', this.onClick.bind(this));
  }

  onClick (event) {
    if (this.$form.checkValidity()) {
      if (typeof this.$form.dataset.confirmation !== 'undefined' && confirm(this.$form.dataset.confirmation) === false) {
        event.preventDefault();
        return;
      }
      this.$btn.disabled = true;
      this.$form.submit();
    }
  }
}
