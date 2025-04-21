<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'url',
        'method',
        'created_by',
    ];

    public static $modules = [
        'New Monthly Payslip' => 'New Monthly Payslip',
        'New Announcement' => 'New Announcement',
        'New Meeting' => 'New Meeting',
        'New Award' => 'New Award',
        'New Holidays' => 'New Holidays',
        'New Company Policy' => 'New Company Policy',
        'New Ticket' => 'New Ticket',
        'New Event' => 'New Event',
        'New Contract' => 'New Contract',
    ];

    public static $methods = [
        'GET' => 'GET',
        'POST' => 'POST',
    ];

}
