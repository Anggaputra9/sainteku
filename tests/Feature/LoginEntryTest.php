<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginEntryTest extends TestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        // Never fall back to the project's environment file in isolated tests.
        $app->useEnvironmentPath(sys_get_temp_dir().'/opencode/saintekku-test-env');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32))]);
    }

    private function createUserTable(): void
    {
        // Only the authentication schema is needed; do not run module migrations.
        Schema::create('mst_user', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('email');
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
        });
    }

    public function test_database_is_isolated_in_memory(): void
    {
        self::assertTrue(app()->environment('testing'));
        self::assertFalse(app()->configurationIsCached());
        self::assertSame('sqlite', DB::connection()->getDriverName());
        self::assertSame(':memory:', DB::connection()->getDatabaseName());
        self::assertEmpty(DB::connection()->getConfig('url'));
        self::assertSame('', DB::selectOne('PRAGMA database_list')->file);
    }

    public function test_root_renders_login_without_news_or_other_database_tables(): void
    {
        $this->get('/')->assertOk()->assertViewIs('landing')
            ->assertSee('id="loginForm"', false)
            ->assertSee('autocomplete="current-password"', false)
            ->assertSee('id="forgotPasswordForm"', false)
            ->assertDontSee('loginModal', false)
            ->assertDontSee('id="blog"', false);

        $this->get('/login')->assertRedirect('/');
        $this->get('/dashboard')->assertRedirect('/');
    }

    public function test_authenticated_visitors_redirect_and_can_log_out(): void
    {
        $this->actingAs(new User(['id' => '123', 'is_active' => true]));
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/login')->assertRedirect('/dashboard');
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/')->assertOk();
    }

    public function test_language_and_flash_messages_remain_visible(): void
    {
        $this->from('/')->get('/language/en')->assertRedirect('/')
            ->assertSessionHas('locale', 'en');
        $this->withSession(['locale' => 'en', 'success' => 'Password reset complete.'])
            ->get('/')->assertSee('lang="en"', false)->assertSee('Password reset complete.');
        $this->from('/')->get('/language/id')->assertSessionHas('locale', 'id');
        $this->from('/')->get('/language/invalid')->assertSessionHas('locale', 'id');
        $this->withSession(['error' => 'Invalid reset token.'])->get('/')
            ->assertSee('Invalid reset token.');
    }

    public function test_login_accepts_email_and_id_and_preserves_remember_and_redirect(): void
    {
        $this->createUserTable();
        DB::table('mst_user')->insert([
            'id' => '123', 'email' => 'user@example.test',
            'password' => Hash::make('secret-password'), 'is_active' => true,
        ]);

        foreach (['user@example.test', '123'] as $credential) {
            $this->postJson('/login', [
                'credential' => $credential, 'password' => 'secret-password', 'remember' => true,
            ])->assertOk()->assertJson(['success' => true, 'redirect' => '/dashboard']);
            $this->assertAuthenticated();
            $this->assertNotEmpty(DB::table('mst_user')->value('remember_token'));
            $this->post('/logout')->assertRedirect('/');
        }
    }

    public function test_login_validation_and_rate_limiting_are_preserved(): void
    {
        $this->createUserTable();
        $this->postJson('/login', [])->assertUnprocessable()->assertJsonValidationErrors(['credential', 'password']);
        $credentials = ['credential' => 'missing@example.test', 'password' => 'incorrect'];
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/login', $credentials)->assertUnauthorized();
        }
        $this->postJson('/login', $credentials)->assertStatus(429)->assertJsonStructure(['retry_after']);
        $this->assertGuest();
    }

    public function test_recovery_rate_limiting_and_reset_return_to_login(): void
    {
        $this->createUserTable();
        Schema::table('mst_user', fn (Blueprint $table) => $table->string('identity_id')->nullable());
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at');
        });
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->postJson('/forgot-password', ['credential' => 'missing'])->assertUnprocessable();
        }
        $this->postJson('/forgot-password', ['credential' => 'missing'])
            ->assertStatus(429)->assertJsonStructure(['retry_after']);
        $this->get('/reset-password/invalid')->assertRedirect('/')->assertSessionHas('error');

        DB::table('mst_user')->insert([
            'id' => '123', 'email' => 'user@example.test', 'password' => Hash::make('old-password'),
        ]);
        DB::table('password_reset_tokens')->insert([
            'email' => 'user@example.test', 'token' => 'valid-token', 'created_at' => now(),
        ]);
        $this->get('/reset-password/valid-token')->assertOk()->assertViewIs('auth.reset-password');
        $this->post('/reset-password', [
            'email' => 'user@example.test', 'token' => 'valid-token',
            'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertRedirect('/')->assertSessionHas('success');
        $this->assertTrue(Hash::check('new-password', DB::table('mst_user')->value('password')));
        $this->assertSame(0, DB::table('password_reset_tokens')->count());
        $this->get('/')->assertOk()->assertSee(session('success'));
    }
}
