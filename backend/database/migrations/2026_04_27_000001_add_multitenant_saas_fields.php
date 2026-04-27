<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend tenants table with full SaaS fields
        Schema::table('tenants', function (Blueprint $table) {
            if (! Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('tenants', 'secondary_color')) {
                $table->string('secondary_color', 20)->nullable()->after('primary_color');
            }

            if (! Schema::hasColumn('tenants', 'plan_name')) {
                $table->string('plan_name')->nullable()->after('plan_type');
            }

            if (! Schema::hasColumn('tenants', 'seat_limit')) {
                $table->unsignedInteger('seat_limit')->default(100)->after('plan_name');
            }

            if (! Schema::hasColumn('tenants', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('support_email');
            }

            if (! Schema::hasColumn('tenants', 'website')) {
                $table->string('website')->nullable()->after('contact_email');
            }
        });

        // Back-fill slug from subdomain for existing tenants
        DB::statement("UPDATE tenants SET slug = subdomain WHERE slug IS NULL");

        // Make slug NOT NULL after back-fill
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });

        // Ensure slug is always in sync (back-fill plan_name from plan_type)
        DB::statement("UPDATE tenants SET plan_name = plan_type WHERE plan_name IS NULL");
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('tenants', 'slug') ? 'slug' : null,
                Schema::hasColumn('tenants', 'secondary_color') ? 'secondary_color' : null,
                Schema::hasColumn('tenants', 'plan_name') ? 'plan_name' : null,
                Schema::hasColumn('tenants', 'seat_limit') ? 'seat_limit' : null,
                Schema::hasColumn('tenants', 'contact_email') ? 'contact_email' : null,
                Schema::hasColumn('tenants', 'website') ? 'website' : null,
            ]);

            if (in_array('slug', $columns, true)) {
                $table->dropUnique(['slug']);
            }

            $nonSlug = array_values(array_diff($columns, ['slug']));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
