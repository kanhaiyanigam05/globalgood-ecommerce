<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Setting;
use App\Models\ShippingProfile;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\TaxSetting;
use App\Models\TaxRegion;
use App\Models\TaxOverride;
use App\Models\CountryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.general', compact('settings'));
    }

    public function shipping()
    {
        $profiles = ShippingProfile::with(['zones.rates', 'products'])->get();
        // If no profile exists, create a default one
        if ($profiles->isEmpty()) {
            $defaultProfile = ShippingProfile::create(['name' => 'General profile', 'is_default' => true]);
            $profiles = collect([$defaultProfile]);
        }
        return view('admin.settings.shipping.index', compact('profiles'));
    }

    public function shippingEdit(ShippingProfile $profile)
    {
        $profile->load(['zones.rates', 'zones.countries', 'products']);
        $countries = Country::orderBy('name')->get();
        return view('admin.settings.shipping.edit', compact('profile', 'countries'));
    }

    public function tax(Request $request)
    {
        $query = Country::with('taxSettings')->orderBy('name');
        
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $countries = $query->paginate(10);
        if ($request->has('search')) {
            $countries->appends(['search' => $request->search]);
        }

        $taxRegions = TaxRegion::all();
        $globalSettings = Setting::where('group', 'tax')->pluck('value', 'key');
        
        if ($request->ajax()) {
            return view('admin.settings.tax.table', compact('countries'))->render();
        }

        return view('admin.settings.tax.index', compact('countries', 'taxRegions', 'globalSettings'));
    }

    public function updateGeneral(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value, 'general');
        }
        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    public function updateTax(Request $request)
    {
        $keys = ['tax_included', 'tax_on_shipping', 'tax_on_digital'];
        
        foreach ($keys as $key) {
            $value = $request->has("settings.$key") ? 1 : 0;
            Setting::set($key, $value, 'tax');
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Tax settings updated successfully.');
    }

    // Shipping Profile CRUD
    public function saveShippingProfile(Request $request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            $profile = $id ? ShippingProfile::findOrFail($id) : new ShippingProfile();
            $profile->name = $request->name;
            $profile->save();

            // Handle Zones and Rates if provided in a single request (Shopify style)
            // For simplicity, we'll handle them via separate AJAX requests in the UI
            
            return redirect()->route('admin.settings.shipping')->with('success', 'Shipping profile saved.');
        });
    }

    // AJAX methods for Zones and Rates
    public function saveZone(Request $request)
    {
        $zone = $request->id ? ShippingZone::findOrFail($request->id) : new ShippingZone();
        $zone->shipping_profile_id = $request->profile_id;
        $zone->name = $request->name;
        $zone->save();

        if ($request->has('countries')) {
            $zone->countries()->sync($request->countries);
        }

        return response()->json(['success' => true, 'zone' => $zone]);
    }

    public function saveRate(Request $request)
    {
        $rate = $request->id ? ShippingRate::findOrFail($request->id) : new ShippingRate();
        $rate->shipping_zone_id = $request->zone_id;
        $rate->name = $request->name;
        $rate->type = $request->type;
        $rate->min_value = $request->min_value;
        $rate->max_value = $request->max_value;
        $rate->price = $request->price;
        $rate->save();

        return response()->json(['success' => true, 'rate' => $rate]);
    }

    public function deleteZone($id)
    {
        ShippingZone::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function saveTax(Request $request)
    {
        TaxSetting::updateOrCreate(
            ['country_id' => $request->country_id],
            [
                'tax_rate' => $request->tax_rate,
                'tax_name' => $request->tax_name,
                'is_active' => $request->has('is_active') ? 1 : 0
            ]
        );

        return response()->json(['success' => true]);
    }

    public function deleteRate($id)
    {
        ShippingRate::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function taxEdit(Country $country)
    {
        $country->load(['taxSettings', 'taxOverrides.zone', 'zones']);
        $baseTax = $country->taxSettings->first();
        
        return view('admin.settings.tax.edit', compact('country', 'baseTax'));
    }

    public function saveCountryTax(Request $request, Country $country)
    {
        // Save Base Tax
        TaxSetting::updateOrCreate(
            ['country_id' => $country->id],
            [
                'tax_rate' => $request->base_tax_rate,
                'tax_name' => $request->base_tax_name,
                'is_active' => true
            ]
        );

        // Save Overrides
        if ($request->has('overrides')) {
            foreach ($request->overrides as $zoneId => $data) {
                if (isset($data['tax_rate']) && $data['tax_rate'] !== "" && $data['tax_rate'] !== null) {
                    TaxOverride::updateOrCreate(
                        ['country_id' => $country->id, 'country_zone_id' => $zoneId],
                        [
                            'tax_rate' => $data['tax_rate'],
                            'tax_name' => $data['tax_name'] ?? 'Tax',
                            'tax_type' => $data['tax_type'] ?? 'added'
                        ]
                    );
                } else {
                    TaxOverride::where('country_id', $country->id)->where('country_zone_id', $zoneId)->delete();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Tax settings updated successfully.']);
    }
}
