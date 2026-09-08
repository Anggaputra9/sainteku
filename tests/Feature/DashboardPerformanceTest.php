<?php

namespace Tests\Feature;

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardPerformanceTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->useEnvironmentPath(sys_get_temp_dir().'/opencode/saintekku-test-env');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::assertSame('sqlite', DB::connection()->getDriverName());
        self::assertSame(':memory:', DB::connection()->getDatabaseName());
        self::assertEmpty(DB::connection()->getConfig('url'));
        Schema::create('mst_role', function (Blueprint $table) {
            $table->id();
            $table->string('role_code');
        });
        Schema::create('trx_user_role', function (Blueprint $table) {
            $table->string('user_id');
            $table->integer('role_id');
        });
        Schema::create('ref_permission', function (Blueprint $table) {
            $table->id();
            $table->string('permission_code');
        });
        Schema::create('trx_role_permission', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->integer('modul_id');
            $table->boolean('allowed');
        });
        DB::table('mst_role')->insert(['id' => 1, 'role_code' => 'USR']);
        DB::table('trx_user_role')->insert(['user_id' => 'self', 'role_id' => 1]);
        foreach (['R', 'C', 'A'] as $i => $code) {
            DB::table('ref_permission')->insert(['id' => $i + 1, 'permission_code' => $code]);
        }
        $this->actingAs(new User(['id' => 'self']));
    }

    private function grant(int $module, int $permission, bool $allowed = true): void
    {
        DB::table('trx_role_permission')->insert([
            'role_id' => 1, 'permission_id' => $permission,
            'modul_id' => $module, 'allowed' => $allowed,
        ]);
    }

    public function test_personal_stats_use_three_queries_and_preserve_scopes_statuses_and_integer_zeros(): void
    {
        $groups = [
            ['trx_exam_proposals', 'created_by', ['SUBMITTED', 'APPROVED', 'REVISED', 'DRAFT', null], ['examSubmitted', 'examApproved', 'examRevised'], [1, 1, 1]],
            ['trx_inventory_loans', 'user_id', [0, 1, 3, 2, null], ['infraPending', 'infraDipinjam', 'infraSelesai'], [1, 1, 1]],
            ['trx_document', 'created_by', [1, 2, 3, 4, 0, null], ['docPending', 'docApproved', 'docRevision'], [2, 1, 1]],
        ];
        foreach ([1, 3, 6] as $module) {
            $this->grant($module, 2);
        }
        foreach ($groups as [$name, $owner, $statuses]) {
            Schema::create($name, function (Blueprint $table) use ($owner, $name) {
                $table->id();
                $table->string($owner);
                if ($name === 'trx_exam_proposals') {
                    $table->string('status')->nullable();
                } else {
                    $table->integer('status')->nullable();
                }
            });
            foreach (['self', 'other'] as $user) {
                foreach ($statuses as $status) {
                    DB::table($name)->insert([$owner => $user, 'status' => $status]);
                }
            }
        }
        Schema::create('trx_questions', fn (Blueprint $table) => $table->id());
        Schema::create('trx_exam_questions', function (Blueprint $table) {
            $table->integer('question_id');
            $table->integer('proposal_id');
        });

        foreach ([false, true] as $empty) {
            DB::enableQueryLog();
            DB::flushQueryLog();
            $data = app(DashboardController::class)->index()->getData();
            $queries = collect(DB::getQueryLog());
            DB::disableQueryLog();
            foreach ($groups as [$name, $owner, $statuses, $keys, $expected]) {
                $personal = $queries->filter(fn ($q) => str_contains($q['query'], 'from "'.$name.'"')
                    && str_contains($q['query'], '"'.$owner.'" = ?'));
                self::assertCount(1, $personal, $name);
                foreach ($keys as $i => $key) {
                    self::assertSame($empty ? 0 : $expected[$i], $data[$key], $key);
                }
                DB::table($name)->where($owner, 'self')->delete();
            }
            self::assertTrue($data['showTashih']);
            self::assertTrue($data['showInfra']);
            self::assertTrue($data['showDoc']);
            self::assertSame($empty ? 1 : 2, $data['totalDokumen']);
            self::assertSame(0, $data['examNeedAcc']);
            self::assertSame(0, $data['infraNeedAcc']);
            self::assertSame(0, $data['docNeedAcc']);
        }

        foreach ([1, 3, 6] as $module) {
            $this->grant($module, 3);
        }
        DB::table('mst_role')->where('id', 1)->update(['role_code' => 'RVI']);
        DB::table('trx_questions')->insert(['id' => 1]);
        DB::table('trx_exam_questions')->insert([
            'question_id' => 1,
            'proposal_id' => DB::table('trx_exam_proposals')->where('status', 'APPROVED')->value('id'),
        ]);
        $data = app(DashboardController::class)->index()->getData();
        self::assertTrue($data['isReviewerMonev']);
        self::assertTrue($data['isReviewerInfra']);
        self::assertTrue($data['isReviewerDoc']);
        self::assertSame(1, $data['examNeedAcc']);
        self::assertSame(1, $data['infraNeedAcc']);
        self::assertSame(2, $data['docNeedAcc']);
        self::assertSame(1, $data['totalBankSoal']);
        self::assertSame(1, $data['totalDokumen']);
    }

    public function test_hidden_stats_do_not_query_module_tables(): void
    {
        $this->grant(1, 1);
        $this->grant(3, 2, false);
        $data = app(DashboardController::class)->index()->getData();
        foreach (['showTashih', 'showInfra', 'showDoc'] as $key) {
            self::assertFalse($data[$key]);
        }
        foreach (['examSubmitted', 'infraPending', 'docPending'] as $key) {
            self::assertArrayNotHasKey($key, $data);
        }
    }

    public function test_sidebar_composer_is_scoped_and_preserves_permissions(): void
    {
        Schema::create('mst_menu', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('parent_id')->nullable();
            $table->integer('module_id')->nullable();
            $table->string('menu_link')->nullable();
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(true);
        });
        foreach ([1, 101, 200, 10, 20, 30] as $id) {
            DB::table('mst_menu')->insert(['id' => $id, 'module_id' => $id === 10 ? 99 : ($id === 30 ? 8 : null)]);
        }
        foreach ([11 => '/list', 12 => '/pengajuan', 13 => '/review', 14 => '/persetujuan', 15 => '/free', 16 => '/inactive'] as $id => $link) {
            DB::table('mst_menu')->insert([
                'id' => $id, 'parent_id' => 10, 'module_id' => $id === 15 ? null : 6,
                'menu_link' => $link, 'is_active' => $id !== 16,
            ]);
        }
        $this->grant(6, 1);
        $this->grant(6, 2, false);
        DB::enableQueryLog();
        DB::flushQueryLog();
        foreach (['pages.dashboard', 'layouts.app', 'layouts.app-header'] as $name) {
            $view = view($name);
            app('view')->callComposer($view);
            self::assertArrayNotHasKey('sidebarMenus', $view->getData());
        }
        self::assertCount(0, DB::getQueryLog());
        $sidebar = view('layouts.sidebar');
        app('view')->callComposer($sidebar);
        self::assertCount(7, DB::getQueryLog());
        $menus = $sidebar->getData()['sidebarMenus'];
        self::assertSame([10, 20], $menus->pluck('id')->all());
        self::assertSame([11, 15], $menus->first()->children->pluck('id')->all());

        $this->grant(6, 2);
        $this->grant(6, 3);
        app('view')->callComposer($sidebar);
        self::assertSame([11, 12, 13, 14, 15], $sidebar->getData()['sidebarMenus']->first()->children->pluck('id')->all());

        DB::table('mst_role')->where('id', 1)->update(['role_code' => 'ADM']);
        DB::flushQueryLog();
        app('view')->callComposer($sidebar);
        self::assertCount(3, DB::getQueryLog());
        self::assertCount(6, $sidebar->getData()['sidebarMenus']);
        self::assertSame([11, 12, 13, 14, 15], $sidebar->getData()['sidebarMenus']->firstWhere('id', 10)->children->pluck('id')->all());

        auth()->forgetGuards();
        DB::flushQueryLog();
        app('view')->callComposer($sidebar);
        self::assertCount(0, DB::getQueryLog());
        self::assertCount(0, $sidebar->getData()['sidebarMenus']);
        DB::disableQueryLog();
    }
}
