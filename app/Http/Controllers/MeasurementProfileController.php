<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GarmentType;
use App\Models\MeasurementProfile;
use App\Models\MeasurementValue;
use Illuminate\Http\Request;

class MeasurementProfileController extends Controller
{
  public function index()
    {
        $customers = Customer::all();
        $garments  = GarmentType::with('measurementFields')->get();
        $profiles  = MeasurementProfile::with(['customer','garmentType'])
                        ->latest()->get();

        return view(
            'admin.measurements.profiles.index',
            compact('customers','garments','profiles')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'     => 'required',
            'garment_type_id' => 'required',
            'title'           => 'required',
            'measurements'    => 'required|array'
        ]);

        DB::transaction(function () use ($request) {

            $profile = MeasurementProfile::create(
                $request->only('customer_id','garment_type_id','title','note')
            );

            foreach ($request->measurements as $fieldId => $value) {
                MeasurementValue::create([
                    'measurement_profile_id' => $profile->id,
                    'measurement_field_id'   => $fieldId,
                    'value'                  => $value
                ]);
            }
        });

        return back()->with('success','Measurement profile saved');
    }

    public function destroy(MeasurementProfile $measurementProfile)
    {
        $measurementProfile->delete();
        return back()->with('success','Profile deleted');
    }
}
