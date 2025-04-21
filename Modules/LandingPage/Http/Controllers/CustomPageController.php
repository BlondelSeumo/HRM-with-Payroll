<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->type == 'super admin') {
            $settings = LandingPageSetting::settings();
            $pages = json_decode($settings['menubar_page'], true);
            return view('landingpage::landingpage.menubar.index', compact('pages', 'settings'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::landingpage.menubar.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($request->menubar_page_name == 'Terms and Conditions' || $request->menubar_page_name == 'Privacy Policy') {
            return redirect()->back()->with('error', __('This name has Already Been Taken.'));
        }

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page'], true);
        $page_slug = str_replace(' ', '_', strtolower($request->menubar_page_name));

        $datas['menubar_page_name'] = $request->menubar_page_name;
        $datas['menubar_page_contant'] = $request->menubar_page_contant;
        $datas['page_slug'] = $page_slug;
        
        if ($request->header) {
            $datas['header'] = 'on';
        } else {
            $datas['header'] = 'off';
        }

        if ($request->footer) {
            $datas['footer'] = 'on';
        } else {
            $datas['footer'] = 'off';
        }

        $datas['template_name'] = $request->template_name;

        if (isset($request->template_name) && $request->template_name == 'page_url') {
            $datas['page_url'] = $request->page_url;
            $datas['menubar_page_contant'] = '';
        } else {
            $datas['page_url'] = '';
            $datas['menubar_page_contant'] = $request->menubar_page_contant;
        }
        if ($request->login) {
            $datas['login'] = 'on';
        } else {
            $datas['login'] = 'off';
        }

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'], ['value' => $data]);

        return redirect()->back()->with(['success' => 'Menubar add successfully']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);
        $page = $pages[$key];
        return view('landingpage::landingpage.menubar.edit', compact('page', 'key'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $key)
    {
        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page'], true);
        $page_slug = str_replace(' ', '_', strtolower($request->menubar_page_name));

        if ($request->menubar_page_name == 'Terms and Conditions') {
            $datas['menubar_page_name'] = 'Terms and Conditions';
        } elseif ($request->menubar_page_name == 'Privacy Policy') {
            $datas['menubar_page_name'] = 'Privacy Policy';
        } else {
            $datas['menubar_page_name'] = $request->menubar_page_name;
        }
        $datas['menubar_page_contant'] = $request->menubar_page_contant;

        $datas['page_slug'] = $page_slug;

        if ($request->header) {
            $datas['header'] = 'on';
        } else {
            $datas['header'] = 'off';
        }

        if ($request->footer) {
            $datas['footer'] = 'on';
        } else {
            $datas['footer'] = 'off';
        }

        $datas['template_name'] = $request->template_name;

        if (isset($request->template_name) && $request->template_name == 'page_url') {
            $datas['page_url'] = $request->page_url;
            $datas['menubar_page_contant'] = '';
        } else {
            $datas['page_url'] = '';
            $datas['menubar_page_contant'] = $request->menubar_page_contant;
        }

        if ($request->login) {
            $datas['login'] = 'on';
        } else {
            $datas['login'] = 'off';
        }

        $data[$key] = $datas;
        $data = json_encode($data);


        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'], ['value' => $data]);
        return redirect()->back()->with(['success' => 'Menubar updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'], ['value' => $pages]);

        return redirect()->back()->with(['success' => 'Menubar deleted successfully']);
    }


    public function customStore(Request $request)
    {
        if ($request->site_logo) {
            $site_logo = "site_logo." . $request->site_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request, 'site_logo', $site_logo, $dir, []);
            if ($path['flag'] == 0) {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data['site_logo'] = $site_logo;
        }

        $data['site_description'] = $request->site_description;

        foreach ($data as $key => $value) {

            LandingPageSetting::updateOrCreate(['name' =>  $key], ['value' => $value]);
        }

        return redirect()->back()->with(['success' => 'Setting updated successfully']);
    }


    public function customPage($slug)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);

        foreach ($pages as $key => $page) {
            if ($page['page_slug'] == $slug) {
                return view('landingpage::layouts.custompage', compact('page', 'settings'));
            }
        }
    }
}
