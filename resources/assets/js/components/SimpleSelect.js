import TomSelect from 'tom-select';
import TomSelect_caret_position from 'tom-select/dist/js/plugins/caret_position';
import TomSelect_input_autogrow from 'tom-select/dist/js/plugins/input_autogrow';

TomSelect.define('caret_position', TomSelect_caret_position);
TomSelect.define('input_autogrow', TomSelect_input_autogrow);

export default class SimpleSelect {

  constructor ($el) {
    new TomSelect($el, {
      plugins: ['caret_position', 'input_autogrow'],
      create: false,
      maxOptions: null,
    });
  }
}
