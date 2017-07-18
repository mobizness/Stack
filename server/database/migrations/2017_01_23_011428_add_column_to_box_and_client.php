<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBoxAndClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // boxes table
        Schema::table('boxes', function(Blueprint $table) {
            $table->string('firmware')->nullable();
            $table->string('serial')->nullable();
            $table->string('wan_name')->nullable();
            $table->string('machine_type')->nullable();
            $table->string('tx_bytes')->nullable();
            $table->string('rx_bytes')->nullable();
            $table->string('uptime')->nullable();
            $table->string('total_ram')->nullable();
            $table->string('free_ram')->nullable();
            $table->integer('procs')->nullable();
        });

        // clients table
        Schema::table('clients', function(Blueprint $table) {
            $table->string('interface')->nullable();
            $table->string('ssid')->nullable();
            $table->string('mac')->nullable();
            $table->bigInteger('rx_packets')->nullable();
            $table->bigInteger('tx_packets')->nullable();
            $table->bigInteger('rx_bytes')->nullable();
            $table->bigInteger('tx_bytes')->nullable();
            $table->bigInteger('tx_retries')->nullable();
            $table->bigInteger('tx_failed')->nullable();
            $table->bigInteger('beacon_rx')->nullable();
            $table->bigInteger('beacon_loss')->nullable();
            $table->integer('signal_avg')->nullable();
            $table->integer('tx_bitrate')->nullable();
            $table->integer('rx_bitrate')->nullable();
            $table->boolean('authorized')->nullable();
            $table->boolean('authenticated')->nullable();
            $table->boolean('associated')->nullable();
            $table->boolean('wmm')->nullable();
            $table->boolean('mfp')->nullable();
            $table->boolean('tdls')->nullable();
            $table->integer('preamble')->nullable();
            $table->integer('conn_time')->nullable();
            $table->integer('expected_tput')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // boxes table
        Schema::table('boxes', function(Blueprint $table) {
            $table->dropColumn('firmware');
            $table->dropColumn('serial');
            $table->dropColumn('wan_name');
            $table->dropColumn('machine_type');
            $table->dropColumn('tx_bytes');
            $table->dropColumn('rx_bytes');
            $table->dropColumn('uptime');
            $table->dropColumn('total_ram');
            $table->dropColumn('free_ram');
            $table->dropColumn('procs');
        });

        // clients table
        Schema::table('clients', function(Blueprint $table) {
            $table->dropColumn('interface');
            $table->dropColumn('ssid');
            $table->dropColumn('mac');
            $table->dropColumn('rx_packets');
            $table->dropColumn('tx_packets');
            $table->dropColumn('rx_bytes');
            $table->dropColumn('tx_bytes');
            $table->dropColumn('beacon_rx');
            $table->dropColumn('tx_retries');
            $table->dropColumn('tx_failed');
            $table->dropColumn('beacon_loss');
            $table->dropColumn('signal_avg');
            $table->dropColumn('tx_bitrate');
            $table->dropColumn('rx_bitrate');
            $table->dropColumn('authorized');
            $table->dropColumn('authenticated');
            $table->dropColumn('associated');
            $table->dropColumn('wmm');
            $table->dropColumn('mfp');
            $table->dropColumn('tdls');
            $table->dropColumn('preamble');
            $table->dropColumn('conn_time');
            $table->dropColumn('expected_tput');
        });

    }
}
