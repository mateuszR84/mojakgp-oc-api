<?php namespace StDevs\Kgp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateHikesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('stdevs_kgp_hikes', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('difficulty_level')->nullable();
            $table->text('notes')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            //TODO:: ADD relation with user
            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('stdevs_kgp_hikes');
    }
};
