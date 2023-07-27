<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailDraft extends Model
{
    use SoftDeletes;

    protected $table = "mail_drafts";
}
