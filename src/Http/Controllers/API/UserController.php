<?php

namespace Leobsst\LaravelCmsCore\Http\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Leobsst\LaravelCmsCore\Concerns\Api\ApiResponse;
use Leobsst\LaravelCmsCore\Http\Controllers\CoreController;
use Leobsst\LaravelCmsCore\Http\Requests\Api\Users\GetUserRequest;
use Leobsst\LaravelCmsCore\Models\User;
use Leobsst\LaravelCmsCore\Models\UserEmail;

class UserController extends CoreController
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (auth()->user()->hasRole(roles: 'admin')) {
                return $this->generateSuccess(message: 'User data retrieved successfully.', data: User::where(column: 'enabled', operator: true)->get(columns: [
                    'id',
                    'email',
                    'name',
                    'avatar',
                    'bio',
                    'enabled',
                ])->map(callback: function ($user): array {
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                        'bio' => $user->bio,
                        'enabled' => $user->enabled,
                        'emails' => $user->emails()->where('email', '!=', $user->email)->get(['id', 'email', 'email_verified_at'])->toArray(),
                    ];
                })->toArray());
            }

            return $this->generateError(cause: 'You are not authorized to view this user data.', code: 403);
        } catch (Exception $e) {
            return $this->generateError(cause: $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GetUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = isset($data['user']) && filled(value: $data['user']) ? UserEmail::firstWhere(column: 'email', operator: $data['user'])->user : auth()->user();

            if (auth()->user()->hasRole(roles: 'admin') || $user->email === auth()->user()->email) {
                $data = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'bio' => $user->bio,
                    'enabled' => $user->avatar_url,
                ];

                return $this->generateSuccess(message: 'User data retrieved successfully.', data: array_merge($data, [
                    'emails' => $user->emails()->where('email', '!=', $user->email)->get(['id', 'email', 'email_verified_at'])->toArray(),
                ]));
            }

            return $this->generateError(cause: 'You are not authorized to view this user data.', code: 403);
        } catch (Exception $e) {
            return $this->generateError(cause: $e->getMessage());
        }
    }
}
