export default class ShareToggleAll {

  constructor ($el) {
    this.$toggle = $el;
    this.state = false;
    this.shareToggles = document.documentElement.querySelectorAll('.sharing-checkbox-input');

    this.$toggle.addEventListener('click', this.onToggleClick.bind(this));
  }

  onToggleClick () {
    this.state = !this.state
    this.shareToggles.forEach(toggle => {
      toggle.checked = this.state;
    })
  }
}
