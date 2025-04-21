<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'purchase_date',
        'supported_date',
        'amount',
        'description',
        'created_by',
    ];

    public function users($users)
    {
        $userArr = explode(',', $users);
        $emp = Employee::whereIn('id', $userArr);
        $employees = $emp->get()->pluck('id');
        $users  = [];
        foreach($employees as $user)
        {
            $emp=Employee::find($user);
            $users[] = User::where('id',$emp->user_id)->first();
        }

        return $users;
    }
}
