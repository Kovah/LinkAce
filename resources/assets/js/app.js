import { register } from './lib/views';
import Base from './components/Base';

import BookmarkTimer from './components/BookmarkTimer';
import BulkEdit from './components/BulkEdit';
import DatabaseSetup from './components/Setup';
import GenerateCronToken from './components/GenerateCronToken';
import Import from './components/Import';
import LoadingButton from './components/LoadingButton';
import ShareToggleAll from './components/ShareToggleAll';
import SimpleSelect from './components/SimpleSelect';
import TagsSelect from './components/TagsSelect';
import UpdateCheck from './components/UpdateCheck';
import UrlField from './components/UrlField';

// Register view components
function registerViews () {
  register('#app', Base);
  register('.bm-timer', BookmarkTimer);
  register('.bulk-edit', BulkEdit);
  register('.database-setup', DatabaseSetup);
  register('.cron-token', GenerateCronToken);
  register('.import-form', Import);
  register('.share-toggle', ShareToggleAll);
  register('.simple-select', SimpleSelect);
  register('.tag-select', TagsSelect);
  register('.update-check', UpdateCheck);
  register('button[type="submit"]', LoadingButton);
  register('input[id="url"]', UrlField);
}

if (document.readyState !== 'loading') {
  registerViews();
} else {
  document.addEventListener('DOMContentLoaded', registerViews);
}
