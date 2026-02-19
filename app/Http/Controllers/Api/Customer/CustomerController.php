<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function customerstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'phone_number' => [
                'required',
                'max:15',
                'min:10',
                Rule::unique('users', 'phone_number'),
            ],
            'user_code' => [
                'required',
                Rule::unique('users', 'user_code'),
            ],
        ], [
            // Custom error messages
            'phone_number.unique' => 'The phone number has already been taken.',
            'user_code.unique' => 'The user code has already been taken.',
            'first_name.required' => 'The first name is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }

        DB::beginTransaction();
        try {
            $user = new Vendor();
            $user->first_name = $request->first_name;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->phone_number);
            $user->user_code = $request->user_code;
            $user->user_type = 1;
            $user->status = $request->status ?? 1;
            $user->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                'datas' => $user,
                'message' => 'Customer Added successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Data not found.',
            ]);
        }
    }

    public function customerupdate(Request $request)
    {
        DB::beginTransaction();
        // try {
        Log::info("update customer");
        Log::info($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'phone_number' => 'required|max:15|min:10|' . Rule::unique('users', 'phone_number')->ignore($request->id),
            'user_code' => 'required|' . Rule::unique('users', 'user_code')->ignore($request->id),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = Vendor::findOrFail($request->id);
        $user->first_name = $request->first_name;
        $user->phone_number = $request->phone_number;
        $user->password = Hash::make($request->phone_number);
        $user->user_code = $request->user_code;
        $user->user_type = 1;
        $user->status = $request->status ?? 1;
        $user->save();

        DB::commit();
        return response()->json([
            'status' => 200,
            'message' => 'Customer Updated successfully.',
        ]);
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Data not found.',
        //     ]);
        // }
    }
}
