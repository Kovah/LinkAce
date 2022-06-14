<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use OwenIt\Auditing\Models\Audit;

class CreateAuditsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('audits')) {
            Schema::create('audits', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_type')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('event');
                $table->morphs('auditable');
                $table->text('old_values')->nullable();
                $table->text('new_values')->nullable();
                $table->text('url')->nullable();
                $table->ipAddress()->nullable();
                $table->string('user_agent', 1023)->nullable();
                $table->string('tags')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'user_type']);
            });
        }

        $this->migrateExistingRevisions();

        if (config('audit.delete_revisions_table')) {
            Schema::drop('revisions');
        }
    }

    public function down(): void
    {
        Schema::drop('audits');
    }

    protected function migrateExistingRevisions(): void
    {
        $entries = DB::table('revisions')->orderBy('created_at')->get();

        foreach ($entries as $entry) {
            $newAudit = new Audit();
            $newAudit->user_type = $entry->user_id ? User::class : null;
            $newAudit->user_id = $entry->user_id ?? null;
            $newAudit->auditable_type = $entry->revisionable_type;
            $newAudit->auditable_id = $entry->revisionable_id;
            $newAudit->url = null;
            $newAudit->ip_address = null;
            $newAudit->user_agent = null;
            $newAudit->tags = null;
            $newAudit->created_at = $entry->created_at;
            $newAudit->updated_at = $entry->updated_at;

            $newAudit->event = $this->processAuditEvent($entry);

            [$newAudit->old_values, $newAudit->new_values] = match ($newAudit->event) {
                Link::AUDIT_RELATION_EVENT => $this->buildModelRelationValues($entry),
                'deleted' => $this->buildDeletionValues($entry),
                'restored' => $this->buildRestoreValues($entry),
                default => $this->buildValues($entry),
            };

            $newAudit->save(['timestamps' => false]);
        }
    }

    protected function processAuditEvent($entry): string
    {
        if (in_array($entry->key, [Link::AUDIT_TAGS_NAME, Link::AUDIT_LISTS_NAME])) {
            return Link::AUDIT_RELATION_EVENT;
        }

        if ($entry->key === 'deleted_at' && $entry->old_value === null) {
            return 'deleted';
        }

        if ($entry->key === 'deleted_at' && $entry->new_value === null) {
            return 'restored';
        }

        return 'updated';
    }

    protected function buildValues($entry): array
    {
        $old = $entry->old_value ? [$entry->key => $entry->old_value] : [];
        $new = $entry->new_value ? [$entry->key => $entry->new_value] : [];

        return [$old, $new];
    }

    protected function buildModelRelationValues($entry): array
    {
        $old = $entry->old_value ? [$entry->key => array_map('intval', explode(',', $entry->old_value))] : [];
        $new = $entry->new_value ? [$entry->key => array_map('intval', explode(',', $entry->new_value))] : [];

        return [$old, $new];
    }

    protected function buildDeletionValues($entry): array
    {
        $old = Link::withTrashed()->find($entry->revisionable_id) ?: [];
        $new = [];

        return [$old, $new];
    }

    protected function buildRestoreValues($entry): array
    {
        $old = [];
        $new = Link::withTrashed()->find($entry->revisionable_id) ?: [];

        return [$old, $new];
    }
}
