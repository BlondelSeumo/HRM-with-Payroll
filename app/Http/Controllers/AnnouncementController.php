<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;

use function App\Models\WebhookCall;

class AnnouncementController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Announcement')) {

            if (Auth::user()->type == 'employee') {
                $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $announcements    = Announcement::orderBy('announcements.id', 'desc')->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $current_employee->id)->orWhere(
                    function ($q) {
                        $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]');
                    }
                )->get();
            } else {
                $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $announcements    = Announcement::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('announcement.index', compact('announcements', 'current_employee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Announcement')) {

            $employees = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees->prepend('All', 0);

            // $branch = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            // $branch->prepend('All', 0);

            // $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            // $departments->prepend('All', 0);

            // $employees   = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branch      = Branch::where('created_by', '=', Auth::user()->creatorId())->get();
            $departments = Department::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('announcement.create', compact('employees', 'branch', 'departments'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Announcement')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required|after_or_equal:start_date',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'employee_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $announcement                = new Announcement();
            $announcement->title         = $request->title;
            $announcement->start_date    = $request->start_date;
            $announcement->end_date      = $request->end_date;
            $announcement->branch_id     = $request->branch_id;
            $announcement->department_id = implode(",", $request->department_id);
            $announcement->employee_id   = implode(",", $request->employee_id);
            // $announcement->department_id = json_encode($request->department_id);
            // $announcement->employee_id   = json_encode($request->employee_id);
            $announcement->description   = $request->description;
            $announcement->created_by    = \Auth::user()->creatorId();
            $announcement->save();

            // slack
            $setting = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if (isset($setting['Announcement_notification']) && $setting['Announcement_notification'] == 1) {
                // $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';

                $uArr = [
                    'announcement_title' => $request->title,
                    'branch_name' => $branch->name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];

                Utility::send_slack_msg('new_announcement', $uArr);
            }

            // telegram
            $setting = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if (isset($setting['telegram_Announcement_notification']) && $setting['telegram_Announcement_notification'] == 1) {
                // $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';

                $uArr = [
                    'announcement_title' => $request->title,
                    'branch_name' => $branch->name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ];

                Utility::send_telegram_msg('new_announcement', $uArr);
            }
            // twilio
            $setting = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            $departments = Department::where('branch_id', $request->branch_id)->first();
            $employees = Employee::where('branch_id', $request->branch_id)->first();

            if (isset($setting['twilio_announcement_notification']) && $setting['twilio_announcement_notification'] == 1) {
                // $employeess = Employee::whereIn('branch_id', $request->employee_id)->get();
                // foreach ($employeess as $key => $employee) {
                    // $msg = $request->title . ' ' . __("announcement created for branch") . ' ' . $branch->name . ' ' . __("from") . ' ' . $request->start_date . ' ' . __("to") . ' ' . $request->end_date . '.';

                    $uArr = [
                        'announcement_title' => $request->title,
                        'branch_name' => $branch->name,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                    ];

                    Utility::send_twilio_msg($employees->phone, 'new_announcement', $uArr);
                // }
            }

            if (in_array('0', $request->employee_id)) {
                $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->get()->pluck('id');
                $departmentEmployee = $departmentEmployee;
            } else {
                $departmentEmployee = $request->employee_id;
            }
            foreach ($departmentEmployee as $employee) {
                $announcementEmployee                  = new AnnouncementEmployee();
                $announcementEmployee->announcement_id = $announcement->id;
                $announcementEmployee->employee_id     = $employee;
                $announcementEmployee->created_by      = \Auth::user()->creatorId();

                $announcementEmployee->save();
            }

            //webhook
            $module = 'New Announcement';
            $webhook =  Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($announcement);
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);

                if ($status == true) {
                    return redirect()->back()->with('success', __('Announcement successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }

            return redirect()->route('announcement.index')->with('success', __('Announcement successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Announcement $announcement)
    {
        return redirect()->route('announcement.index');
    }

    public function edit($announcement)
    {
        if (\Auth::user()->can('Edit Announcement')) {
            $announcement = Announcement::find($announcement);
            if ($announcement->created_by == Auth::user()->creatorId()) {
                $branch      = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                return view('announcement.edit', compact('announcement', 'branch', 'departments', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (\Auth::user()->can('Edit Announcement')) {
            if ($announcement->created_by == \Auth::user()->creatorId()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                        'branch_id' => 'required',
                        'department_id' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $announcement->title         = $request->title;
                $announcement->start_date    = $request->start_date;
                $announcement->end_date      = $request->end_date;
                $announcement->branch_id     = $request->branch_id;
                $announcement->department_id = implode(",", $request->department_id);
                $announcement->employee_id   = ($request->has('employee_id') && is_array($request->employee_id)) ? implode(",", $request->employee_id) : 'all';
                $announcement->description   = $request->description;
                $announcement->save();

                $existingEmployees = AnnouncementEmployee::where('announcement_id', $announcement->id)
                    ->where('created_by', \Auth::user()->creatorId())
                    ->pluck('employee_id')
                    ->toArray();

                if ($request->employee_id) {
                     if (in_array('0', $request->employee_id)) {
                        $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->pluck('id');
                    } else {
                        $departmentEmployee = $request->employee_id;
                    }

                    AnnouncementEmployee::where('announcement_id', $announcement->id)
                    ->where('created_by', \Auth::user()->creatorId())
                    ->whereNotIn('employee_id', $departmentEmployee)
                    ->delete();
                    foreach ($departmentEmployee as $employee) {
                        AnnouncementEmployee::updateOrCreate(
                            [
                                'announcement_id' => $announcement->id,
                                'employee_id'     => $employee,
                                'created_by'      => \Auth::user()->creatorId(),
                            ],
                            [
                                'announcement_id' => $announcement->id,
                                'employee_id'     => $employee,
                                'created_by'      => \Auth::user()->creatorId(),
                            ]
                        );
                    }
                } else {
                    AnnouncementEmployee::where('announcement_id', $announcement->id)
                        ->where('created_by', \Auth::user()->creatorId())
                        ->delete();
                }
                return redirect()->back()->with('success', __('Announcement successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Announcement $announcement)
    {
        if (\Auth::user()->can('Delete Announcement')) {
            if ($announcement->created_by == \Auth::user()->creatorId()) {
                $announcement->delete();

                return redirect()->route('announcement.index')->with('success', __('Announcement successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getdepartment(Request $request)
    {

        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {

        if ($request->department_id) {

            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->where('department_id', $request->department_id)->get()->pluck('name', 'id');
        } else {
            $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        }
        return response()->json($employees);
    }
}
