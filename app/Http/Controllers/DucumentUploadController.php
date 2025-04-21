<?php

namespace App\Http\Controllers;

use App\Models\DucumentUpload;
use Illuminate\Http\Request;
use App\Models\Utility;
use Spatie\Permission\Models\Role;

class DucumentUploadController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('Manage Document')) {
            if (\Auth::user()->type == 'company') {
                $documents = DucumentUpload::where('created_by', \Auth::user()->creatorId())->get();
            } else {
                $userRole  = \Auth::user()->roles->first();
                $documents = DucumentUpload::whereIn(
                    'role',
                    [
                        $userRole->id,
                        0,
                    ]
                )->where('created_by', \Auth::user()->creatorId())->get();
            }

            return view('documentUpload.index', compact('documents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('Create Document')) {
            $roles = Role::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $roles->prepend('All', '0');

            return view('documentUpload.create', compact('roles'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Document')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'role' => 'required',
                    'documents' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $roles = $request->role;

            $document              = new DucumentUpload();
            $document->name        = $request->name;
            $document->role        = $request->role;
            $document->description = $request->description;
            $document->created_by  = \Auth::user()->creatorId();
            if (!empty($request->documents)) {
                $image_size = $request->file('documents')->getSize();

                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1) {
                    $filenameWithExt = $request->file('documents')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('documents')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/documentUpload/';
                    $image_path = $dir . $fileNameToStore;

                    $url = '';
                    $path = Utility::upload_file($request, 'documents', $fileNameToStore, $dir, []);
                    $document->document    = !empty($request->documents) ? $fileNameToStore : '';
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }
            $document->save();

            // return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(DucumentUpload $ducumentUpload)
    {
        //
    }


    public function edit($id)
    {
        if (\Auth::user()->can('Edit Document')) {
            $roles = Role::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $roles->prepend('All', '0');

            $ducumentUpload = DucumentUpload::find($id);

            return view('documentUpload.edit', compact('roles', 'ducumentUpload'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Document')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $roles = $request->role;

            $document = DucumentUpload::find($id);
            $document->name = $request->name;
            $document->role        = $request->role;
            $document->description = $request->description;

            if (!empty($request->documents)) {

                //storage limit
                $dir = 'uploads/documentUpload/';
                $file_path = $dir . $document->document;
                $image_size = $request->file('documents')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if ($result == 1) {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    $filenameWithExt = $request->file('documents')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('documents')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir = 'uploads/documentUpload/';
                    $image_path = $dir . $fileNameToStore;

                    $url = '';
                    $path = Utility::upload_file($request, 'documents', $fileNameToStore, $dir, []);
                    $document->document = !empty($request->documents) ? $fileNameToStore : '';
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            $document->save();

            // return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('Delete Document')) {
            $document = DucumentUpload::find($id);
            if ($document->created_by == \Auth::user()->creatorId()) {
                $document->delete();


                if (!empty($document->document)) {
                    // $dir = storage_path('uploads/documentUpload/');

                    //storage limit
                    $file_path = 'uploads/documentUpload/' . $document->document;
                    $result = Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);
                    // unlink($dir . $document->document);
                }

                return redirect()->route('document-upload.index')->with('success', __('Document successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
