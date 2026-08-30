<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class AgentSender extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'agent_senders';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agents_id', 'senders_sender_id', 'comments'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

