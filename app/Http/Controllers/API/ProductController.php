<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{

    public function index(Request $request)

    {
        $keyword = $request->input('q');
        $category_id = $request->input('category');
        $perPage = $request->input('perPage', 10);
        $data = Product::where('name', 'like', "%$keyword%")
            ->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->latest()
            ->paginate($perPage);
        return $this->sendResponseWithPagination(ProductResource::collection($data), 'Products retrieved successfully.', $request);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'barcode' => 'nullable',
        ]);
        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_path = $image->storeAs('products', time() . '.' . $image->getClientOriginalExtension(), 'public');
            $input['image'] = str_replace('public/', '', $file_path);
        }

        $product = Product::create($input);

        return $this->sendResponseValidation($product, 'Product created successfully.', $validation);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendResponse([], 'Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);
        $validation = array_fill_keys(array_keys($request->all()), []);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $errors) {
                $validation[$key] = $errors;
            }
            return $this->sendErrorValidation($validation);
        }

        $product = Product::find($id);
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->stock = $input['stock'];
        $product->category_id = $input['category_id'];
        $product->save();

        return $this->sendResponseValidation($product, 'Product updated successfully.', $validation);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
