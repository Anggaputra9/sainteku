<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mst_user', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('phone_number');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('address')->nullable()->after('bio');
            $table->enum('gender', ['L', 'P'])->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('gender');
        });
    }

    public function down()
    {
        Schema::table('mst_user', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'address', 'gender', 'birth_date']);
        });
    }
};
