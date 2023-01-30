<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function addProject(Request $request)
    {
        $email          = $request->email;
        $phone_number   = $request->phone_number;

        if(!$email && !$phone_number){
            return response()->json([
                'status' => 400,
                'error' => 'INVALID REQUEST',
                'data' => 'Email and phone number are empty. Please fill in one of your contacts.'
            ]);
        }else if($email && !$phone_number){
            $find_email = User::where('email', $email)->first();

            if($find_email){
                $users = $find_email;
            }else if(!$find_email){
                $email_validator = Validator::make($request->all(), [
                    'email' => 'email'
                ]);

                if ($email_validator->fails()){
                    return response()->json([
                        'status' => 400,
                        'error' => 'INVALID REQUEST',
                        'data' => $email_validator->errors()
                    ]);
                }

                $users = User::create([
                    'email' => $email,
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'error' => 'INVALID_REQUEST',
                    'data' => $find_email->errors(),
                ], 400);
            }
        }else if(!$email && $phone_number){
            $find_phone_number = User::where('phone_number', $phone_number)->first();
            if($find_phone_number){
                $users = $find_phone_number;
            }else if(!$find_phone_number){
                $users = User::create([
                    'phone_number' => $phone_number,
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'error' => 'INVALID_REQUEST',
                    'data' => $find_phone_number->errors(),
                ], 400);
            }
        }else{
            $find_contact = User::where('email', $email)->orWhere('phone_number', $phone_number)->first();

            if($find_contact){
                $users = $find_contact;
            }else if(!$find_contact){
                $email_validator = Validator::make($request->all(), [
                    'email' => 'email'
                ]);

                if ($email_validator->fails()){
                    return response()->json([
                        'status' => 400,
                        'error' => 'INVALID REQUEST',
                        'data' => $email_validator->errors()
                    ]);
                }

                $users = User::create([
                    'email' => $email,
                    'phone_number' => $phone_number,
                ]);
            }else{
                return response()->json([
                    'status' => 400,
                    'error' => 'INVALID_REQUEST',
                    'data' => $find_contact->errors(),
                ], 400);
            }
        }

        $validator = Validator::make($request->all(), [
            'deadline'      => 'required|string',
            'project_type'  => 'required|string',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => 400,
                'error' => 'INVALID REQUEST',
                'data' => $validator->errors()
            ]);
        }

        $project = Project::create([
            "user_id"           => $users->user_id,
            "project_details"   => $request->project_details,
            "project_type"      => $request->project_type,
            "deadline"          => $request->deadline,
        ]);

        if($project){
            return response()->json([
                'status' => 201,
                'error' => 'NULL',
                'data' => "Your project has been created successfully."
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'error' => 'INVALID_REQUEST',
                'data' => $project->errors(),
            ], 400);
        }
    }

    public function payProject(Request $request){
        if($request->tnc_accepted == 0){
            return response()->json([
                'status' => 400,
                'error' => 'INVALID_REQUEST',
                'data' => 'tnc_accepted is FALSE',
            ], 400);
        }else{
            $imageData = base64_decode($request->payment_proof);
            $image = Image::make($imageData);
            $image->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imageName = $request->project_id.'.jpg';
            $path = storage_path('app/public/payment_proof/'.$imageName);
            $image->save($path, 50);

            $project = Project::find($request->post('project_id'));

            if(!$project){
                return response()->json([
                    'status' => 400,
                    'error' => 'INVALID_REQUEST',
                    'data' => $project->errors(),
                ], 400);
            }else if($project){
                $project->update([
                    'deal'          => $request->deal,
                    'payment_proof' => $imageName,
                    'status'        => "payment-on-confirmation",
                    'tnc_accepted'  => $request->tnc_accepted
                ]);
            }

            return response()->json([
                'status' => 200,
                'error' => 'NULL',
                'data' => "Your project payment has been updated successfully."
            ]);
        }
    }
}
