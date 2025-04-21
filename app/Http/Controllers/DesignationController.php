<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
{
    public function index()
    {

        if (\Auth::user()->can('Manage Designation')) {
            $designations = Designation::where('created_by', '=', \Auth::user()->creatorId())->with('department')->get();

            return view('designation.index', compact('designations'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Designation')) {
            $branchs     = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branchs->prepend('Select Branch', '');
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('Select Department', '');

            return view('designation.create', compact('branchs', 'departments'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if (\Auth::user()->can('Create Designation')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            try {
                $branch = Department::where('id', $request->department_id)->where('created_by', '=', Auth::user()->creatorId())->first()->branch->id;
            } catch (Exception $e) {
                $branch = null;
            }

            $designation                = new Designation();
            $designation->branch_id     = $branch;
            $designation->department_id = $request->department_id;
            $designation->name          = $request->name;
            $designation->created_by    = \Auth::user()->creatorId();

            $designation->save();

            return redirect()->route('designation.index')->with('success', __('Designation  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Designation $designation)
    {
        return redirect()->route('designation.index');
    }

    public function edit(Designation $designation)
    {

        if (\Auth::user()->can('Edit Designation')) {
            if ($designation->created_by == \Auth::user()->creatorId()) {

                if (!empty($designation->branch_id)) {
                    $branchs     = Branch::where('id', $designation->branch_id)->first()->pluck('name', 'id');
                    $branchs->prepend('Select Branch', '');
                } else {
                    $branchs     = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $branchs->prepend('Select Branch', '');
                }
                $departments = Department::where('id', $designation->department_id)->first()->pluck('name', 'id');
                $departments->prepend('Select Department', '');

                return view('designation.edit', compact('designation', 'departments', 'branchs'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Designation $designation)
    {
        if (\Auth::user()->can('Edit Designation')) {
            if ($designation->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'branch_id' => 'required',
                        'department_id' => 'required',
                        'name' => 'required|max:20',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                try {
                    $branch = Department::where('id', $request->department_id)->where('created_by', '=', Auth::user()->creatorId())->first()->branch->id;
                } catch (Exception $e) {
                    $branch = null;
                }

                $designation->name          = $request->name;
                $designation->branch_id     = $branch;
                $designation->department_id = $request->department_id;
                $designation->save();

                return redirect()->route('designation.index')->with('success', __('Designation  successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Designation $designation)
    {
        if (\Auth::user()->can('Delete Designation')) {
            $employee     = Employee::where('designation_id', $designation->id)->get();
            if (count($employee) == 0) {
                if ($designation->created_by == \Auth::user()->creatorId()) {
                    $designation->delete();

                    return redirect()->route('designation.index')->with('success', __('Designation successfully deleted.'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            } else {
                return redirect()->route('designation.index')->with('error', __('This designation has employees. Please remove the employee from this designation.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
