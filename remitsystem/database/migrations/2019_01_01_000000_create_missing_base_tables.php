<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tables with ALTER TABLE migrations - only create base, ALTERs add columns
        Schema::create('australian_states', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('dob')->nullable();
            $table->string('sex')->nullable();
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('agent_exchange_rate_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('image');
            $table->integer('sort_order');
        });

        Schema::create('bank_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('shelf_location')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('exchange_rate', 10, 2);
            $table->decimal('cost_rate', 10, 2);
            $table->decimal('agent_rate', 10, 2);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('sender_status_id');
            $table->decimal('service_charge', 10, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_details_id');
            $table->unsignedBigInteger('transaction_status_id');
            $table->unsignedBigInteger('sender_identification_id')->nullable();
            $table->unsignedBigInteger('beneficiary_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('sender_addresses_id')->nullable();
            $table->unsignedBigInteger('beneficiary_addresses_id')->nullable();
            $table->unsignedBigInteger('sender_phones_id')->nullable();
            $table->unsignedBigInteger('beneficiary_phones_id')->nullable();
            $table->unsignedBigInteger('beneficiaries_bank_details_id')->nullable();
            $table->unsignedBigInteger('sender_companies_id')->nullable();
            $table->string('pickup_district')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('value')->nullable();
        });

        // Model tables without any ALTER TABLE migrations
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street')->nullable();
            $table->string('suburb')->nullable();
            $table->string('postcode')->nullable();
            $table->string('state')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('country_list_id')->nullable();
        });

        Schema::create('address_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
        });

        Schema::create('agent_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_transactions_id')->nullable();
            $table->unsignedBigInteger('agent_payments_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('agent_exchange_rate', function (Blueprint $table) {
            $table->id();
            $table->decimal('less_than_service_charge', 10, 2)->nullable();
            $table->decimal('more_than_service_charge', 10, 2)->nullable();
            $table->decimal('sending_amount_threshold', 10, 2)->nullable();
        });

        Schema::create('agent_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('method')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
        });

        Schema::create('agent_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agents_id')->nullable();
            $table->unsignedBigInteger('transactions_id')->nullable();
            $table->decimal('exchange_rate', 10, 2)->nullable();
            $table->decimal('service_charge', 10, 2)->nullable();
            $table->decimal('total_commission', 10, 2)->nullable();
        });

        Schema::create('agent_senders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agents_id')->nullable();
            $table->unsignedBigInteger('senders_sender_id')->nullable();
            $table->text('comments')->nullable();
        });

        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('assign_distributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transactions_id')->nullable();
            $table->unsignedBigInteger('distributor_office_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
        });

        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bsb')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('current')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id('beneficiary_id');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->date('created_date')->nullable();
        });

        Schema::create('beneficiary_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_details_id')->nullable();
            $table->unsignedBigInteger('beneficiaries_beneficiary_id')->nullable();
            $table->boolean('current')->nullable();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('addresses_id')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('abn')->nullable();
        });

        Schema::create('company_bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_details_id')->nullable();
            $table->unsignedBigInteger('companies_id')->nullable();
            $table->boolean('current')->nullable();
        });

        Schema::create('country_list', function (Blueprint $table) {
            $table->id();
            $table->string('country_code')->nullable();
            $table->string('name')->nullable();
        });

        Schema::create('cron_email', function (Blueprint $table) {
            $table->id();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->string('group')->nullable();
        });

        Schema::create('cron_sms', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->string('group')->nullable();
            $table->string('status')->nullable();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('email')->nullable();
        });

        Schema::create('customers_senders', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('distributor_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributor_transactions_id')->nullable();
            $table->unsignedBigInteger('distributor_payments_id')->nullable();
            $table->text('text')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('distributor_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companies_id')->nullable();
            $table->boolean('active')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('distributor_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributor_company_id')->nullable();
            $table->string('date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('method')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->decimal('cost_rate', 10, 2)->nullable();
        });

        Schema::create('distributor_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('distributor_office_id')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('assigned_date')->nullable();
            $table->decimal('cost_rate', 10, 2)->nullable();
        });

        Schema::create('distributor_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('distributor_office_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('from')->nullable();
            $table->string('receiver')->nullable();
            $table->string('subject')->nullable();
            $table->text('email_message')->nullable();
            $table->timestamps();
            $table->string('status')->nullable();
        });

        Schema::create('identification_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id')->nullable();
        });

        Schema::create('identification_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('points')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('identifications', function (Blueprint $table) {
            $table->id();
            $table->date('expiry_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->string('id_number')->nullable();
            $table->unsignedBigInteger('identification_documents_id')->nullable();
            $table->unsignedBigInteger('senders_id')->nullable();
            $table->unsignedBigInteger('identification_status_id')->nullable();
            $table->boolean('current')->nullable();
            $table->unsignedBigInteger('identification_types_id')->nullable();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('transactions_id')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('notes_assign', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notes_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->boolean('is_read')->nullable();
        });

        Schema::create('person_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('person_id')->nullable();
            $table->boolean('current')->nullable();
            $table->unsignedBigInteger('address_status_id')->nullable();
        });

        Schema::create('person_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phones_id')->nullable();
            $table->boolean('current')->nullable();
            $table->unsignedBigInteger('person_id')->nullable();
        });

        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
        });

        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
        });

        Schema::create('sender_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('beneficiary_id')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('sender_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senders_sender_id')->nullable();
            $table->unsignedBigInteger('companies_id')->nullable();
            $table->boolean('active')->nullable();
        });

        Schema::create('sender_status', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('favcon')->nullable();
            $table->decimal('sms_fee', 10, 2)->nullable();
            $table->integer('sms_credit')->nullable();
            $table->decimal('service_charge', 10, 2)->nullable();
            $table->string('company_name')->nullable();
            $table->string('abn')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('street')->nullable();
            $table->string('suburb')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('bsb')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
        });

        Schema::create('sms_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->string('send_from')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('sms_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payment_type')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('stripe_transaction_id')->nullable();
            $table->integer('sms_credit')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date')->nullable();
            $table->decimal('cost_rate', 10, 2)->nullable();
            $table->decimal('sending_amount', 10, 2)->nullable();
            $table->decimal('exchange_rate', 10, 2)->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->decimal('service_charge', 10, 2)->nullable();
            $table->decimal('total_to_pay', 10, 2)->nullable();
            $table->string('receive_type')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('receive_bank_name')->nullable();
            $table->string('purpose_of_transfer')->nullable();
            $table->text('staff_notes')->nullable();
            $table->text('admin_staff_notes')->nullable();
        });

        Schema::create('transaction_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_name')->nullable();
            $table->string('file_name')->nullable();
            $table->date('created_date')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('transaction_document_type_id')->nullable();
            $table->unsignedBigInteger('transactions_id')->nullable();
        });

        Schema::create('user_ins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $tables = [
            'australian_states', 'person', 'agents', 'sliders', 'bank_lists',
            'documents', 'exchange_rates', 'senders', 'transactions', 'levels',
            'addresses', 'address_status', 'agent_accounts', 'agent_exchange_rate',
            'agent_payments', 'agent_transactions', 'agent_senders', 'apis',
            'assign_distributors', 'bank_details', 'beneficiaries',
            'beneficiary_bank_details', 'companies', 'company_bank_details',
            'country_list', 'cron_email', 'cron_sms', 'customers', 'customers_senders',
            'distributors', 'distributor_accounts', 'distributor_offices',
            'distributor_payments', 'distributor_transactions', 'distributor_users',
            'email_logs', 'identification_documents', 'identification_types',
            'identifications', 'notes', 'notes_assign', 'person_address',
            'person_phones', 'phones', 'receivers', 'sender_beneficiaries',
            'sender_companies', 'sender_status', 'settings', 'sms_log',
            'sms_payments', 'status', 'transaction_details', 'transaction_documents',
            'user_ins',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
