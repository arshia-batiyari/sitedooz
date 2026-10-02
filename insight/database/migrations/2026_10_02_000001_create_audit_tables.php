<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->text('url');
            $table->text('normalized_url');
            $table->string('host');
            $table->string('status')->index();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->json('category_scores')->nullable();
            $table->text('error_message')->nullable();
            $table->json('crawl_stats')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('business_name')->nullable();
            $table->string('lead_source')->default('sitedooz_insight');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->text('url');
            $table->text('final_url')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->json('redirect_chain')->nullable();
            $table->unsignedSmallInteger('depth')->default(0);
            $table->boolean('indexable')->default(false);
            $table->string('title')->nullable();
            $table->string('content_hash', 64)->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('category');
            $table->string('name');
            $table->text('description');
            $table->text('business_impact');
            $table->text('recommendation');
            $table->unsignedSmallInteger('weight')->default(1);
            $table->string('severity');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('audit_findings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('audit_page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rule_key')->index();
            $table->string('category');
            $table->string('severity');
            $table->string('title');
            $table->text('description');
            $table->text('business_impact');
            $table->text('recommendation');
            $table->text('page_url')->nullable();
            $table->json('metadata')->nullable();
            $table->decimal('score_impact', 5, 2)->default(0);
            $table->boolean('needs_human_review')->default(false);
            $table->timestamps();
        });

        Schema::create('audit_metrics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('audit_page_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source');
            $table->string('name');
            $table->decimal('value', 12, 3)->nullable();
            $table->string('unit')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_metrics');
        Schema::dropIfExists('audit_findings');
        Schema::dropIfExists('audit_rules');
        Schema::dropIfExists('audit_pages');
        Schema::dropIfExists('audits');
    }
};
