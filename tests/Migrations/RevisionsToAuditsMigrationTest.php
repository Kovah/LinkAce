<?php

namespace Tests\Migrations;

use App\Models\Link;
use App\Models\User;
use CreateAuditsTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;
use Tests\TestCase;

class RevisionsToAuditsMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        config()->set('audit.delete_revisions_table', false);
    }

    public function testRevisionMigration(): void
    {
        $date = now()->subDay()->startOfSecond();

        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 5,
            'user_id' => null,
            'key' => 'url',
            'old_value' => 'https://example.com',
            'new_value' => 'https://test.com',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $this->assertDatabaseCount('revisions', 1);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertNull($audit->user_type);
        $this->assertNull($audit->user_id);
        $this->assertEquals('https://example.com', $audit->getModified()['url']['old']);
        $this->assertEquals('https://test.com', $audit->getModified()['url']['new']);
        $this->assertTrue(
            $date->equalTo($audit->created_at),
            sprintf('Created at date should be %s but is %s', $date, $audit->created_at)
        );
        $this->assertTrue(
            $date->equalTo($audit->updated_at),
            sprintf('Created at date should be %s but is %s', $date, $audit->updated_at)
        );
    }

    public function testRevisionMigrationWithUser(): void
    {
        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 5,
            'user_id' => 7,
            'key' => 'url',
            'old_value' => 'https://example.com',
            'new_value' => 'https://test.com',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertEquals(User::class, $audit->user_type);
        $this->assertEquals(7, $audit->user_id);
    }

    public function testRevisionMigrationWithoutOldValue(): void
    {
        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 5,
            'user_id' => 7,
            'key' => 'url',
            'old_value' => null,
            'new_value' => 'https://test.com',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertArrayNotHasKey('old', $audit->getModified()['url']);
        $this->assertArrayHasKey('new', $audit->getModified()['url']);
    }

    public function testRevisionMigrationWithRelations(): void
    {
        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 5,
            'user_id' => 7,
            'key' => 'revtags',
            'old_value' => null,
            'new_value' => '4,8,12',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertArrayHasKey('revtags', $audit->getModified());
        $this->assertEquals([4, 8, 12], $audit->getModified()['revtags']['new']);
    }

    public function testRevisionMigrationWithDeletion(): void
    {
        Link::factory()->createQuietly(['url' => 'https://deleted.com']);

        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 1,
            'user_id' => 7,
            'key' => 'deleted_at',
            'old_value' => null,
            'new_value' => '2020-07-02 09:23:34',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertEquals('deleted', $audit->event);
        $this->assertArrayHasKey('url', $audit->getModified());
        $this->assertEquals('https://deleted.com', $audit->getModified()['url']['old']);
    }

    public function testRevisionMigrationWithRestore(): void
    {
        Link::factory()->createQuietly(['url' => 'https://restored.com']);

        DB::table('revisions')->insert([
            'revisionable_type' => Link::class,
            'revisionable_id' => 1,
            'user_id' => 7,
            'key' => 'deleted_at',
            'old_value' => '2020-07-02 09:23:34',
            'new_value' => null,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $migrator = new CreateAuditsTable();
        $migrator->up();

        $audit = Audit::first();

        $this->assertEquals('restored', $audit->event);
        $this->assertArrayHasKey('url', $audit->getModified());
        $this->assertEquals('https://restored.com', $audit->getModified()['url']['new']);
    }
}
