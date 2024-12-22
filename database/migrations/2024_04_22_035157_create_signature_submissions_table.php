<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signature_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('required_id');
            $table->integer('department_id')->comment('bộ phận');
            $table->text('content')->comment('Nội dung duyệt');
            $table->integer('positions')->comment('chức vụ:1:General Director,2:Director,3:Supper Manager,4: Manager, 5:Supper Chief, 6:Chief, 7:Staff, 8:Suppser Leader, 9:Leader, 10:Sub Leader, 11:worker','khi người ký confirm thì lưu chức vụ của người ký');
            $table->string('approve_id')->comment('người phê duyệt');
            $table->integer('sign_instead')->comment('ký thay 0: trưởng bộ phận ký, 1: phó trưởng bộ phận ký');
            $table->integer('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signature_submissions');
    }
};
