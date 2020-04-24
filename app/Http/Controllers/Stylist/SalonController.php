<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Salon;
use App\Stylist;
use Validator;
use Exception;
use DB;
use Auth;


class SalonController extends Controller
{
    /**
     * @return 
     * the Stylist info from both tables
     * User and Styist
     */
    public function index(User $user)
    {
        $id = Auth::id();
        $selectAllinfoForStylist =  DB::table('users')
                                    ->join('stylists', 'stylists.user_id', '=', 'users.id')
                                    ->where('stylists.user_id', $id)
                                    ->get();

                        return response()->json($selectAllinfoForStylist);
     }


    /**
     * @SWG\Post(
     *     path="/api/stylist/add_salon",
     *     summary="create salon",
     *     tags={"Salon"},
     *     description="create new salon",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="salon's name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation message with user Token and user name",
     *      
     *     ),
     *     @SWG\Response(
     *         response="409",
     *         description="message already exist salon",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="validation error",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */

    public function createSalon(Request $request)
    {
       $validator =  Validator::make($request->all() ,[
            'name' => 'required'
        ]);

    try {
        $stylist = $this->findStylistById(Auth::id());
        $hasSalon = $stylist->salon_id;

        if (!$hasSalon) {

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],422);
        }
        
            $makeSalon = Salon::create(['name' => $request->name]);
            $stylist = $this->findStylistById(Auth::id());
            $stylist->salon_id = $makeSalon->id;
            $stylist->save();

            return response()->json(['success','successfully created your salon'],200);
        }else{

            return response()->json(['message' => 'You have already exist Salon'],409);
        }

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

     /**
     * @SWG\Post(
     *     path="/api/stylist/update_salon",
     *     summary="update salon",
     *     tags={"Salon"},
     *     description="update salon info",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="name",
     *         in="path",
     *         description="salon's name",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="address",
     *         in="path",
     *         description="salon's address",
     *         required=false,
     *         type="string",
     *     ),
     * 
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation message with user Token and user name",
     *         @SWG\Schema(ref="#/definitions/Salon"),
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="validation error",
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function updateSalonInfo(Request $request)
    {
        $validator =  Validator::make($request->all() ,[
            'name' => 'required',
            'address' => ''
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],422);
            }

            $stylist = $this->findStylistById(Auth::id());
            $salon = Salon::find($stylist->salon_id);

            if ($request->name) {
                $salon->name = $request->name;
            }
            if ($request->address) {
                $salon->address = $request->address;
            }
            $salon->save();

            return response()->json(['success','successfully updated your info'],200);
            } catch (Exception $e) {
                    
                return response()->json(['error' => 'something went wrong!'], 500);
            }
    }

    /**
     *  Salon owner updating his own images
     * for salon 
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */

    public function updateImages(Request $request)
    {
        $validator =  Validator::make($request->all() ,[
            'images' => ''
        ]);

        try{
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],401);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $name = time().'.'.$file->getClientOriginalName();
                    $thumbnailpath = $file->move(public_path().'/uploads/salon/', $name);
                    $data[] = $name; 
                }
                $stylist = $this->findStylistById(Auth::id());
                $salon = Salon::find($stylist->salon_id);
                $salon->images =json_encode($data); 
                $salon->save();
                
                return response()->json(['success','images uploaded successfully'],200);
            }
         }catch (Exception $e) {

             return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * Salon owner updates location
     *
     * @return \Illuminate\Http\Response
     */

    public function updateLocation(Request $request)
    {
        $this->validate($request, [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

           $stylist = $this->findStylistById(Auth::id());

        if ($salon = Salon::find($stylist->salon_id)) {

            $salon->latitude = $request->latitude;
            $salon->longitude = $request->longitude;
            $salon->save();

            return response()->json(['message' => 'location updated successfuly'], 200);
        }else{

            return response()->json(['error' => 'something went wrong!'], 500);
        }

    }

     /**
     * Salon owner updates location
     *
     * @return \Illuminate\Http\Response
     */

    public function showMySalon()
    {
       try{
           $salonOwner = $this->findStylistById(Auth::id());
           $mySalon = Salon::find($salonOwner)->first();
           
           return response()->json(['salon' => $mySalon], 200);
       } catch (Exception $e) {

           return response()->json(['error' => 'something went wrong!'], 500);
       }

    }
    
     /**
     * Salon owner updates location
     *
     * @return \Illuminate\Http\Response
     */

    public function showMyLocation()
    {
        try{
            $salonOwner = $this->findStylistById(Auth::id());
            $mySalon = Salon::find($salonOwner)->first();
            
            return response()->json(
                ['location' => ['latitude' => $mySalon->latitude ,
                               'longitude' => $mySalon->longitude] ], 200);
        } catch (Exception $e) {
            
            return response()->json(['error' => 'something went wrong!'], 500);
        }
 
     }
 
}
