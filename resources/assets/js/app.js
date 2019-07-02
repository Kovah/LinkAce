import { register } from './lib/views';

// Register components
import Base from './components/Base';
import UrlField from './components/UrlField';
import LoadingButton from './components/LoadingButton';

// Register view components
function registerViews () {
  // register component views
  register('#app', Base);
  register('input[id="url"]', UrlField);
  register('button[type="submit"]', LoadingButton);
}

if (document.readyState !== 'loading') {
  // dom loaded event already fired
  registerViews();
} else {
  // wait for the dom to load
  document.addEventListener('DOMContentLoaded', registerViews);
}
