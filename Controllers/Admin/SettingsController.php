<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Http\Requests\WebsiteSettingRequest;
use App\Http\Requests\ShippingsRequest;
use Artisan;
use DB;
use CoreComponentRepository;
use File;
class SettingsController extends Controller
{
    
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:seller_commission_configuration'])->only('vendor_commission');
        $this->middleware(['permission:seller_verification_form_configuration'])->only('seller_verification_form');
        $this->middleware(['permission:general_settings'])->only('general_setting');
        $this->middleware(['permission:features_activation'])->only('activation');
        $this->middleware(['permission:smtp_settings'])->only('smtp_settings');
        $this->middleware(['permission:payment_methods_configurations'])->only('payment_method');
        $this->middleware(['permission:order_configuration'])->only('order_configuration');
        $this->middleware(['permission:file_system_&_cache_configuration'])->only('file_system');
        $this->middleware(['permission:social_media_logins'])->only('social_login');
        $this->middleware(['permission:facebook_chat'])->only('facebook_chat');
        $this->middleware(['permission:facebook_comment'])->only('facebook_comment');
        $this->middleware(['permission:analytics_tools_configuration'])->only('google_analytics');
        $this->middleware(['permission:google_recaptcha_configuration'])->only('google_recaptcha');
        $this->middleware(['permission:google_map_setting'])->only('google_map');
        $this->middleware(['permission:google_firebase_setting'])->only('google_firebase');
        $this->middleware(['permission:shipping_configuration'])->only('shipping_configuration');
    }
    
    public function editShippingMethods($type)
    {

        //free , inner , outer for shipping methods

        if ($type === 'free')
            $shippingMethod = Setting::where('key', 'free_shipping_label')->first();


        elseif ($type === 'inner')
            $shippingMethod = Setting::where('key', 'local_label')->first();

        elseif ($type === 'outer')
            $shippingMethod = Setting::where('key', 'outer_label')->first();
        else
            $shippingMethod = Setting::where('key', 'free_shipping_label')->first();
            // return $shippingMethod;
        return view('admin.settings.shippings.edit', compact('shippingMethod'));

    }

    public function updateShippingMethods(ShippingsRequest $request, $id)
    {
        //validation
        //update db
        try {
            $shipping_method = Setting::find($id);
            DB::beginTransaction();
            $shipping_method->update(['plain_value' => $request->plain_value]);
            //save translations
            $shipping_method->value = $request->value;
            $shipping_method->save();
            DB::commit();
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollback();
        }

    }

    public function shipping_configuration(Request $request){
        return view('admin.settings.shippings.shipping_configuration.index');
    }

    public function shipping_configuration_update(Request $request){
        $business_settings = BusinessSetting::where('type', $request->type)->first();
        $business_settings->value = $request[$request->type];
        $business_settings->save();
        Artisan::call('cache:clear');
        return redirect()->back()->with(['success'=>'Shipping Method updated successfully']);
    }


    public function env_key_update(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        $key_arr=array("APP_NAME","MAIL_FROM_NAME","GOOGLE_CLIENT_ID","GOOGLE_CLIENT_SECRET","FACEBOOK_CLIENT_ID","FACEBOOK_CLIENT_SECRET");
        foreach ($request->types as $key => $type) {
            $value="";
            if(in_array($type,$key_arr)){
                $value='"'.$request[$type].'"';
            }else{
                $value=$request[$type];
            }
            if($request[$type] !=""){
                $this->updateEnvFile([$type=>$value]);
            }
        }
        Artisan::call('cache:clear');
        return back()->with(['success'=>tran('Settings updated successfully')]);
    }

 

    public function overWriteEnvFile($type, $val)
    {
        //if(env('DEMO_MODE') != 'On'){
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"'.trim($val).'"';
                if(is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0){
                    file_put_contents($path, str_replace(
                        $type.'="'.env($type).'"', $type.'='.$val, file_get_contents($path)
                    ));
                }
                else{
                    file_put_contents($path, file_get_contents($path)."\r\n".$type.'='.$val);
                }
                Artisan::call('cache:clear');
            }
      //  }
    }


    public function seller_verification_form(Request $request)
    {
    	
        return view('admin.sellers.seller_verification_form.index');
    }

    /**
     * Update sell verification form.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seller_verification_form_update(Request $request)
    {
        $form = array();
        $select_types = ['select', 'multi_select', 'radio'];
        $j = 0;
        for ($i=0; $i < count($request->type); $i++) {
            $item['type'] = $request->type[$i];
            $item['label'] = $request->label[$i];
            if(in_array($request->type[$i], $select_types)){
                $item['options'] = json_encode($request['options_'.$request->option[$j]]);
                $j++;
            }
            array_push($form, $item);
        }
        $business_settings = BusinessSetting::where('type', 'verification_form')->first();
        $business_settings->value = json_encode($form);
        if($business_settings->save()){
            Artisan::call('cache:clear');
            return back()->with(['success'=>tran('Verification form updated successfully')]);
        }
    }


    public function vendor_commission(Request $request)
    {
        return view('admin.sellers.seller_commission.index');
    }

    public function vendor_commission_update(Request $request){
        foreach ($request->types as $key => $type) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if($business_settings!=null){
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
            else{
                $business_settings = new BusinessSetting;
                $business_settings->type = $type;
                $business_settings->value = $request[$type];
                $business_settings->save();
            }
        }
        Artisan::call('cache:clear');
        return back()->with(['success'=>tran('Seller Commission updated successfully')]);
    }
    

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS', 'FILESYSTEM_DRIVER'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if($business_settings!=null){
            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if(env('DEMO_MODE') != 'On'){
                    Artisan::call('down');
                }
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if(env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            }
            $business_settings->value = $request->value;
            $business_settings->save();
        }
        else{
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }

        Artisan::call('cache:clear');
        return '1';
    }


    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if(strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if(strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 's3');
        }
        elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'local');
        }

        return '1';
    }


    public function update(WebsiteSettingRequest $request)
    {
        if( $request->has('meta_image')){
            $this->logo_update('meta_image',$request->meta_image);
          }
        foreach ($request->types as $key => $type) {
            if($type == 'site_name'){
                $this->updateEnvFile(['APP_NAME'=>'"'.$request[$type].'"']);
            }
            if($type == 'timezone'){
                $this->updateEnvFile(['APP_TIMEZONE'=>'"'.$request[$type].'"']);
            }
            
            else {
                $lang = null;
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->where('lang',$lang)->first();
                }else{
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                if($business_settings!=null){
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
                else{
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
            }
        }

        Artisan::call('cache:clear');
        return back()->with(['success'=>tran('Settings updated successfully')]);
    }


    public function order_configuration(){
        return view('admin.settings.order_configuration.index');
    }

    public function general_setting(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();
    	return view('admin.settings.setup_configurations.general_settings');
    }
    
    
    public function logo_update($key,$value){
        $business_settings = BusinessSetting::where('type', $key)->first();
        $logoval="";
            if ($value !="") {
                $logoval = uploadImage('website_settings', $value);      
            }  
        if($business_settings !=null){
            if($value ==""){
                $logoval= $business_settings ->value;
            }else   $business_settings->value = $logoval;
            $business_settings->save();
        } else{
            $business_settings = new BusinessSetting;
            $business_settings->type = $key;
            $business_settings->value = $logoval;
            $business_settings->save();
        }
    }

     function setEnvValue(string $key, string $value)
      {
    $path = app()->environmentFilePath();
    $env = file_get_contents($path);
    $old_value = env($key);
    if (!str_contains($env, $key.'=')) {
        $env .= sprintf("%s=%s\n", $key, $value);
    } else if ($old_value) {
        $env = str_replace(sprintf('%s=%s', $key, $old_value), sprintf('%s=%s', $key, $value), $env);
    } else {
        $env = str_replace(sprintf('%s=', $key), sprintf('%s=%s',$key, $value), $env);
    }
    file_put_contents($path, $env);
    }
    /////////////////////////////////////////////////////////////
    function updateEnvFile(array $data)
    {
    // Read the contents of the .env file
    $envFile = base_path('.env');
    $contents = File::get($envFile);
    
    // Split the contents into an array of lines
    $lines = explode("\n", $contents);
    
    // Loop through the lines and update the values
    foreach ($lines as &$line) {
    // Skip empty lines and comments
    if (empty($line)) {
    continue;
    }
    
    // Split each line into key and value
    $parts = explode('=', $line, 2);
    $key = $parts[0];
    
    // Check if the key exists in the provided data
    if (isset($data[$key])) {
    // Update the value
    $line = $key . '=' . $data[$key];
    unset($data[$key]);
    }
    }
    
    // Append any new keys that were not present in the original file
    foreach ($data as $key => $value) {
    $lines[] = $key . '=' . $value;
    }
    
    // Combine the lines back into a string
    $updatedContents = implode("\n", $lines);
    
    // Write the updated contents back to the .env file
    File::put($envFile, $updatedContents);
    }

    ///////////////////////////////////////////////////////////////
    public function websitesettings_update(WebsiteSettingRequest $request){
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        if( $request->has('header_logo')){
          $this->logo_update('header_logo',$request->header_logo);
        }
        if( $request->has('footer_logo')){
            $this->logo_update('footer_logo',$request->footer_logo);
          }  
        if( $request->has('payment_method_images')){
            $this->logo_update('payment_method_images',$request->payment_method_images);
          }
          if( $request->has('site_icon')){
            $this->logo_update('site_icon',$request->site_icon);
          }
          
        foreach ($request->types as $key => $type) {
            if( $type =='website_name'){
              
                $this->updateEnvFile(['APP_NAME'=>'"'.$request[$type].'"']);
              }
              if($type == 'timezone'){
                $this->updateEnvFile(['APP_TIMEZONE'=>'"'.$request[$type].'"']);
            }
              $lang = null;
                if(gettype($type) == 'array'){
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->where('lang',$lang)->first();
                }else{
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                if($business_settings!=null){
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
                else{
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $business_settings->value = json_encode($request[$type]);
                    }
                    else {
                        $business_settings->value = $request[$type];
                    }
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }
        }

        Artisan::call('cache:clear');
        return back()->with(['success'=>tran('Settings updated successfully')]);
    }

    public function smtp_settings(Request $request)
    {
        return view('admin.settings.smtp_settings');
    }

    public function social_login(Request $request)
    {

        return view('admin.settings.social_login');
    }

    public function activation(Request $request)
    {
    	return view('admin.settings.activation');
    }
  
    public function payment_method(Request $request)
    {
        return view('admin.settings.payment_method');
    }

    public function payment_method_update(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        foreach ($request->types as $key => $type) {
                $this->updateEnvFile([$type=>'"'.$request[$type].'"']);
        }
        $business_settings = BusinessSetting::where('type', $request->payment_method.'_sandbox')->first();
        if($business_settings != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $business_settings->value = 1;
                $business_settings->save();
            }
            else{
                $business_settings->value = 0;
                $business_settings->save();
            }
        }
        
        Artisan::call('cache:clear');
        return back()->with(['success'=>tran('Settings updated successfully')]);
    }
}
