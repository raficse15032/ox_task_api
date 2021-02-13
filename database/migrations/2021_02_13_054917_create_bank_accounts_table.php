<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('financial_organization_id')->unsigned();
            $table->bigInteger('store_id')->null();
            $table->string('account_name');
            $table->string('account_no')->null();
            $table->string('branch')->null();
            $table->integer('account_type')->null();
            $table->string('swift_code')->null();
            $table->string('route_no')->null();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreign('financial_organization_id')->references('id')->on('financial_organizations')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
