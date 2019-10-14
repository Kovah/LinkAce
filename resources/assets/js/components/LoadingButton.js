export default class LoadingButton {

  constructor ($el) {
    this.$el = $el;
    this.$form = this.$el.form;

    this.$el.addEventListener('click', this.onClick.bind(this))
  }

  onClick () {
    if (this.formIsValid()) {
      this.$el.disabled = true;
      this.$form.submit();
    }
  }

  formIsValid() {
    return this.$form.checkValidity();
  }
}
