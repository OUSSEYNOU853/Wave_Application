<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToBlacklistTokensTable extends Migration
{
    public function up()
    {
        Schema::table('blacklist_tokens', function (Blueprint $table) {
            $table->string('type')->nullable()->after('token'); // Ajouter la colonne "type"
        });
    }

    public function down()
    {
        Schema::table('blacklist_tokens', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
