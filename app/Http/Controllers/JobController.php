<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CustomQuestion;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationNote;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\GenerateOfferLetter;
use App\Models\Utility;
use App\Models\JobStage;


class JobController extends Controller
{

    public function index($lang = 'en')
    {
        if (\Auth::user()->can('Manage Job')) {
            $jobs = Job::where('created_by', '=', \Auth::user()->creatorId())->get();

            $data['total']     = Job::where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['active']    = Job::where('status', 'active')->where('created_by', '=', \Auth::user()->creatorId())->count();
            $data['in_active'] = Job::where('status', 'in_active')->where('created_by', '=', \Auth::user()->creatorId())->count();
            $Offerletter = GenerateOfferLetter::all();
            $currOfferletterTempLang = GenerateOfferLetter::where('created_by',  \Auth::user()->id)->where('lang', $lang)->first();

            $langs = $lang;


            return view('job.index', compact('jobs', 'data', 'currOfferletterTempLang', 'langs'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Job')) {
            $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
            $categories->prepend('--', '');

            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $status = Job::$status;

            $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

            return view('job.create', compact('categories', 'status', 'branches', 'customQuestion'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Job')) {

            $rules = [
                'title' => 'required',
                'branch' => 'required',
                'category' => 'required',
                'skill' => 'required',
                'position' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
                'requirement' => 'required',
                'custom_question.*' => 'required',
            ];

            if (isset($request->visibility) && in_array('terms', $request->visibility)) {
                $rules['terms_and_conditions'] = 'required';
            }

            $validator = \Validator::make(
                $request->all(),
                $rules
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $job                       = new Job();
            $job->title                = $request->title;
            $job->branch               = $request->branch;
            $job->category             = $request->category;
            $job->skill                = $request->skill;
            $job->position             = $request->position;
            $job->status               = $request->status;
            $job->start_date           = $request->start_date;
            $job->end_date             = $request->end_date;
            $job->description          = $request->description;
            $job->requirement          = $request->requirement;
            $job->terms_and_conditions = !empty($request->terms_and_conditions) ? $request->terms_and_conditions : '';
            $job->code                 = uniqid();
            $job->applicant            = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $job->visibility           = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $job->custom_question      = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $job->created_by           = \Auth::user()->creatorId();
            $job->save();

            return redirect()->route('job.index')->with('success', __('Job  successfully created.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }

    public function show(Job $job)
    {
        if (\Auth::user()->can('Show Job')) {
            $status          = Job::$status;
            $job->applicant  = !empty($job->applicant) ? explode(',', $job->applicant) : '';
            $job->visibility = !empty($job->visibility) ? explode(',', $job->visibility) : '';
            $job->skill      = !empty($job->skill) ? explode(',', $job->skill) : '';

            return view('job.show', compact('status', 'job'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }

    public function edit(Job $job)
    {
        if (\Auth::user()->can('Edit Job')) {
            $categories = JobCategory::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
            $categories->prepend('--', '');

            $branches = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            $status = Job::$status;

            $job->applicant       = explode(',', $job->applicant);
            $job->visibility      = explode(',', $job->visibility);
            $job->custom_question = explode(',', $job->custom_question);

            $customQuestion = CustomQuestion::where('created_by', \Auth::user()->creatorId())->get();

            return view('job.edit', compact('categories', 'status', 'branches', 'job', 'customQuestion'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, Job $job)
    {
        if (\Auth::user()->can('Edit Job')) {

            $rules = [
                'title' => 'required',
                'branch' => 'required',
                'category' => 'required',
                'skill' => 'required',
                'position' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
                'requirement' => 'required',
            ];

            if (isset($request->visibility) && in_array('terms', $request->visibility)) {
                $rules['terms_and_conditions'] = 'required';
            }

            $validator = \Validator::make(
                $request->all(),
                $rules
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $job->title           = $request->title;
            $job->branch          = $request->branch;
            $job->category        = $request->category;
            $job->skill           = $request->skill;
            $job->position        = $request->position;
            $job->status          = $request->status;
            $job->start_date      = $request->start_date;
            $job->end_date        = $request->end_date;
            $job->description     = $request->description;
            $job->requirement     = $request->requirement;
            $job->terms_and_conditions = !empty($request->terms_and_conditions) ? $request->terms_and_conditions : '';
            $job->applicant       = !empty($request->applicant) ? implode(',', $request->applicant) : '';
            $job->visibility      = !empty($request->visibility) ? implode(',', $request->visibility) : '';
            $job->custom_question = !empty($request->custom_question) ? implode(',', $request->custom_question) : '';
            $job->save();

            return redirect()->route('job.index')->with('success', __('Job  successfully updated.'));
        } else {
            return redirect()->route('job.index')->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Job $job)
    {
        $application = JobApplication::where('job', $job->id)->get()->pluck('id');
        JobApplicationNote::whereIn('application_id', $application)->delete();
        JobApplication::where('job', $job->id)->delete();
        $job->delete();

        return redirect()->route('job.index')->with('success', __('Job  successfully deleted.'));
    }

    public function career($id, $lang)
    {
        $jobs = Job::where('created_by', $id)->with('createdBy')->get();
        \Session::put('lang', $lang);

        \App::setLocale($lang);

        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $id)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $id)->where('name', 'footer_text')->first();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $id)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $id)->where('name', 'company_logo')->first();
        $companySettings['metakeyword']     = \DB::table('settings')->where('created_by', $id)->where('name', 'metakeyword')->first();
        $companySettings['metadesc']        = \DB::table('settings')->where('created_by', $id)->where('name', 'metadesc')->first();
        $languages                          = Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $user        = User::find($id);
            $currantLang = !empty($user) && !empty($user->lang) ? $user->lang : 'en';
        }


        return view('job.career', compact('companySettings', 'jobs', 'languages', 'currantLang', 'id'));
    }

    public function jobRequirement($code, $lang)
    {
        try {
            $job = Job::where('code', $code)->first();
            if ($job->status == 'in_active') {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Page Not Found.'));
        }

        \Session::put('lang', $lang);

        \App::setLocale($lang);

        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'footer_text')->first();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_logo')->first();
        $companySettings['metakeyword']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'metakeyword')->first();
        $companySettings['metadesc']        = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'metadesc')->first();
        $languages                          = Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($job->createdBy) ? $job->createdBy->lang : 'en';
        }


        return view('job.requirement', compact('companySettings', 'job', 'languages', 'currantLang'));
    }

    public function jobApply($code, $lang)
    {
        \Session::put('lang', $lang);

        \App::setLocale($lang);
        try {
            $job = Job::where('code', $code)->first();
            $companySettings['title_text']      = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'title_text')->first();
            $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'footer_text')->first();
            $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_favicon')->first();
            $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $job->created_by)->where('name', 'company_logo')->first();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Page not Found');
        }


        $que = !empty($job->custom_question) ? explode(",", $job->custom_question) : [];

        $questions = CustomQuestion::wherein('id', $que)->get();

        $languages = Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($job->createdBy) ? $job->createdBy->lang : 'en';
        }

        return view('job.apply', compact('companySettings', 'job', 'questions', 'languages', 'currantLang'));
    }

    public function TermsAndCondition($id)
    {
        try {
            $job = Job::where('id', $id)->first();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Page not Found');
        }

        return view('job.terms', compact('job'));
    }

    public function jobApplyData(Request $request, $code)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];
        if (isset($request->terms_condition_check) && empty($request->terms_condition_check)) {
            $rules['terms_condition_check'] = [
                'required',
            ];
        }

        $validator = \Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $job = Job::where('code', $code)->first();

        // $stage = JobStage::where('created_by',\Auth::user()->creatorId())->first();
        $stage = JobStage::where('created_by', $job->created_by)->first();

        $jobApplication                        = new JobApplication();
        $jobApplication->job                   = $job->id;
        $jobApplication->name                  = $request->name;
        $jobApplication->email                 = $request->email;
        $jobApplication->phone                 = $request->phone;
        $jobApplication->cover_letter          = $request->cover_letter;
        $jobApplication->dob                   = $request->dob;
        $jobApplication->gender                = $request->gender;
        $jobApplication->address               = $request->address;
        $jobApplication->country               = $request->country;
        $jobApplication->state                 = $request->state;
        $jobApplication->stage                 = $stage->id;
        $jobApplication->city                  = $request->city;
        $jobApplication->zip_code              = $request->zip_code;
        $jobApplication->custom_question       = json_encode($request->question);
        $jobApplication->terms_condition_check = !empty($request->terms_condition_check) ? $request->terms_condition_check : '';
        $jobApplication->created_by            = $job->created_by;

        if (!empty($request->profile)) {
            $image_size = $request->file('profile')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1) {
                $filenameWithExt = $request->file('profile')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('profile')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $dir        = 'uploads/job/profile';

                $image_path = $dir . $filenameWithExt;

                $url = '';
                $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);
                $jobApplication->profile         = !empty($request->profile) ? $fileNameToStore : '';
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }

        if (!empty($request->resume)) {

            $image_size = $request->file('resume')->getSize();
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

            if ($result == 1) {
                $filenameWithExt1 = $request->file('resume')->getClientOriginalName();
                $filename1        = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
                $extension1       = $request->file('resume')->getClientOriginalExtension();
                $fileNameToStore1 = $filename1 . '_' . time() . '.' . $extension1;

                $dir        = 'uploads/job/resume';

                $image_path = $dir . $filenameWithExt1;

                $url = '';
                $path = Utility::upload_file($request, 'resume', $fileNameToStore1, $dir, []);
                $jobApplication->resume          = !empty($request->resume) ? $fileNameToStore1 : '';

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }
        $jobApplication->save();

        // return redirect()->back()->with('success', __('Job application successfully send.'));
        return redirect()->back()->with('success', __('Job application successfully send.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        // return redirect()->route('job-application.index')->with('success', __('Job application successfully send.'));
    }
}
