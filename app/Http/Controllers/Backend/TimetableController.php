<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Timetable;
use App\Models\Grade;
use App\Http\Controllers\Traits\FileUploadTrait;

class TimetableController extends Controller
{
	use FileUploadTrait;

    /** 
	* List of timetables for each class
    */
	public function getClassTimeTable()
	{
		return view('backend.timetables.class');
	}

	public function getClassTimetableByAjax()
	{
		$classes = Grade::all();

		$data =[];
		foreach ($classes as $item) {
			$temp = [];
			$temp['index'] = '';
            $temp['class'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
								<div class="avatar avatar-sm mr-8pt">
									<span class="avatar-title rounded bg-primary text-white">
										'. substr($item->name, 0, 2) .'
									</span>
								</div>
								<div class="media-body">
									<div class="d-flex flex-column">
										<small class="js-lists-values-project">
											<strong>'. $item->name .'</strong>
										</small>
										<small class="text-70">'. $item->institution->name .'</small>
									</div>
								</div>
							</div>';
			
			$divisions_html = '';
			foreach($item->divisions as $division) {
				$divisions_html .= '<div class="avatar avatar-sm mr-8pt">
										<span class="avatar-title rounded bg-light text-black-100">'. $division->name .'</span>
									</div>';

				if($item->classTimeTableForDivision($division->id)) {
					if($item->classTimeTableForDivision($division->id)->type == 'pdf') {
						$temp['preview'] = '<div class="avatar avatar-xxl avatar-4by3">
												<embed src="'. asset('/storage/attachments/' . $item->classTimeTableForDivision($division->id)->url) .'" alt="Avatar" class="avatar-img rounded">
											</div>';
					} else {
						$temp['preview'] = '<div class="avatar avatar-xxl avatar-4by3">
												<img src="'. asset('/storage/attachments/' . $item->classTimeTableForDivision($division->id)->url) .'" alt="Avatar" class="avatar-img rounded">
											</div>';
					}
					
				} else {
					$temp['preview'] = '<div class="avatar avatar-xxl avatar-4by3">
											<img src="'. asset('/assets/img/no-image.jpg') .'" alt="Avatar" class="avatar-img rounded">
										</div>';
				}
			}

			$temp['divisions'] = $divisions_html;
			$temp['type'] = 'pdf';
			
			$edit_route = route('admin.timetables.class.edit', $item->id);
			$btn_edit = view('layouts.buttons.edit', ['edit_route' => $edit_route]);

			$delete_route = route('admin.timetables.class.delete', $item->id);
			$btn_delete = view('layouts.buttons.delete', ['delete_route' => $delete_route]);

           	$temp['actions'] = $btn_edit . '&nbsp' .$btn_delete;
           	array_push($data, $temp);
		}

		return response()->json([
			'success' => true,
			'data' => $data
		]);
	}

	public function editClassTimeTable($id)
	{
		$class = Grade::find($id);
		return view('backend.timetables.class-edit', compact('class'));
	}

	public function updateClassTimeTable(Request $request, $id)
	{
		$inputs = $request->all();
		foreach($inputs as $key => $value) {
			if(Str::is('file_timetable_*', $key)) {
				$division_id = Str::after($key, 'file_timetable_');
				$file = $request->file('file_timetable_' . $division_id);

				if(!empty($file)) {
					$timetable = Timetable::where('grade_id', $id)->where('division_id', $division_id)->first();
					if($timetable) {
						// Delete existing img file
						if (File::exists(public_path('/storage/attachments/' . $timetable->url))) {
							File::delete(public_path('/storage/attachments/' . $timetable->url));
						}
	
						$attachment_url = $this->saveFile($file);
						$timetable->save();
					} else {
						Timetable::create([
							'grade_id' => $id,
							'division_id' => $division_id,
							'type' => array_last(explode('.', $file->getClientOriginalName())),
							'url' => $this->saveFile($file)
						]);
					}
				}
			}
		}

		return response()->json([
			'success' => true
		]);
	}

	public function deleteClassTimeTable($id)
	{
		try {
            Timetable::where('grade_id', $id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
	}

	public function getExamTimeTable()
	{
		$classes = Grade::paginate(5);
		return view('backend.timetables.exam', compact('classes'));
	}

	public function editExamTimeTable($id)
	{
		$class = Grade::find($id);
		return view('backend.timetables.exam-edit', compact('class'));
	}

	public function storeTimeTable(Request $request)
	{
		$name = $request->name;
		$file = $request->file('file_timetable');
		$attachment_url = $this->saveFile($file);

		$timetable = Timetable::create([
			'grade_id' => $request->class_id,
			'name' => $request->name,
			'type' => array_last(explode('.', $file->getClientOriginalName())),
			'table_type' => 1,
			'url' => $attachment_url
		]);

		return back()->with('success', 'Created Successfully');
	}

	public function updateExamTimeTable(Request $request, $id)
	{
		$timetable = Timetable::find($id);

		$file = $request->file('file_timetable');

		if(!empty($file)) {
			// Delete existing img file
			if (File::exists(public_path('/storage/attachments/' . $timetable->url))) {
				File::delete(public_path('/storage/attachments/' . $timetable->url));
			}
			$attachment_url = $this->saveFile($file);
			$timetable->url = $attachment_url;
			$timetable->type = array_last(explode('.', $file->getClientOriginalName()));
		}
		$timetable->name = $request->name;
		$timetable->save();

		return response()->json([
			'success' => true
		]);
	}

	public function orderChange(Request $request)
	{
		if(isset($request->data)) {
			$i = 0;
			foreach($request->data as $item) {
				$timetable = Timetable::find($item['id']);
				$timetable->order = $i;
				$timetable->save();
				$i++;
			}
		}

		return response()->json([
			'success' => true
		]);
	}
}
