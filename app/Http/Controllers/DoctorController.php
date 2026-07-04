<?php

namespace App\Http\Controllers;

use App\Http\Requests\Doctor\CreateRequest;
use App\Http\Requests\Doctor\UpdateRequest;
use App\Services\DoctorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorController extends Controller
{
    public function __construct(private DoctorService $doctorService) {}

    public function index(): Response
    {
        $page    = (int) request('page', 1);
        $doctors = $this->doctorService->getPaginatedDoctors(15, $page);

        return Inertia::render('Doctors/Index', [
            'doctors'   => $doctors,
            'canManage' => auth()->user()->hasRole('receptionist'),
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $this->doctorService->createDoctor($request->validated());

        return back()->with('success', 'Doctor created successfully.');
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $doctor = $this->doctorService->findDoctor($id);

        abort_if(!$doctor, 404);

        $this->doctorService->updateDoctor($id, $request->validated());

        return back()->with('success', 'Doctor updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->doctorService->deleteDoctor($id);

        return back()->with('success', 'Doctor deleted successfully.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
          'file' => ['required', 'file', 'mimetypes:text/plain,text/csv', 'max:2048'],
        ]);

        $file = $request->file('file');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('imports', $filename);
        $count = $this->doctorService->importFromCsv(storage_path('app/' . $path));

        return back()->with('success', "{$count} doctor(s) imported successfully.");
    }

    public function export(): StreamedResponse
    {
        return $this->doctorService->exportToCsv();
    }
}
