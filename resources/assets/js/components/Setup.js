export default class DatabaseSetup {

  constructor ($el) {
    this.$connection = $el.querySelector('#connection');
    this.$dbPath = $el.querySelector('.db-path');
    this.$dbHost = $el.querySelector('.db-host');
    this.$dbPort = $el.querySelector('.db-port');
    this.$dbName = $el.querySelector('.db-name');
    this.$dbUser = $el.querySelector('.db-user');
    this.$dbPass = $el.querySelector('.db-password');
    this.$submit = $el.querySelector('.db-submit');

    this.$connection.addEventListener('change', this.handleConnectionChange.bind(this));
    this.handleConnectionChange();

    $el.addEventListener('submit', () => {
      this.$submit.toggleAttribute('disabled');
    });
  }

  handleConnectionChange () {
    const connection = this.$connection.options[this.$connection.selectedIndex].value;
    this.$dbPath.classList.toggle('d-none', connection !== 'sqlite');
    this.$dbHost.classList.toggle('d-none', connection === 'sqlite');
    this.$dbPort.classList.toggle('d-none', connection === 'sqlite');
    this.$dbName.classList.toggle('d-none', connection === 'sqlite');
    this.$dbUser.classList.toggle('d-none', connection === 'sqlite');
    this.$dbPass.classList.toggle('d-none', connection === 'sqlite');
  }
}
