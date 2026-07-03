<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): Response
    {
        $page  = (int) request('page', 1);
        $users = $this->userService->getPaginatedUsers(15, $page);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());

        return back()->with('success', 'User created successfully.');
    }

    public function update(UpdateRequest $request, int $id): RedirectResponse
    {
        $user = $this->userService->findUser($id);

        abort_if(!$user, 404);

        $this->userService->updateUser($id, $request->validated());

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        if ($id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $this->userService->deleteUser($id);

        return back()->with('success', 'User deleted successfully.');
    }
}
