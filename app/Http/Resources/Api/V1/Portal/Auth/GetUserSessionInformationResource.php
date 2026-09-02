<?php

namespace App\Http\Resources\Api\V1\Portal\Auth;

use App\Models\Tenant\TenantUser;
use Illuminate\Http\Resources\Json\JsonResource;

class GetUserSessionInformationResource extends JsonResource
{
    public function toArray($request)
    {
        $photo = null;
        if (isset($this->photo)) {
            $photo = [
                "uuid" => $this->photo->uuid,
                "original_file_name" => $this->photo->original_name,
                "url" => $this->photo->url
            ];
        }

        $tenants = TenantUser::where('user_id', $this->id)
            ->with('tenant')
            ->get()
            ->map(function ($tnt_user) {
                if (!$tnt_user->tenant) return null;
                return [
                    'uuid' => $tnt_user->tenant->uuid,
                    'name' => $tnt_user->tenant->name,
                    'slug' => $tnt_user->tenant->slug,
                    'is_suspended' => $tnt_user->tenant->is_suspended,
                ];
            })->filter()->values();

        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->detailUser->full_name ?? null,
            'photo' => $photo,
            'role' => [
                'uuid' => $this->roleUser->role->uuid ?? null,
                'name' => $this->roleUser->role->name ?? null,
            ],
            'user_information' => [
                'name' => $this->detailUser->full_name ?? null,
                'phone_number' => $this->detailUser->phone_number ?? null,
            ],
            'tenants' => $tenants
        ];
    }
}
