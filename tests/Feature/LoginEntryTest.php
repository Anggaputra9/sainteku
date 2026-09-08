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

    public function test_password_toggle_has_localized_accessible_icon_markup(): void
    {
        foreach (['en', 'id'] as $locale) {
            self::assertNotSame('messages.show_password', __('messages.show_password', [], $locale));
            self::assertNotSame('messages.hide_password', __('messages.hide_password', [], $locale));
            $response = $this->withSession(['locale' => $locale])->get('/')->assertOk();
            $document = new \DOMDocument;
            @$document->loadHTML($response->getContent());
            $xpath = new \DOMXPath($document);
            $button = $xpath->query('//button[@id="togglePasswordBtn"]')->item(0);
            self::assertNotNull($button);
            self::assertSame('button', $button->getAttribute('type'));
            self::assertSame('password', $button->getAttribute('aria-controls'));
            self::assertSame('false', $button->getAttribute('aria-pressed'));
            self::assertSame(__('messages.show_password', [], $locale), $button->getAttribute('aria-label'));
            self::assertSame(__('messages.show_password', [], $locale), $button->getAttribute('title'));
            self::assertSame('', trim($button->textContent));
            $icons = $xpath->query('.//svg', $button);
            self::assertCount(2, $icons);
            foreach ($icons as $icon) {
                self::assertSame('true', $icon->getAttribute('aria-hidden'));
                self::assertSame('false', $icon->getAttribute('focusable'));
            }
            self::assertSame('eye', $icons->item(0)->getAttribute('data-icon'));
            self::assertFalse($icons->item(0)->hasAttribute('hidden'));
            self::assertSame('eye-slash', $icons->item(1)->getAttribute('data-icon'));
            self::assertTrue($icons->item(1)->hasAttribute('hidden'));
            self::assertSame('password', $button->parentNode->getAttribute('class'));
            self::assertCount(1, $xpath->query('./input[@id="password"]', $button->parentNode));
            $response->assertSee('.password { position: relative; }', false)
                ->assertSee('padding-right: 3.5rem;', false)
                ->assertSee('position: absolute; right: 1px; top: 50%;', false)
                ->assertSee('width: 44px; height: 44px;', false);
        }
    }

    public function test_shared_tashih_workspace_renders_styled_accessible_mc_controls(): void
    {
        $html = view('monevakademik::tashih.partials.modal-create-workspace', ['periods' => collect()])->render();
        self::assertStringContainsString('isEditMode ?', $html);
        self::assertStringContainsString('q-type mt-1 min-h-11 w-full rounded-xl border border-gray-200', $html);
        self::assertStringContainsString('q-option min-h-11 min-w-0 flex-1 rounded-xl border border-gray-200', $html);
        self::assertStringContainsString('aria-describedby="err-options-${uniqueId}"', $html);
        self::assertStringContainsString('aria-label="Kunci ${key}"', $html);
        self::assertStringContainsString('aria-label="Opsi ${key}"', $html);
        self::assertStringContainsString('focus-visible:outline-offset-2', $html);
        self::assertStringContainsString('dark:aria-invalid:border-red-400', $html);
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
