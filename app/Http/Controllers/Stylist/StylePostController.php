<?php

namespace App\Http\Controllers\Stylist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Salon;
use App\Stylist;
use App\StylePost;
use Validator;
use Exception;
use DB;
use Auth;
use File;

class StylePostController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/api/stylist/make_style_post",
     *     summary="create style post",
     *     tags={"StylePost"},
     *     description="create new style post",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="desc",
     *         in="path",
     *         description="post's description",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="tag",
     *         in="path",
     *         description="tags",
     *         required=false,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="images[]",
     *         in="path",
     *         description="images max 10 pieces",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="brand name",
     *         in="path",
     *         description="brand name",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="style_name",
     *         in="path",
     *         description="style name",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="color",
     *         in="path",
     *         description="color",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation message ",
     *         @SWG\Schema(ref="#/definitions/StylePost")
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
    public function createStylePost(Request $request)
    {
       $validator =  Validator::make(
           $request->all() ,[
            'images'     => 'required',
            'images.*'   => 'mimes:jpg,jpeg,gif,png',
            'desc'       => 'max:2000',
            'tag'        => '',
            'brand_name' => '',
            'style_name' => '',
            'color'      => '',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $stylePost= new StylePost();

            if ($request->desc){
                $stylePost->description = $request->desc;
            }

            if ($request->tag) {
                foreach ($request->tag as $tags) {
                    $stylePost->tags =  $tags;
                }
            }

            if ($request->brand_name) {
                $stylePost->brand_name = $request->brand_name;
            }

            if ($request->style_name) {
                $stylePost->style_name = $request->style_name;
            }

            if ($request->color) {
                $stylePost->color = $request->color;
            }

            if ($request->hasFile('images')) {
                $data             = $this->uploadPostImage($request->images ,Auth::id());
                $stylePost->media = json_encode($data); 
            }

            // this might be needed for future 
            // if ($request->hasFile('clip')) {
            //     $data             = $this->uploadClip($request->clip ,Auth::id());
            //     $stylePost->media = json_encode($data);  
            // }

            $stylist = User::find(Auth::id())->stylist;
            $stylePost->stylist_id = $stylist->id;
            $stylePost->save();

            return response()->json(
                ['success' => $stylePost ,
                'message' => 'post updated successfully'] ,
                200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    private function uploadPostImage($image , $id)
    {
        foreach ($image as $file) {
            $name   = time().'.'.$file->getClientOriginalName();
            $file->move(public_path().'/uploads/style_post/'. $id ,$name);
            $data[] = $name; 
        }
        return $data;
    }

    // this might be needed for future 
    // private function uploadClip($clip ,$id)
    // {
    //     $name = time().'.'.$clip->getClientOriginalName();
    //     $clip->move(public_path().'/uploads/style_post/'. $id ,$name);
    //     return $name;
    // }

    /**
     * @SWG\Get(
     *     path="/api/stylist/delete_post/{id}",
     *     summary="delete some post by stylist",
     *     tags={"StylePost"},
     *     description="delete style post'",
     *     security={{"passport": {}}},
 
     *     @SWG\Response(
     *         response=200,
     *         description="Post has been deleted'",
     *      
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function deletePost($id)
    {
        try {
            $stylePost = StylePost::findOrfail($id);
            $images    = json_decode($stylePost->media);

            if ($stylePost->stylits_id = Auth::id()) {
                $this->deletePostImages($images , Auth::id());
                $stylePost->delete();
            }

                return response()->json(['Post has been deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_all_post",
     *     summary="show all post for stylist",
     *     tags={"StylePost"},
     *     description="delete some style post'",
     *     security={{"passport": {}}},
 
     *     @SWG\Response(
     *         response=200,
     *         description="show all post for stylist",
     *         @SWG\Schema(ref="#/definitions/StylePost"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function showAllPosts()
    {
        try {
            $stylePost = StylePost::all();
                return response()->json(['stylePosts' => $stylePost] , 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Get(
     *     path="/api/stylist/show_post/{id}'",
     *     summary="show specific post for stylist",
     *     tags={"StylePost"},
     *     description="show some style post'",
     *     security={{"passport": {}}},
 
     *     @SWG\Response(
     *         response=200,
     *         description="show specific post for stylist",
     *         @SWG\Schema(ref="#/definitions/StylePost"),
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="error something went wrong",
     *     ),
     * 
     * )
     */
    public function showPost($id)
    {
        try {
            $stylePost = StylePost::findOrfail($id)->first();

            if ($stylePost){
                return response()->json(['stylePost' => $stylePost] , 200);
            }

        } catch (Exception $e) {
            return response()->json(['error' => 'something went wrong!'], 500);
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/stylist/update_post/{id}'",
     *     summary="update style post",
     *     tags={"StylePost"},
     *     description="update style post",
     *     security={{"passport": {}}},
     *     @SWG\Parameter(
     *         name="desc",
     *         in="path",
     *         description="post's description",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="tag",
     *         in="path",
     *         description="tags",
     *         required=false,
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="images[]",
     *         in="path",
     *         description="images max 10 pieces",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="brand name",
     *         in="path",
     *         description="brand name",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="style_name",
     *         in="path",
     *         description="style name",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="color",
     *         in="path",
     *         description="color",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/StylePost")
     *      
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
    public function updateStylePost(Request $request , $id)
    {
        $validator =  Validator::make(
            $request->all() ,[
            'images'     => 'required',
            'images.*'   => 'mimes:jpg,jpeg,gif,png',
            'desc'       => 'max:2000',
            'tag'        => '',
            'brand_name' => '',
            'style_name' => '',
            'color'      => '',
         ]);

        try{
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],401);
            }

            $stylePost = StylePost::findOrfail($id);
            
            if ($request->desc) {
                $stylePost->description = $request->desc;
            }
    
            if ($request->tag) {
                foreach ($request->tag as $tags){
                    $stylePost->tags =  $tags;
                }
            }
    
            if ($request->brand_name) {
                $stylePost->brand_name = $request->brand_name;
            }
    
            if ($request->style_name) {
                $stylePost->style_name = $request->style_name;
            }
    
            if ($request->color) {
                $stylePost->color = $request->color;
            }

            $images = json_decode($stylePost->media);
            $this->deletePostImages($images, Auth::id());

            if ($request->hasFile('images')) {
                $data             = $this->uploadPostImage(
                                    $request->images ,Auth::id());
                $stylePost->media = json_encode($data); 
            }

            $stylePost->save();    

            return response()->json(
                ['success' => $stylePost ,
                'message' => 'postt updated successfully'] ,
                200);

         }catch (Exception $e) {
             return response()->json(['error' => 'something went wrong!'], 500);
        }
    }


    private function deletePostImages($images ,$userId)
    {
       try{
           foreach ($images as $key => $value) {
               $path = public_path().'/uploads/style_post/'. $userId.'/'. $value;
               File::delete($path);
           }

       } catch (Exception $e) {
           return response()->json(['error' => 'something went wrong!'], 500);
       }
    }
}
