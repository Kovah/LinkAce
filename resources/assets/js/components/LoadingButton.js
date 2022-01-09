export default class LoadingButton {

  constructor ($el) {
    this.$btn = $el;
    this.$form = this.$btn.form;

    this.$btn.addEventListener('click', this.onClick.bind(this));
  }

  onClick () {
    if (this.$form.checkValidity()) {
      this.$btn.disabled = true;
      this.$form.submit();
    }
  }
}
