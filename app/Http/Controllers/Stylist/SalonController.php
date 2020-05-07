<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Salon;
use App\Stylist;
use App\Services;
use App\Menu;
use Validator;
use Exception;
use DB;
use Auth;
use File;

class SalonController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/api/stylist/add_salon",
     *     summary="create salon",
     *     tags={"Salon"},
     *     description="create salon and if exist , then you can update it using this API",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="salon's name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="location",
     *         in="path",
     *         description="location",
     *         required=false,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="latitude",
     *         in="path",
     *         description="latitude",
     *         required=false,
     *         type="number",
     *     ),
     *     @SWG\Parameter(
     *         name="longitude",
     *         in="path",
     *         description="longitude",
     *         required=false,
     *         type="number",
     *     ),
     *      @SWG\Parameter(
     *         name="images[]",
     *         in="path",
     *         description="images max 10 pieces",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation message ",   
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="validation error",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
    public function addSalon(Request $request)
    {
       $validator =  Validator::make(
           $request->all() ,[
            'name'      => 'required',
            'location'  => '',
            'images.*'  => 'mimes:jpg,jpeg,gif,png',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $stylist = $this->findStylistById(Auth::id());
            $salon = Salon::find($stylist->salon_id);

            if ($salon) {
                $this->updateSalon($salon , $request);
            } else {
                $salon = new Salon();
                
                if ($request->name) {
                    $salon->name = $request->name;
                }

                if ($request->location) {
                    foreach ($request->location as $key => $value) {
                        $salon->address   = $value['address'];
                        $salon->latitude  = $value['latitude'];
                        $salon->longitude = $value['longitude'];
                    }
                }

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $name = time().'.'.$file->getClientOriginalName();
                        $file->move(public_path().'/uploads/salon/'. Auth::id() , $name);
                        $data[] = $name; 
                    }
                    $salon->images = json_encode($data);
                }
            }

            $salon->save();
            $stylist           = $this->findStylistById(Auth::id());
            $stylist->salon_id = $salon->id;
            $stylist->save();

            return response()->json(['success','successfully created your salon'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * return data form stylist
     * table by using stylist id
     * @return array  
     */
    public function findStylistById($id)
    {
        return User::find($id)->stylist;
    }

    public function updateSalon($salon ,$request)
    {   
        try {
            if ($request->name) {
                $salon->name = $request->name;
            }

            if ($request->location) {
                foreach ($request->location as $key => $value){
                    $salon->address    = $value['address'];
                    $salon->latitude   = $value['latitude'];
                    $salon->longitude  = $value['longitude'];
                }
            }

            if ($request->hasFile('images')) {
                $this->deleteSalonImages(Auth::id());
                foreach ($request->file('images') as $file) {
                    $name = time().'.'.$file->getClientOriginalName();
                    $file->move(public_path().'/uploads/salon/'.Auth::id(), $name);
                    $data[] = $name;
                }
                $salon->images = json_encode($data);
            }

            return response()->json(['success' => 'successfully updated your salon'],200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_my_salon",
     *     summary="show salon info",
     *     tags={"Salon"},
     *     description="get salon info like name,images ,address for owner 'stylist'",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response=200,
     *         description="salon full info and images",
     *         @SWG\Schema(ref="#/definitions/Salon"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
    public function showMySalon()
    {
        try{
            $salonOwner = $this->findStylistById(Auth::id());
            $mySalon    = Salon::find($salonOwner->salon_id)->first();
            
            return response()->json(['salon' => $mySalon], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/update_location",
     *     summary="update salon's location",
     *     tags={"Salon"},
     *     description="update salon location for salon owner 'stylist'",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="location",
     *         in="path",
     *         description="location will be updated by loation object that consist ,address, latitude,longitude ",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="location update",
     *         @SWG\Schema(ref="#/definitions/Salon"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function updateLocation(Request $request)
    {
        $this->validate($request, [
                'location' => ''
            ]);

        $stylist = $this->findStylistById(Auth::id());

        if ($salon = Salon::find($stylist->salon_id)) {

            if ($request->location) {
                foreach ($request->location as $key => $value){
                    $salon->address   = $value['address'];
                    $salon->latitude  = $value['latitude'];
                    $salon->longitude = $value['longitude'];
               }
            }

            return response()->json(['message' => 'location updated successfuly'], 200);
        } else {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_my_location",
     *     summary="show salon's location",
     *     tags={"Salon"},
     *     description="get salon location for salon owner 'stylist'",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response=200,
     *         description="return location and iside lat and long",
     *         @SWG\Schema(ref="#/definitions/Salon"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function showMyLocation()
    {
        try{
            $salonOwner = $this->findStylistById(Auth::id());
            $mySalon = Salon::find($salonOwner)->first();
            
            return response()->json(
                ['location' => [
                    'address'  => $mySalon->address ,
                    'latitude' => $mySalon->latitude ,
                    'longitude' => $mySalon->longitude
                    ] ], 200);
        } catch (Exception $e) { 
            return response()->json(['error' => 'something went wrong!'], 500);
        }
     }

     /**
     * @SWG\Post(
     *     path="/api/stylist/add_service",
     *     summary="add service to salon",
     *     tags={"Salon"},
     *     description="add services",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="name",
     *         required=true,
     *         type="string",
     *     ),
     *  @SWG\Parameter(
     *         name="price",
     *         in="path",
     *         description="price",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="service added successfuly",
     *         @SWG\Schema(ref="#/definitions/Services"),
     *     ),
     *   @SWG\Response(
     *         response=422,
     *         description="validation error",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
     public function addService(Request $request)
     {
        $validator =  Validator::make(
            $request->all() ,[
             'name'      => 'required',
             'price'      => 'required',
         ]);

        try{
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            
            $stylist = $this->findStylistById(Auth::id());

            $service            = new Services();
            $service->name      = $request->name;
            $service->price     = $request->price;
            $service->salon_id  = $salonOwner->salon_id;

            $service->save();

            return response()->json(['message' => 'service added successfuly'], 200);
            
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
     }
     
    /**
     * @SWG\Get(
     *     path="/api/stylist/my_services",
     *     summary="show salon's services for stylis 'for self'",
     *     tags={"Salon"},
     *     description="get salon services'",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response=200,
     *         description="return all salon service",
     *         @SWG\Schema(ref="#/definitions/Services"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
     public function listServices()
     {
        try{
            $salonOwner  = $this->findStylistById(Auth::id());
            $allServices =  Salon::findOrfail($salonOwner->salon_id)->service;
            
            return response()->json( ['allServices' => $allServices ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
     }

    /**
     * @SWG\Get(
     *     path="/api/stylist/delete_service/{id}",
     *     summary="delete specifc service",
     *     tags={"Salon"},
     *     description="delete sservice",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response=200,
     *         description="service deleted",
     *         @SWG\Schema(ref="#/definitions/Services"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
    public function deleteService($id)
    {
       try{
           $salonOwner  = $this->findStylistById(Auth::id());
           $service     =  Services::where('salon_id', $salonOwner->salon_id)
                                   ->where('id',$id);

           $service->delete();

           return response()->json(['success' => 'service deleted'] , 200);
       } catch (Exception $e) {
           return response()->json(['error' => 'something went wrong!'], 500);
       }
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_service/{id}",
     *     summary="show specifc service",
     *     tags={"Salon"},
     *     description="show sservice",
     *     security={{"passport": {}}},
     *     @SWG\Response(
     *         response=200,
     *         description="service",
     *         @SWG\Schema(ref="#/definitions/Services"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
    public function showService($id)
    {
       try{
           $service = Services::findOrfail($id);

           return response()->json(['service' => $service] , 200);
       } catch (Exception $e) {
           return response()->json(['error' => 'something went wrong!'], 500);
       }
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/update_service/{id}",
     *     summary="add service to salon",
     *     tags={"Salon"},
     *     description="update services",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="name",
     *         required=true,
     *         type="string",
     *     ),
     *     description="update services",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="price",
     *         in="path",
     *         description="price",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="service added successfuly",
     *         @SWG\Schema(ref="#/definitions/Services"),
     *     ),
     *   @SWG\Response(
     *         response=422,
     *         description="validation error",
     *        
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * )
     */
    public function updateService(Request $request ,$id)
    {
        $validator =  Validator::make(
            $request->all() ,[
             'name'       => 'required',
             'price'      => 'required',
             ]);

        try {
            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()], 422);
            }

            $salonOwner     = $this->findStylistById(Auth::id());
            $service        =  Services::where('salon_id', $salonOwner->salon_id)
                                    ->where('id',$id);
            $service->name  = $request->name;
            $service->price = $request->price;

            return response()->json(['service' => $service, 'message' => 'service updateed'] , 200);
       } catch (Exception $e) {
           return response()->json(['error' => 'something went wrong!'], 500);
       }
    }

     public function deleteSalonImages($id)
     {
        try{
           $stylist = $this->findStylistById(Auth::id());
            $salon = Salon::find($stylist->salon_id);
            $images = json_decode($salon->images);

            foreach ($images as $key => $value){
                $path = public_path().'/uploads/salon/'. $id .'/'. $value;
                File::delete($path);
            }

        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
     }
}
