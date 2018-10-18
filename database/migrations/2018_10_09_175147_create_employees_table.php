<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEmployeesTable
 */
class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash');
            $table->string('first_name');
            $table->string('last_name');
	        $table->string('img');
            $table->unsignedInteger('position_id')->nullable();
            $table->date('data_reception')->nullable();
            $table->integer('salary')->nullable();
            $table->timestamps();

            $table->foreign('position_id')->references('id')->on('positions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    Schema::dropIfExists('employees');
    }
}
