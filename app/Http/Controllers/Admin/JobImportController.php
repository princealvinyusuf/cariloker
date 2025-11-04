<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\JobsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class JobImportController extends Controller
{
	public function create()
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}
		return view('admin.jobs.import');
	}

	public function store(Request $request)
	{
		if (!Auth::user() || Auth::user()->role !== 'admin') {
			abort(403);
		}

		$request->validate([
			'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
		]);

		$path = $request->file('file')->store('imports');

		$import = new JobsImport;
		$import->import(storage_path('app/'.$path));

		$successCount = $import->getSuccessCount();
		$errors = $import->getErrors();

		return Redirect::route('admin.jobs.import.create')
			->with('status', "Imported {$successCount} rows")
			->with('import_errors', $errors);
	}
}


