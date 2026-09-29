<?php
namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class JobApplicationController extends Controller
{
   
    public function index()
    {
        $datas = JobApplication::with('content')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.job-applications.index', compact('datas'));
    }


    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);

        if ($application->resume && File::exists(public_path($application->resume))) {
            File::delete(public_path($application->resume));
        }

        $application->delete();

        return redirect()->back()->with('success', 'Application deleted successfully!');
    }
}