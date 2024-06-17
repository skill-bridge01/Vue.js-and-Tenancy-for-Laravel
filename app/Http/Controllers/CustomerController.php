<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    // /**
    //  * @OA\Get(
    //  *      path="/api/customer",
    //  *      tags={"customer"},
    //  *      summary="Get list of customer",
    //  *      description="Returns list of customer",
    //  *      security={
    //  *           {"sanctum": {}}
    //  *      },
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="Successfully get customer list"
    //  *       ),
    //  *       @OA\Response(response=404, description="Not Found"),
    //  *     )
    //  *
    //  * Returns list of projects
    //  */
    public function index()
    {
        return (new CustomerCollection(Customer::all()))->response()->setStatusCode(200);
    }

    // /**
    //  * @OA\Get(
    //  *      path="/api/customer/{idCustomer}",
    //  *      tags={"customer"},
    //  *      summary="Find customer by ID",
    //  *      description="Returns customer by ID",
    //  *      security={
    //  *           {"sanctum": {}}
    //  *      },
    //  *      @OA\Parameter(
    //  *          name="idCustomer",
    //  *          in="path",
    //  *          description="Please enter id customer",
    //  *          required=true,
    //  *          @OA\Schema(
    //  *              type="integer"
    //  *          )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="Successfully get customer"
    //  *       ),
    //  *       @OA\Response(response=404, description="Not Found"),
    //  *     )
    //  *
    //  * Returns specific customer
    //  */
    public function findById(Customer $customer)
    {
        return (new CustomerResource($customer))->response()->setStatusCode(200);
    }

    // /**
    //  * @OA\Post(
    //  *     path="/api/create-customer",
    //  *     tags={"customer"},
    //  *     summary="Create customer",
    //  *     description="Create new customer",
    //  *     operationId="store",
    //  *     security={
    //  *          {"sanctum": {}}
    //  *     },
    //  *     @OA\Parameter(
    //  *         name="name",
    //  *         in="query",
    //  *         description="Please enter your full name",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="email",
    //  *         in="query",
    //  *         description="Please enter your email",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="password",
    //  *         in="query",
    //  *         description="Please enter your password",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="hobby[]",
    //  *         in="query",
    //  *         description="Please enter your hobby",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"art", "basketball", "chess", "fashion", "video gaming", "photography", "music", "dance", "jogging", "swimming"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="date_of_birth",
    //  *         in="query",
    //  *         description="Please enter your date of birth",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string",
    //  *             format="date"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="age",
    //  *         in="query",
    //  *         description="Please enter your age",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="integer"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="status[]",
    //  *         in="query",
    //  *         description="Please enter your status",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"single", "married", "divorce"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="vehicle",
    //  *         in="query",
    //  *         description="Please enter your vehicle",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"motorcycle", "car", "bike"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="address",
    //  *         in="query",
    //  *         description="Please enter your address",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *      @OA\RequestBody(
    //  *         required=true,
    //  *         description="Please enter your photo",
    //  *          @OA\MediaType(
    //  *              mediaType="multipart/form-data",
    //  *              @OA\Schema(
    //  *                  @OA\Property(
    //  *                      property="photo",
    //  *                      type="array",
    //  *                      @OA\Items(type="string", format="binary")
    //  *                  )
    //  *              )
    //  *          )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=201,
    //  *          description="Customer created successfully"
    //  *       ),
    //  *     @OA\Response(
    //  *         response=400,
    //  *         description="Bad Request"
    //  *     ),
    //  * )
    //  */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:customers|max:255',
            'password' => 'required',
            'hobby' => 'required',
            'date_of_birth' => 'required|date',
            'photo' => 'required|image',
            'age' => 'required|numeric',
            'status' => 'required',
            'vehicle' => ['required', Rule::in(['motorcycle', 'car', 'bike'])],
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'status'  => false,
                        'data'  => $validator->errors(),
                    ], 400);
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->hobby = implode(",", $request->hobby);
        $customer->date_of_birth = $request->date_of_birth;
        $customer->photo = $request->file('photo')->store('customer', 'public');
        $customer->age = $request->age;
        $customer->status = $request->status[0];
        $customer->vehicle = $request->vehicle;
        $customer->address = $request->address;
        $customer->save();

        return response([
                    'status'  => true,
                    'message' => 'Customer created successfully',
                ], 201);
    }

    // /**
    //  * @OA\Put(
    //  *     path="/api/edit-customer",
    //  *     tags={"customer"},
    //  *     summary="Edit customer",
    //  *     description="Edit customer",
    //  *     operationId="update",
    //  *     security={
    //  *          {"sanctum": {}}
    //  *     },
    //  *     @OA\Parameter(
    //  *         name="idCustomer",
    //  *         in="query",
    //  *         description="Please enter id customer",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="integer"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="name",
    //  *         in="query",
    //  *         description="Please enter your full name",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="email",
    //  *         in="query",
    //  *         description="Please enter your email",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="hobby[]",
    //  *         in="query",
    //  *         description="Please enter your hobby",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"art", "basketball", "chess", "fashion", "video gaming", "photography", "music", "dance", "jogging", "swimming"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="date_of_birth",
    //  *         in="query",
    //  *         description="Please enter your date of birth",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string",
    //  *             format="date"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="age",
    //  *         in="query",
    //  *         description="Please enter your age",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="integer"
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="status[]",
    //  *         in="query",
    //  *         description="Please enter your status",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"single", "married", "divorce"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="vehicle",
    //  *         in="query",
    //  *         description="Please enter your vehicle",
    //  *         required=true,
    //  *         explode=true,
    //  *         @OA\Schema(
    //  *             type="array",
    //  *             @OA\Items(
    //  *                 type="string",
    //  *                 enum = {"motorcycle", "car", "bike"},
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="address",
    //  *         in="query",
    //  *         description="Please enter your address",
    //  *         required=true,
    //  *         @OA\Schema(
    //  *             type="string"
    //  *         )
    //  *     ),
    //  *      @OA\RequestBody(
    //  *         required=true,
    //  *         description="Please enter your photo",
    //  *          @OA\MediaType(
    //  *              mediaType="multipart/form-data",
    //  *              @OA\Schema(
    //  *                  @OA\Property(
    //  *                      property="photo",
    //  *                      type="array",
    //  *                      @OA\Items(type="string", format="binary")
    //  *                  )
    //  *              )
    //  *          )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="Customer successfully updated"
    //  *       ),
    //  *     @OA\Response(
    //  *         response=400,
    //  *         description="Bad Request"
    //  *     ),
    //  * )
    //  */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'hobby' => 'required',
            'date_of_birth' => 'required|date',
            'photo' => 'image',
            'age' => 'required|numeric',
            'status' => 'required',
            'vehicle' => ['required', Rule::in(['motorcycle', 'car', 'bike'])],
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                        'status'  => false,
                        'data'  => $validator->errors(),
                    ], 400);
        }

        $customer = Customer::find($request->idCustomer);
        $customer->name = $request->name;
        $customer->email = $request->email;
        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }
        $customer->hobby = implode(",", $request->hobby);
        $customer->date_of_birth = $request->date_of_birth;
        if ($request->hasFile('photo')) {
            $customer->photo = $request->file('photo')->store('customer', 'public');
        }
        $customer->age = $request->age;
        $customer->status = $request->status[0];
        $customer->vehicle = $request->vehicle;
        $customer->address = $request->address;
        $customer->save();

        return response([
                    'status'  => true,
                    'message' => 'Customer successfully updated',
                ], 200);
    }

    // /**
    //  * @OA\Delete(
    //  *      path="/api/delete-customer/{idCustomer}",
    //  *      tags={"customer"},
    //  *      summary="Delete customer",
    //  *      description="Delete customer by ID",
    //  *      security={
    //  *           {"sanctum": {}}
    //  *      },
    //  *      @OA\Parameter(
    //  *          name="idCustomer",
    //  *          in="path",
    //  *          description="Please enter id customer",
    //  *          required=true,
    //  *          @OA\Schema(
    //  *              type="integer"
    //  *          )
    //  *      ),
    //  *      @OA\Response(
    //  *          response=200,
    //  *          description="Customer successfully removed"
    //  *       ),
    //  *       @OA\Response(response=400, description="Bad Request"),
    //  *     )
    //  *
    //  * Delete specific customer
    //  */
    public function destroy(Customer $customer)
    {
        Storage::disk('public')->delete($customer->photo);
        $customer->delete();

        return response([
                    'status'  => true,
                    'message' => 'Customer successfully removed',
                ], 200);
    }
}