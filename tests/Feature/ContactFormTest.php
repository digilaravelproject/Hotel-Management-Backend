<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_valid_demo_request_submits_successfully_and_saves_to_database(): void
    {
        $response = $this->postJson(route('contact.submit'), [
            'name' => 'John Doe',
            'email' => 'john@luxuryhotel.test',
            'phone' => '+91 98765 43210',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'John Doe',
            'email' => 'john@luxuryhotel.test',
            'phone' => '+91 98765 43210',
        ]);
    }

    public function test_bot_submission_with_honeypot_is_silently_dropped_without_saving(): void
    {
        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.test',
            'phone' => '1234567890',
            'website_url' => 'https://spam-link.test/casino',
        ]);

        // Returns success so bot stops retrying
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // But NO inquiry is stored in database!
        $this->assertDatabaseMissing('contact_inquiries', [
            'email' => 'bot@spammer.test',
        ]);
    }

    public function test_missing_required_fields_fails_validation(): void
    {
        $response = $this->postJson(route('contact.submit'), [
            'name' => '',
            'email' => 'invalid-email',
            'phone' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'phone']);
    }
}

