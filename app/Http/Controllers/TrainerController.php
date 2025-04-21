<?php

namespace App\Http\Controllers;

use App\Exports\TrainerExport;
use App\Imports\TrainerImport;
use App\Models\Branch;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TrainerController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('Manage Trainer')) {
            $trainers = Trainer::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('trainer.index', compact('trainers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('Create Trainer')) {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.create', compact('branches'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Trainer')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'contact' => 'required',
                    'email' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainer             = new Trainer();
            $trainer->branch     = $request->branch;
            $trainer->firstname  = $request->firstname;
            $trainer->lastname   = $request->lastname;
            $trainer->contact    = $request->contact;
            $trainer->email      = $request->email;
            $trainer->address    = $request->address;
            $trainer->expertise  = $request->expertise;
            $trainer->created_by = \Auth::user()->creatorId();
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Trainer $trainer)
    {
        return view('trainer.show', compact('trainer'));
    }


    public function edit(Trainer $trainer)
    {
        if (\Auth::user()->can('Edit Trainer')) {
            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('trainer.edit', compact('branches', 'trainer'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Trainer $trainer)
    {
        if (\Auth::user()->can('Edit Trainer')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'contact' => 'required',
                    'email' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $trainer->branch    = $request->branch;
            $trainer->firstname = $request->firstname;
            $trainer->lastname  = $request->lastname;
            $trainer->contact   = $request->contact;
            $trainer->email     = $request->email;
            $trainer->address   = $request->address;
            $trainer->expertise = $request->expertise;
            $trainer->save();

            return redirect()->route('trainer.index')->with('success', __('Trainer  successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Trainer $trainer)
    {
        if (\Auth::user()->can('Delete Trainer')) {
            if ($trainer->created_by == \Auth::user()->creatorId()) {
                $trainer->delete();

                return redirect()->route('trainer.index')->with('success', __('Trainer successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function export()
    {
        $name = 'trainer_' . date('Y-m-d i:h:s');
        $data = Excel::download(new TrainerExport(), $name . '.xlsx');


        return $data;
    }
    public function importFile(Request $request)
    {
        return view('trainer.import');
    }
    // public function import(Request $request)
    // {

    //     $rules = [
    //         'file' => 'required|mimes:csv,txt',
    //     ];
    //     $validator = \Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         $messages = $validator->getMessageBag();

    //         return redirect()->back()->with('error', $messages->first());
    //     }

    //     try {
    //         $trainer = (new TrainerImport())->toArray(request()->file('file'))[0];

    //         $totaltrainer = count($trainer) - 1;
    //         $errorArray    = [];

    //         for ($i = 1; $i <= $totaltrainer; $i++) {
    //             $trainers = $trainer[$i];

    //             $trainersData = Trainer::where('email', $trainers[4])->first();


    //             if (!empty($trainersData)) {
    //                 $errorArray[] = $trainersData;
    //             } else {
    //                 $trainer_data = new Trainer();
    //                 $getBranchId = Branch::where('name', $trainers[0])->first();
    //                 $trainer_data->branch = !empty($getBranchId->id) ? $getBranchId->id : '';
    //                 $trainer_data->firstname = $trainers[1];
    //                 $trainer_data->lastname = $trainers[2];
    //                 $trainer_data->contact = $trainers[3];
    //                 $trainer_data->email = $trainers[4];
    //                 $trainer_data->address = $trainers[5];
    //                 $trainer_data->expertise = $trainers[6];
    //                 $trainer_data->created_by = Auth::user()->id;
    //                 $trainer_data->save();
    //             }
    //         }
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', __('Something went wrong please try again.'));
    //     }

    //     if (empty($errorArray)) {
    //         $data['status'] = 'success';
    //         $data['msg']    = __('Record successfully imported');
    //     } else {

    //         $data['status'] = 'error';
    //         $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totaltrainer . ' ' . 'record');


    //         foreach ($errorArray as $errorData) {
    //             $errorRecord[] = implode(',', $errorData->toArray());
    //         }

    //         \Session::put('errorArray', $errorRecord);
    //     }

    //     return redirect()->back()->with($data['status'], $data['msg']);
    // }

    public function fileImport(Request $request)
    {
        session_start();

        $error = '';

        $html = '';

        if ($request->hasFile('file') && $request->file->getClientOriginalName() != '') {
            $file_array = explode(".", $request->file->getClientOriginalName());

            $extension = end($file_array);
            if ($extension == 'csv') {
                $file_data = fopen($request->file->getRealPath(), 'r');

                $file_header = fgetcsv($file_data);

                $html .= '<table class="table table-bordered"><tr>';

                for ($count = 0; $count < count($file_header); $count++) {
                    $html .= '
                            <th>
                                <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                <option value="">Set Count Data</option>
                                <option value="firstname">First Name</option>
                                <option value="lastname">Last name</option>
                                <option value="contact">Contact</option>
                                <option value="email">Email ID</option>
                                <option value="address">Address</option>
                                <option value="expertise">Experience</option>
                                </select>
                            </th>
                            ';
                }
                $html .= '
                            <th>
                                    <select name="set_column_data branch_name" class="form-control set_column_data branch-name" data-column_number="' . $count . '">
                                        <option value="branch">Branch</option>
                                    </select>
                            </th>
                            ';
                $html .= '</tr>';
                $limit = 0;
                $temp_data = [];
                while (($row = fgetcsv($file_data)) !== false) {
                    $limit++;

                    $html .= '<tr>';

                    for ($count = 0; $count < count($row); $count++) {
                        $html .= '<td>' . $row[$count] . '</td>';
                    }

                    $html .= '<td>
                                <select name="branch_name" class="form-control branch-name-value" id="branch_name" required>;';
                    $branchs = Branch::where('created_by', \Auth::user()->id)->pluck('name', 'id');
                    foreach ($branchs as $key => $branch) {
                        $html .= ' <option value="' . $key . '">' . $branch . '</option>';
                    }
                    $html .= '  </select>
                            </td>';

                    $html .= '</tr>';

                    $temp_data[] = $row;
                }
                $_SESSION['file_data'] = $temp_data;
            } else {
                $error = 'Only <b>.csv</b> file allowed';
            }
        } else {

            $error = 'Please Select CSV File';
        }
        $output = array(
            'error' => $error,
            'output' => $html,
        );

        return json_encode($output);
    }

    public function fileImportModal()
    {
        return view('trainer.import_modal');
    }

    public function trainerImportdata(Request $request)
    {
        session_start();
        $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
        $flag = 0;
        $html .= '<table class="table table-bordered"><tr>';
        try {
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);
        } catch (\Throwable $th) {
            $html = '<h3 class="text-danger text-center">Something went wrong, Please try again</h3></br>';
            return response()->json([
                'html' => true,
                'response' => $html,
            ]);
        }

        $user = Auth::user();
        foreach ($file_data as $key => $row) {
            $trainers = Trainer::where('created_by', \Auth::user()->creatorId())->Where('email', 'like', $row[$request['email']])->get();
            $branch = Branch::find($request->branch[$key]);

            if ($trainers->isEmpty()) {
                try {
                    Trainer::create([
                        'firstname' => $row[$request['firstname']],
                        'lastname' => $row[$request['lastname']],
                        'contact' => $row[$request['contact']],
                        'email' => $row[$request['email']],
                        'address' => $row[$request['address']],
                        'expertise' => $row[$request['expertise']],
                        'branch' => !empty($branch) ? $branch->id : 0,
                        'created_by' => \Auth::user()->creatorId(),
                    ]);
                } catch (\Exception $e) {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . (isset($row[$request['firstname']]) ? $row[$request['firstname']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$request['lastname']]) ? $row[$request['lastname']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$request['contact']]) ? $row[$request['contact']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$request['email']]) ? $row[$request['email']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$request['address']]) ? $row[$request['address']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$request['expertise']]) ? $row[$request['expertise']] : '-') . '</td>';
                    $html .= '<td>' . (isset($row[$branch->id]) ? $row[$branch->id] : '-') . '</td>';

                    $html .= '</tr>';
                }
            } else {
                $flag = 1;
                $html .= '<tr>';

                $html .= '<td>' . (isset($row[$request['firstname']]) ? $row[$request['firstname']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$request['lastname']]) ? $row[$request['lastname']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$request['contact']]) ? $row[$request['contact']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$request['email']]) ? $row[$request['email']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$request['address']]) ? $row[$request['address']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$request['expertise']]) ? $row[$request['expertise']] : '-') . '</td>';
                $html .= '<td>' . (isset($row[$branch->id]) ? $row[$branch->id] : '-') . '</td>';

                $html .= '</tr>';
            }
        }

        $html .= '
                        </table>
                        <br />
                        ';
        if ($flag == 1) {

            return response()->json([
                'html' => true,
                'response' => $html,
            ]);
        } else {
            return response()->json([
                'html' => false,
                'response' => 'Data Imported Successfully',
            ]);
        }
    }
}
