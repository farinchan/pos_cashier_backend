<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpendingResource;
use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SpendingController extends BaseController
{
    
    public function index(Request $request)
    {
        $date_start = $request->input('date_start', date('Y-m-d 00:00:00'));
        $date_end = $request->input('date_end', date('Y-m-d 23:59:59'));

        // dd($date_start, $date_end);
        
        
        $perPage = request()->input('perPage', 10);
        $spendings = Spending::whereBetween('created_at', [$date_start, $date_end])->latest()->paginate($perPage);

        return $this->sendResponseWithPagination(SpendingResource::collection($spendings), 'Spendings retrieved successfully.', $request);

    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);

        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        $input['user_id'] = Auth::user()->id;

        $spending = Spending::create($input);

        return $this->sendResponseValidation($spending, 'Spending created successfully.', $validation);

    }

    public function show($id)
    {
        $spending = Spending::find($id);

        if (is_null($spending)) {
            return $this->sendResponse([], 'Spending not found.');
        }

        return $this->sendResponse(new SpendingResource($spending), 'Spending retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);

        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        $spending = Spending::find($id);
        $spending->name = $input['name'];
        $spending->price = $input['price'];
        $spending->description = $input['description'];
        $spending->save();

        return $this->sendResponseValidation($spending, 'Spending updated successfully.', $validation);
    }

    public function destroy($id)
    {
        try {
            $spending = Spending::find($id);
            $spending->delete();
            return $this->sendResponse([], 'Spending deleted successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Spending failed to delete.');
        }
    }

    
}
