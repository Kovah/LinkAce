<?php
return [
    'import' => 'Import',
    'import_queue' => 'Import Queue',
    'failed_imports' => 'Failed Imports',
    'scheduled_for' => 'Scheduled for',
    'start_import' => 'Start Import',
    'import_running' => 'Import running...',
    'import_file' => 'File for Import',

    'import_help' => 'You can import your existing browser bookmarks here. Usually, bookmarks are exported into an .html file by your browser. Select the file here and start the import. Please note that a cron must be configured for the import to work.',

    'import_networkerror' => 'Something went wrong while trying to import the bookmarks. Please check your browser console for details or consult the application logs.',
    'import_error' => 'Something went wrong while trying to import the bookmarks. Please consult the application logs.',
    'import_empty' => 'Could not import any bookmarks. Either the uploaded file is corrupt or empty.',
    'import_successfully' => ':queued links are queued for import and will be processed consecutively. :skipped links were skipped because they already exist in the database. All imported links will be assigned the tag :taglink.',
];
