<?php

namespace App\Http\Controllers\Client;

use App\Models\Client\UsersSala;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class UsersSalaController extends AController
{
    public function index(Request $request)
    {
        try {
            $query = UsersSala::query();

            if ($request->filled('users_id')) {
                $query->where('users_id', $request->get('users_id'));
            }

            if ($request->filled('sala_id')) {
                $query->where('sala_id', $request->get('sala_id'));
            }

            $data = $request->filled('per_page')
                ? $query->with(['usuario', 'sala'])->paginate((int) $request->get('per_page'))
                : $query->with(['usuario', 'sala'])->get();

            return $this->success($data, 'UsersSala list retrieved successfully.');
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve UsersSala list.', 500, $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $item = UsersSala::with(['usuario', 'sala'])->findOrFail($id);

            return $this->success($item, 'UsersSala retrieved successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('UsersSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to retrieve UsersSala.', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'users_id' => ['required', 'string', 'exists:usuarios,id'],
                'sala_id' => ['required', 'string', 'exists:sala,id'],
            ]);

            $item = UsersSala::create($data);

            return $this->success($item->load(['usuario', 'sala']), 'UsersSala created successfully.', 201);
        } catch (Throwable $e) {
            return $this->error('Failed to create UsersSala.', 500, $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $item = UsersSala::findOrFail($id);

            $data = $request->validate([
                'users_id' => ['sometimes', 'string', 'exists:usuarios,id'],
                'sala_id' => ['sometimes', 'string', 'exists:sala,id'],
            ]);

            $item->update($data);

            return $this->success($item->fresh(['usuario', 'sala']), 'UsersSala updated successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('UsersSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to update UsersSala.', 500, $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $item = UsersSala::findOrFail($id);
            $item->delete();

            return $this->success(null, 'UsersSala deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('UsersSala not found.', 404);
        } catch (Throwable $e) {
            return $this->error('Failed to delete UsersSala.', 500, $e->getMessage());
        }
    }
}
