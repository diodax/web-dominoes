<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('head');
            $table->integer('tail');
            $table->string('image_url');
        });

        $this->initializeBones();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bones');
    }

    public function initializeBones()
    {
        $data = array(
            array('head' => 6, 'tail' => 6, 'image_url'=> '/img/bones/bone6-6.png'),
            array('head' => 6, 'tail' => 5, 'image_url'=> '/img/bones/bone6-5.png'),
            array('head' => 6, 'tail' => 4, 'image_url'=> '/img/bones/bone6-4.png'),
            array('head' => 6, 'tail' => 3, 'image_url'=> '/img/bones/bone6-3.png'),
            array('head' => 6, 'tail' => 2, 'image_url'=> '/img/bones/bone6-2.png'),
            array('head' => 6, 'tail' => 1, 'image_url'=> '/img/bones/bone6-1.png'),
            array('head' => 6, 'tail' => 0, 'image_url'=> '/img/bones/bone6-0.png'),

            array('head' => 5, 'tail' => 5, 'image_url'=> '/img/bones/bone5-5.png'),
            array('head' => 5, 'tail' => 4, 'image_url'=> '/img/bones/bone5-4.png'),
            array('head' => 5, 'tail' => 3, 'image_url'=> '/img/bones/bone5-3.png'),
            array('head' => 5, 'tail' => 2, 'image_url'=> '/img/bones/bone5-2.png'),
            array('head' => 5, 'tail' => 1, 'image_url'=> '/img/bones/bone5-1.png'),
            array('head' => 5, 'tail' => 0, 'image_url'=> '/img/bones/bone5-0.png'),
            array('head' => 4, 'tail' => 4, 'image_url'=> '/img/bones/bone4-4.png'),

            array('head' => 4, 'tail' => 3, 'image_url'=> '/img/bones/bone4-3.png'),
            array('head' => 4, 'tail' => 2, 'image_url'=> '/img/bones/bone4-2.png'),
            array('head' => 4, 'tail' => 1, 'image_url'=> '/img/bones/bone4-1.png'),
            array('head' => 4, 'tail' => 0, 'image_url'=> '/img/bones/bone4-0.png'),
            array('head' => 3, 'tail' => 3, 'image_url'=> '/img/bones/bone3-3.png'),
            array('head' => 3, 'tail' => 2, 'image_url'=> '/img/bones/bone3-2.png'),
            array('head' => 3, 'tail' => 1, 'image_url'=> '/img/bones/bone3-1.png'),

            array('head' => 3, 'tail' => 0, 'image_url'=> '/img/bones/bone3-0.png'),
            array('head' => 2, 'tail' => 2, 'image_url'=> '/img/bones/bone2-2.png'),
            array('head' => 2, 'tail' => 1, 'image_url'=> '/img/bones/bone2-1.png'),
            array('head' => 2, 'tail' => 0, 'image_url'=> '/img/bones/bone2-0.png'),
            array('head' => 1, 'tail' => 1, 'image_url'=> '/img/bones/bone1-1.png'),
            array('head' => 1, 'tail' => 0, 'image_url'=> '/img/bones/bone1-0.png'),
            array('head' => 0, 'tail' => 0, 'image_url'=> '/img/bones/bone0-0.png')
        );

        DB::table('bones')->insert($data);
    }
}
