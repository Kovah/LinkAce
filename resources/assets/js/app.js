import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import Tooltip from 'bootstrap/js/dist/tooltip';

import { register } from './lib/views';
import Base from './components/Base';

import BookmarkTimer from './components/BookmarkTimer';
import BulkEdit from './components/BulkEdit';
import DatabaseSetup from './components/Setup';
import GenerateCronToken from './components/GenerateCronToken';
import Import from './components/Import';
import LoadingButton from './components/LoadingButton';
import OpenLinksInTabs from './components/OpenLinksInTabs';
import RecoveryCodes from './components/RecoveryCodes';
import ShareToggleAll from './components/ShareToggleAll';
import SimpleSelect from './components/SimpleSelect';
import TagsSelect from './components/TagsSelect';
import UpdateCheck from './components/UpdateCheck';
import UrlField from './components/UrlField';

// Register view components
function registerViews () {
  register('#app', class extends Base {
    constructor (element) {
      super(element, Tooltip);
    }
  });
  register('.bm-timer', BookmarkTimer);
  register('.bulk-edit', BulkEdit);
  register('.database-setup', DatabaseSetup);
  register('.cron-token', GenerateCronToken);
  register('.import-form', Import);
  register('.recovery-codes', RecoveryCodes);
  register('.share-toggle', ShareToggleAll);
  register('.simple-select', SimpleSelect);
  register('.tag-select', TagsSelect);
  register('.update-check', UpdateCheck);
  register('.open-in-tabs', OpenLinksInTabs);
  register('button[type="submit"]', LoadingButton);
  register('input[id="url"]', UrlField);
}

if (document.readyState !== 'loading') {
  registerViews();
} else {
  document.addEventListener('DOMContentLoaded', registerViews);
}
