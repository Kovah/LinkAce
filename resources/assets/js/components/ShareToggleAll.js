export default class ShareToggleAll {

  constructor ($el) {
    this.$toggle = $el;
    this.shareToggles = document.documentElement.querySelectorAll('.sharing-checkbox-input');

    this.$toggle.addEventListener('click', this.onToggleClick.bind(this));
  }

  onToggleClick () {
    this.shareToggles.forEach(toggle => {
      toggle.checked = !toggle.checked;
    })
  }
}
