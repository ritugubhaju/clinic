<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\CreateRequest;
use App\Http\Requests\Appointment\UpdateRequest;
use App\Services\AppointmentService;
use App\Services\DoctorService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
        private DoctorService $doctorService
    ) {}

    public function index(int $doctorId): Response
    {
        $doctor = $this->doctorService->findDoctor($doctorId);

        abort_if(!$doctor, 404);

        $user = auth()->user();

        $linkedDoctor = null;

        if ($user->hasRole('doctor')) {
            $linkedDoctor = $this->doctorService->findDoctorByUserId($user->id);
            abort_if(!$linkedDoctor || (int) $linkedDoctor->id !== $doctorId, 403);
        }

        $page         = (int) request('page', 1);
        $appointments = $this->appointmentService->getPaginatedAppointments($doctorId, 15, $page);

        $canManage = $user->hasRole('doctor') && $linkedDoctor && (int) $linkedDoctor->id === $doctorId;

        return Inertia::render('Appointments/Index', [
            'doctor'       => $doctor,
            'appointments' => $appointments,
            'canManage'    => $canManage,
            'statuses'     => ['pending', 'confirmed', 'cancelled', 'completed'],
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $this->appointmentService->createAppointment($request->validated());

        return back()->with('success', 'Appointment created successfully.');
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $appointment = $this->appointmentService->findAppointment($id);

        abort_if(!$appointment, 404);

        $this->appointmentService->updateAppointment($id, $request->validated());

        return back()->with('success', 'Appointment updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->appointmentService->deleteAppointment($id);

        return back()->with('success', 'Appointment deleted successfully.');
    }
}
