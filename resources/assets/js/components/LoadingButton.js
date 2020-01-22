export default class LoadingButton {

  constructor ($el) {
    this.$btn = $el;
    this.$form = this.$btn.form;

    this.$btn.addEventListener('click', this.onClick.bind(this));
  }

  onClick () {
    if (this.formIsValid()) {
      this.$btn.disabled = true;
      this.$form.submit();
    }
  }

  formIsValid () {
    return this.$form.checkValidity();
  }
}
