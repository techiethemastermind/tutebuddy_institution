<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Config;
use App\Models\Grade;
use App\Models\Institution;
use App\Models\Division;
use Hash;

class ConfigController extends Controller
{
    use FileUploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get Config Data
     */
    public function getGeneralSettings() {

        return view('backend.settings.general');
    }

    public function saveGeneralSettings(Request $request) {

        if (($request->get('mail_provider') == 'sendgrid') && ($request->get('list_selection') == 2)) {
            if ($request->get('list_name') == "") {
                return back()->withErrors(['Please input list name']);
            }
            $apiKey = config('sendgrid_api_key');
            $sg = new \SendGrid($apiKey);
            try {
                $request_body = json_decode('{"name": "' . $request->get('list_name') . '"}');
                $response = $sg->client->contactdb()->lists()->post($request_body);
                if ($response->statusCode() != 201) {
                    return back()->withErrors(['Check name and try again']);
                }
                $response = json_decode($response->body());
                $sendgrid_list_id = Config::where('sendgrid_list_id')->first();
                $sendgrid_list_id->value = $response->id;
                $sendgrid_list_id->save();
            } catch (Exception $e) {
                \Log::info($e->getMessage());
            }

        }

        $requests = $this->saveLogos($request);

        if ($request->get('access_registration') == null) {
            $requests['access_registration'] = 0;
        }
        if (!$request->get('mailchimp_double_opt_in')) {
            $requests['mailchimp_double_opt_in'] = 0;
        }
        if ($request->get('access_users_change_email') == null) {
            $requests['access_users_change_email'] = 0;
        }
        if ($request->get('access_users_confirm_email') == null) {
            $requests['access_users_confirm_email'] = 0;
        }
        if ($request->get('access_captcha_registration') == null) {
            $requests['access_captcha_registration'] = 0;
        }
        if ($request->get('access_users_requires_approval') == null) {
            $requests['access_users_requires_approval'] = 0;
        }
        if ($request->get('services__stripe__active') == null) {
            $requests['services__stripe__active'] = 0;
        }
        if ($request->get('paypal__active') == null) {
            $requests['paypal__active'] = 0;
        }
        if ($request->get('payment_offline_active') == null) {
            $requests['payment_offline_active'] = 0;
        }
        if ($request->get('backup__status') == null) {
            $requests['backup__status'] = 0;
        }
        if ($request->get('access__captcha__registration') == null) {
            $requests['access__captcha__registration'] = 0;
        }
        if ($request->get('retest') == null) {
            $requests['retest'] = 0;
        }
        if ($request->get('lesson_timer') == null) {
            $requests['lesson_timer'] = 0;
        }
        if ($request->get('show_offers') == null) {
            $requests['show_offers'] = 0;
        }
        if ($request->get('onesignal_status') == null) {
            $requests['onesignal_status'] = 0;
        }

        foreach ($requests->all() as $key => $value) {
            if ($key != '_token') {
                $key = str_replace('__', '.', $key);
                $config = Config::firstOrCreate(['key' => $key]);
                if($value !== null) {
                    $config->value = $value;
                }
                $config->save();

                if($key === 'app.locale'){
                    Locale::where('short_name','!=',$value)->update(['is_default' => 0]);
                    $locale = Locale::where('short_name','=',$value)->first();
                    $locale->is_default = 1;
                    $locale->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }

    public function getInstitutionSettings()
    {
        $institution = auth()->user()->institution;
        return view('backend.settings.institution', compact('institution'));
    }

    public function saveInstitutionSettings(Request $request)
    {
        $institution_id = auth()->user()->institution->id;

        if($request->tab == 'grade') {

            Grade::where('institution_id', $institution_id)->delete();
            foreach ($request->grade_name as $key => $value) {
                Grade::insert([
                    'name' => $value,
                    'value' => $key,
                    'type' => 'grade',
                    'institution_id' => $institution_id
                ]);
            }

            foreach ($request->college_name as $key => $value) {
                Grade::insert([
                    'name' => $value,
                    'value' => $key,
                    'type' => 'college',
                    'institution_id' => $institution_id
                ]);
            }

            foreach ($request->graduation_name as $key => $value) {
                Grade::insert([
                    'name' => $value,
                    'value' => $key,
                    'type' => 'graduation',
                    'institution_id' => $institution_id
                ]);
            }

            return response()->json([
                'success' => true
            ]);
        }

        if($request->tab == 'division') {

            $division = Division::where('institution_id', $institution_id)->delete();
            foreach ($request->division_name as $key => $value) {
                Division::insert([
                    'name' => $value,
                    'institution_id' => $institution_id
                ]);
            }

            return response()->json([
                'success' => true
            ]);
        }

        if($request->tab == 'general') {

            $input = $request->except('logo');

            $institution = Institution::find($institution_id);
            $institution->update($input);

            $logo = $request->has('logo') ? $request->file('logo') : false;
            if($logo) {
                $logo_url = $this->saveImage($logo, 'logos');
                $institution->logo = $logo_url;
                $institution->save();
            }
        
            return response()->json([
                'success' => true,
            ]);
        }

        if($request->tab == 'password') {

            $input = $request->all();
            $institution = Institution::find($institution_id);

            if(!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
                if (Hash::check($input['password'], $institution->password)) {

                    if($input['password'] == $input['confirm_password']) {
                        $institution->update($input);

                        return response()->json([
                            'success' => true,
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Password and confirm password should be same'
                        ]);
                    }
                    
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current Password is not correct'
                    ]);
                }
            }
        }
    }
}
