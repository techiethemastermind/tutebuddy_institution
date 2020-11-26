<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Institution;
use App\User;
use Hash;
use App\Http\Controllers\Traits\FileUploadTrait;

class InstitutionController extends Controller
{
	use FileUploadTrait;

    /**
     * Display a listing of the Institutions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = Institution::where('prefix', '!=', 'admin')->paginate(10);
        return view('backend.institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.institutions.create');
    }

    /**
    * Store new Institution
    */
    public function store(Request $request)
    {
    	$input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $institution = Institution::create($input);

        $logo = $request->has('logo') ? $request->file('logo') : false;

        if($logo) {
            $logo_url = $this->saveImage($logo, 'logos');
            $institution->logo = $logo_url;
            $institution->save();
        }
    
        // Create a New Institution Admin
        $user_data = [
        	'name' => $input['prefix'] . ' Admin',
        	'email' => $input['email'],
        	'institution_id' => $institution->id,
        	'password' => $input['password']
        ];

        $user = User::create($user_data);
        $user->assignRole('Institution Admin');

        return response()->json([
        	'success' => true,
        	'id' => $institution->id
        ]);
    }

    // Edit Institution
    public function edit($id)
    {
    	$institution = Institution::find($id);
    	return view('backend.institutions.edit', compact('institution'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    
        $input = $request->except('logo');

        if(!empty($input['password'])) { 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));    
        }

        $institution = Institution::find($id);
        $institution->update($input);

        $logo = $request->has('logo') ? $request->file('logo') : false;
        if($logo) {
            $logo_url = $this->saveImage($logo, 'logos');
            $institution->logo = $logo_url;
            $institution->save();
        }
    
        return response()->json([
        	'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $institution = Institution::find($id);
        return view('backend.institutions.show', compact('institution'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Institution::find($id)->delete();
        return redirect()->route('admin.institutions.index')
                        ->with('success','Selected Institution deleted successfully');
    }
}
