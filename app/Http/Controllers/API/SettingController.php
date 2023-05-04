<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomFileUpload;
use App\Models\Setting;

class SettingController extends Controller
{
    use CustomFileUpload;
    public function generalSettingSave(Request $request){
        $data = array(
            'user_id'     => Auth::guard('api')->id(),
            'theme'         => $request->theme,
            'footer'        => $request->footer,
            'sidebar_option' => $request->sidebar_option,
            'sidebar_variants'=> $request->sidebar_variants,
            'header_setting' => $request->header_setting,
            'navbar_variants' => $request->navbar_variants,
            'accent_color_variants' => $request->accent_color_variants,
            'dark_sidebar_variants' => $request->dark_sidebar_variants,
            'light_sidebar_variants' => $request->light_sidebar_variants,
            'brand_logo_variants' => $request->brand_logo_variants,
        );
        if($request->file('sitelogo')){
            $sitelogo = request()->file('sitelogo');
            $setting = Setting::where('user_id',Auth::guard('api')->id())->first();
            if(!is_null($setting) && isset($setting->site_logo)){
                $this->deleteFile(
                    $setting->getRawOriginal('site_logo'),
                    'uploads/setting/'
                );
            }
            $imagename = $this->uploadFile(
                $sitelogo[0],
                'uploads/setting'
            );
            $data['site_logo'] = $imagename;
        }
        $generalsetting = Setting::updateOrCreate(
            [ 'user_id' => Auth::guard('api')->id() ],
            $data
        );

        return response()->json($generalsetting);
    }

    public function getSetting(){
        return Setting::where('user_id',Auth::guard('api')->id())->first();
    }

    public function resetSetting(){
        $data = array(
            'user_id'               => Auth::guard('api')->id(),
            'theme'                 => 'dark-mode',
            'footer'                => NULL,
            'header_setting'        => array('layout-navbar-fixed','border-bottom-0'),
            'sidebar_option'        => array('layout-fixed','sidebar-mini'),
            'sidebar_variants'      => 'dark',
            'navbar_variants'       => 'bg-dark',
            'accent_color_variants'     => 'accent-white',
            'dark_sidebar_variants'     => 'sidebar-dark-primary',
            'light_sidebar_variants'    => 'sidebar-light-primary',
            'brand_logo_variants'       => 'dark'

        );
        return Setting::updateOrCreate(
            [ 'user_id' => Auth::guard('api')->id() ],
            $data
        );
    }
}
