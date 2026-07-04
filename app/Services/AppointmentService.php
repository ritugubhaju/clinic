<?php

namespace App\Services;

use App\Repositories\Appointment\AppointmentRepositoryInterface;

class AppointmentService
{
    public function __construct(private AppointmentRepositoryInterface $appointmentRepository) {}

    public function getPaginatedAppointments(int $doctorId, int $perPage = 15, int $page = 1): array
    {
        $appointments = $this->appointmentRepository->paginateByDoctor($doctorId, $perPage, $page);
        $total        = $this->appointmentRepository->totalByDoctor($doctorId);

        return [
            'data'         => $appointments,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    public function createAppointment(array $data): bool
    {
        return $this->appointmentRepository->create($data);
    }

    public function updateAppointment(int $id, array $data): bool
    {
        return $this->appointmentRepository->update($id, $data);
    }

    public function deleteAppointment(int $id): bool
    {
        return $this->appointmentRepository->delete($id);
    }

    public function findAppointment(int $id): ?object
    {
        return $this->appointmentRepository->find($id);
    }
}
