<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function settings($page)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }
        return view('admin.pages.settings', compact('page'));
    }

    public function profile()
    {
        if (!userCan('manage profile')) {
            abort(403);
        }
        return view('admin.pages.profile');
    }

    public function updateProfile(Request $request)
    {
        if (!userCan('manage profile')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:6',
        ]);

        $user = $request->user('admin');

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password
        ]);

        if ($request->password) {
            auth('admin')->logout();
        }

        return redirect()->back()->withSuccess('Data updated.');
    }

    public function updateSettings(Request $request)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }
        $request->validate([
            'website_name' => $request->tab == 'basic' ? 'required' : '',
            'website_logo' => $request->tab == 'basic' ? 'nullable|image' : '',
            'website_favicon' => $request->tab == 'basic' ? 'nullable|image' : '',
            'hero_section_banner' => $request->tab == 'basic' ? 'nullable|image' : '',
            'footer_banner' => $request->tab == 'basic' ? 'nullable|image' : '',
            'facebook' => 'nullable|url',
            'youtube' => 'nullable|url',
            'instagram' => 'nullable|url',
            'footer_banner_link' => 'nullable|url',
            'delivery_charge_inside_title' => 'nullable|string|max:255',
            'delivery_charge_outside_title' => 'nullable|string|max:255',
            'delivery_charge_sub_area_title' => 'nullable|string|max:255',
            'affiliate_commission_percent' => 'nullable|numeric|min:0|max:100',
            'affiliate_min_withdrawal_amount' => 'nullable|numeric|min:0',
            'affiliate_feature_enabled' => 'nullable|boolean',
            'google_client_id' => 'nullable|string',
            'google_client_secret' => 'nullable|string',
            'google_login_enabled' => 'nullable|boolean',
        ]);

        $settings = Setting::first();

        $website_logo = $settings->website_logo;
        if ($request->hasFile('website_logo')) {

            // delete old one
            deleteFile($settings->website_logo);

            // upload new one
            $website_logo = uploadImage($request->website_logo, 'uploads/logo');
        }

        $website_favicon = $settings->website_favicon;
        if ($request->hasFile('website_favicon')) {

            deleteFile($settings->website_favicon);

            $website_favicon = uploadImage($request->website_favicon, 'uploads/logo');
        }

        $footer_logo = $settings->footer_logo;
        if ($request->hasFile('footer_logo')) {

            deleteFile($footer_logo);

            $footer_logo = uploadImage($request->footer_logo, 'uploads/logo');
        }

        $payment_logo = $settings->payment_logo;
        if ($request->hasFile('payment_logo')) {

            deleteFile($payment_logo);

            $payment_logo = uploadImage($request->payment_logo, 'uploads/payment_logo');
        }

        $hero_section_banner = $settings->hero_section_banner;
        if ($request->hasFile('hero_section_banner')) {

            deleteFile($hero_section_banner);

            $hero_section_banner = uploadImage($request->hero_section_banner, 'uploads/banner');
        }

        $footer_banner = $settings->footer_banner;
        if ($request->hasFile('footer_banner')) {

            deleteFile($footer_banner);

            $footer_banner = uploadImage($request->footer_banner, 'uploads/banner');
        }


        if ($request->online_payment_gateway == 'sslcommerz') {
            $settings->update([
                'sslcommerz_enabled' => $request->sslcommerz_enabled ?? false,
                'sslcommerz_store_id' => $request->sslcommerz_store_id ?? '',
                'sslcommerz_store_password' => $request->sslcommerz_store_password ?? '',
                'sslcommerz_api_endpoint' => $request->sslcommerz_api_endpoint ?? '',
            ]);
        }

        if ($request->online_payment_gateway == 'stripe') {
            $settings->update([
                'stripe_enabled' => $request->stripe_enabled ?? false,
                'stripe_public_key' => $request->stripe_public_key ?? '',
                'stripe_secret_key' => $request->stripe_secret_key ?? '',
                'stripe_mode' => $request->stripe_mode ?? 'test',
            ]);
        }

        if ($request->online_payment_gateway == 'manual') {
            // manual_focus indicates which specific manual gateway block was submitted.
            // If manual_focus is present (bkash/nagad/rocket/bank), only update that block.
            // If not present, update all manual gateway fields as before.
            $focus = $request->input('manual_focus');

            $updateData = [];

            if (!$focus || $focus == 'bkash') {
                $updateData = array_merge($updateData, [
                    'bkash_number' => $request->bkash_number ?? $settings->bkash_number,
                    'bkash_number_note' => $request->bkash_number_note ?? $settings->bkash_number_note,
                    'bkash_enabled' => $request->has('bkash_enabled') ? 1 : 0,
                ]);
            }

            if (!$focus || $focus == 'nagad') {
                $updateData = array_merge($updateData, [
                    'nagad_number' => $request->nagad_number ?? $settings->nagad_number,
                    'nagad_number_note' => $request->nagad_number_note ?? $settings->nagad_number_note,
                    'nagad_enabled' => $request->has('nagad_enabled') ? 1 : 0,
                ]);
            }

            if (!$focus || $focus == 'rocket') {
                $updateData = array_merge($updateData, [
                    'rocket_number' => $request->rocket_number ?? $settings->rocket_number,
                    'rocket_number_note' => $request->rocket_number_note ?? $settings->rocket_number_note,
                    'rocket_enabled' => $request->has('rocket_enabled') ? 1 : 0,
                ]);
            }

            if (!$focus || $focus == 'bank') {
                $updateData = array_merge($updateData, [
                    'bank_account_number' => $request->bank_account_number ?? $settings->bank_account_number,
                    'bank_account_number_note' => $request->bank_account_number_note ?? $settings->bank_account_number_note,
                    'bank_enabled' => $request->has('bank_enabled') ? 1 : 0,
                ]);
            }

            if (!empty($updateData)) {
                $settings->update($updateData);
            }
        }

        // Only update fields based on the tab being submitted
        $tabUpdateData = [];

        // Determine which fields to update based on tab
        switch ($request->tab) {
            case 'basic':
                $tabUpdateData = [
                    'website_name' => $request->website_name ?? $settings->website_name,
                    'website_logo' => $website_logo ?? $settings->website_logo,
                    'footer_logo' => $footer_logo ?? $settings->footer_logo,
                    'website_favicon' => $website_favicon ?? $settings->website_favicon,
                    'payment_logo' => $payment_logo ?? $settings->payment_logo,
                    'hero_section_banner' => $hero_section_banner ?? $settings->hero_section_banner,
                    'footer_banner' => $footer_banner ?? $settings->footer_banner,
                    'footer_banner_link' => $request->footer_banner_link ?? $settings->footer_banner_link,
                ];
                break;

            case 'footer':
                $tabUpdateData = [
                    'address' => $request->address ?? $settings->address,
                    'phone_number' => $request->phone_number ?? $settings->phone_number,
                    'email' => $request->email ?? $settings->email,
                    'whatsapp_number' => $request->whatsapp_number ?? $settings->whatsapp_number,
                    'copyright' => $request->copyright ?? $settings->copyright,
                    'facebook' => $request->facebook ?? $settings->facebook,
                    'youtube' => $request->youtube ?? $settings->youtube,
                    'instagram' => $request->instagram ?? $settings->instagram,
                ];
                break;

            case 'charge':
                $tabUpdateData = [
                    'delivery_charge_inside_title' => $request->delivery_charge_inside_title ?? $settings->delivery_charge_inside_title,
                    'delivery_charge_outside_title' => $request->delivery_charge_outside_title ?? $settings->delivery_charge_outside_title,
                    'delivery_charge_sub_area_title' => $request->delivery_charge_sub_area_title ?? $settings->delivery_charge_sub_area_title,
                    'delivery_charge' => $request->delivery_charge ?? $settings->delivery_charge,
                    'delivery_charge_sub_area' => $request->delivery_charge_sub_area ?? $settings->delivery_charge_sub_area,
                    'delivery_charge_outside_dhaka' => $request->delivery_charge_outside_dhaka ?? $settings->delivery_charge_outside_dhaka,
                    'vat' => $request->vat ?? $settings->vat,
                ];
                break;

            case 'offline-payment':
                $tabUpdateData = [
                    'cash_on_delivery_enabled' => $request->has('cash_on_delivery_enabled') ? 1 : 0,
                ];
                break;

            case 'online-payment':
                $tabUpdateData = [
                    'online_payment_enabled' => $request->has('online_payment_enabled') ? 1 : 0,
                ];
                break;

            case 'affiliate':
                $tabUpdateData = [
                    'affiliate_commission_percent' => $request->affiliate_commission_percent ?? $settings->affiliate_commission_percent,
                    'affiliate_min_withdrawal_amount' => $request->affiliate_min_withdrawal_amount ?? $settings->affiliate_min_withdrawal_amount,
                    'affiliate_feature_enabled' => $request->affiliate_feature_enabled !== null ? (bool) $request->affiliate_feature_enabled : $settings->affiliate_feature_enabled,
                ];
                break;

            case 'google-login':
                $tabUpdateData = [
                    'google_client_id' => $request->google_client_id ?? $settings->google_client_id,
                    'google_client_secret' => $request->google_client_secret ?? $settings->google_client_secret,
                    'google_login_enabled' => $request->google_login_enabled !== null ? (bool) $request->google_login_enabled : $settings->google_login_enabled,
                ];
                break;

            default:
                // If no tab specified, update all (backward compatibility)
                $tabUpdateData = [
                    'website_name' => $request->website_name ?? $settings->website_name,
                    'website_logo' => $website_logo ?? $settings->website_logo,
                    'payment_logo' => $payment_logo ?? $settings->payment_logo,
                    'website_favicon' => $website_favicon ?? $settings->website_favicon,
                    'address' => $request->address ?? $settings->address,
                    'phone_number' => $request->phone_number ?? $settings->phone_number,
                    'email' => $request->email ?? $settings->email,
                    'whatsapp_number' => $request->whatsapp_number ?? $settings->whatsapp_number,
                    'copyright' => $request->copyright ?? $settings->copyright,
                    'facebook' => $request->facebook ?? $settings->facebook,
                    'youtube' => $request->youtube ?? $settings->youtube,
                    'instagram' => $request->instagram ?? $settings->instagram,
                    'footer_logo' => $footer_logo ?? $settings->footer_logo,
                    'delivery_charge_inside_title' => $request->delivery_charge_inside_title ?? $settings->delivery_charge_inside_title,
                    'delivery_charge_outside_title' => $request->delivery_charge_outside_title ?? $settings->delivery_charge_outside_title,
                    'delivery_charge_sub_area_title' => $request->delivery_charge_sub_area_title ?? $settings->delivery_charge_sub_area_title,
                    'delivery_charge' => $request->delivery_charge ?? $settings->delivery_charge,
                    'delivery_charge_sub_area' => $request->delivery_charge_sub_area ?? $settings->delivery_charge_sub_area,
                    'delivery_charge_outside_dhaka' => $request->delivery_charge_outside_dhaka ?? $settings->delivery_charge_outside_dhaka,
                    'vat' => $request->vat ?? $settings->vat,
                    'cash_on_delivery_enabled' => $request->has('cash_on_delivery_enabled') ? 1 : 0,
                    'online_payment_enabled' => $request->has('online_payment_enabled') ? 1 : 0,
                    'hero_section_banner' => $hero_section_banner ?? $settings->hero_section_banner,
                    'footer_banner' => $footer_banner ?? $settings->footer_banner,
                    'footer_banner_link' => $request->footer_banner_link ?? $settings->footer_banner_link,
                    'affiliate_commission_percent' => $request->affiliate_commission_percent ?? $settings->affiliate_commission_percent,
                    'affiliate_min_withdrawal_amount' => $request->affiliate_min_withdrawal_amount ?? $settings->affiliate_min_withdrawal_amount,
                    'affiliate_feature_enabled' => $request->affiliate_feature_enabled !== null ? (bool) $request->affiliate_feature_enabled : $settings->affiliate_feature_enabled,
                    'google_client_id' => $request->google_client_id ?? $settings->google_client_id,
                    'google_client_secret' => $request->google_client_secret ?? $settings->google_client_secret,
                    'google_login_enabled' => $request->google_login_enabled !== null ? (bool) $request->google_login_enabled : $settings->google_login_enabled,
                ];
                break;
        }

        $settings->update($tabUpdateData);

        return redirect()->back()->withSuccess('Data updated.');
    }

    public function donateInfo()
    {
        if (!userCan('manage settings')) {
            abort(403);
        }
        $settings = Setting::first();

        return view('admin.pages.donate-info', compact('settings'));
    }

    public function updateDonateInfo(Request $request)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }
        $settings = Setting::first();

        $settings->update($request->except('token'));

        return redirect()->back()->withSuccess('Data updated.');
    }

    public function flashDealTimer(Request $request)
    {
        if (!userCan('manage settings')) {
            abort(403);
        }
        $settings = Setting::first();

        $settings->update([
            'flash_deal_timer' => $request->flash_deal
        ]);

        return redirect()->back()->withSuccess('Data updated.');
    }
    public function siteColor(Request $request)
    {
        if (!userCan('manage site colors')) {
            abort(403);
        }
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'text_color' => 'required|string',
        ]);

        $settings = Setting::first();

        $settings->update([
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'text_color' => $request->text_color,
        ]);

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }
}
