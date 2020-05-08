import { register } from './lib/views';

import Base from './components/Base';
import UrlField from './components/UrlField';
import LoadingButton from './components/LoadingButton';
import BookmarkTimer from './components/BookmarkTimer';
import TagsSelect from './components/TagsSelect';
import SimpleSelect from './components/SimpleSelect';
import ShareToggleAll from './components/ShareToggleAll';
import GenerateApiToken from './components/GenerateApiToken';
import GenerateCronToken from './components/GenerateCronToken';
import UpdateCheck from './components/UpdateCheck';
import Import from './components/Import';

// Register view components
function registerViews () {
  register('#app', Base);
  register('input[id="url"]', UrlField);
  register('button[type="submit"]', LoadingButton);
  register('.bm-timer', BookmarkTimer);
  register('.tags-select', TagsSelect);
  register('.simple-select', SimpleSelect);
  register('.share-toggle', ShareToggleAll);
  register('.api-token', GenerateApiToken);
  register('.cron-token', GenerateCronToken);
  register('.update-check', UpdateCheck);
  register('.import-form', Import);
}

if (document.readyState !== 'loading') {
  registerViews();
} else {
  document.addEventListener('DOMContentLoaded', registerViews);
}
