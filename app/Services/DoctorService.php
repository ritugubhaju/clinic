<?php

namespace App\Services;

use App\Repositories\Doctor\DoctorRepositoryInterface;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorService
{
    public function __construct(private DoctorRepositoryInterface $doctorRepository) {}

    public function getPaginatedDoctors(int $perPage = 15, int $page = 1): array
    {
        $doctors = $this->doctorRepository->paginate($perPage, $page);
        $total   = $this->doctorRepository->total();

        return [
            'data'         => $doctors,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($total / $perPage),
        ];
    }

    public function createDoctor(array $data): int
    {
        return $this->doctorRepository->create($data);
    }

    public function updateDoctor(int $id, array $data): bool
    {
        return $this->doctorRepository->update($id, $data);
    }

    public function deleteDoctor(int $id): bool
    {
        return $this->doctorRepository->delete($id);
    }

    public function findDoctor(int $id): ?object
    {
        return $this->doctorRepository->find($id);
    }

    public function findDoctorByUserId(int $userId): ?object
    {
        return $this->doctorRepository->findByUserId($userId);
    }

    public function licenseExists(string $license, ?int $excludeId = null): bool
    {
        return $this->doctorRepository->licenseExists($license, $excludeId);
    }

    public function allDoctors(): array
    {
        return $this->doctorRepository->all();
    }

    public function importFromCsv(string $filePath): int
    {
        $count = 0;

        $rows = SimpleExcelReader::create($filePath)->getRows();

        $rows->each(function (array $row) use (&$count) {
            if (empty($row['name']) || empty($row['specialization']) || empty($row['license_no'])) {
                return;
            }

            if ($this->doctorRepository->licenseExists($row['license_no'])) {
                return;
            }

            $this->doctorRepository->create([
                'name'           => trim($row['name']),
                'specialization' => trim($row['specialization']),
                'license_no'     => trim($row['license_no']),
                'user_id'        => null,
            ]);

            $count++;
        });

        return $count;
    }

    public function exportToCsv(): StreamedResponse
    {
        $doctors = $this->doctorRepository->all();

        return SimpleExcelWriter::streamDownload('doctors.csv')
            ->addHeader(['name', 'specialization', 'license_no'])
            ->addRows(array_map(fn($d) => [
                'name'           => $d->name,
                'specialization' => $d->specialization,
                'license_no'     => $d->license_no,
            ], $doctors))
            ->toBrowser();
    }
}
