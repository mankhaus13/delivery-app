<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\DTO\API\Admin\User\CreateUserDTO;
use App\DTO\API\Admin\User\DeleteUserDTO;
use App\DTO\API\Admin\User\EditUserDTO;
use App\DTO\API\Admin\User\GetLogDTO;
use App\Http\Resources\Admin\User\CreateUserResource;
use App\Http\Resources\Admin\User\DeleteUserResource;
use App\Http\Resources\Admin\User\EditUserResource;
use App\Http\Resources\Admin\User\UserCollection;
use App\Models\Enums\User\UserRole;
use App\Models\User;
use App\Models\UserLog;

use function public_path;

final readonly class UserService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): UserCollection
    {
        return new UserCollection(User::query()
            ->where('role', UserRole::Expeditor->value)
            ->paginate(self::PAGE_SIZE));
    }

    public function create(CreateUserDTO $dto): CreateUserResource
    {
        $user = User::query()->create([
            'first_name' => $dto->firstName,
            'second_name' => $dto->secondName,
            'surname' => $dto->surname,
            'phone' => $dto->phone,
            'password' => bcrypt($dto->password),
            'external_id' => $dto->externalId,
        ]);

        return new CreateUserResource($user);
    }

    public function edit(EditUserDTO $dto): EditUserResource
    {
        $user = User::query()->find($dto->id);
        $user->update([
            'first_name' => $dto->first_name ?? $user->fisrt_name,
            'second_name' => $dto->second_name ?? $user->second_name,
            'surname' => $dto->surname ?? $user->surname,
            'phone' => $dto->phone ?? $user->phone,
            'password' => $dto->password ? bcrypt($dto->password) : $user->password,
        ]);

        return new EditUserResource($user);
    }

    public function delete(DeleteUserDTO $dto): DeleteUserResource
    {
        User::query()->where('id', $dto->id)->delete();

        return new DeleteUserResource([]);
    }

    public function getLogs(GetLogDTO $dto): array
    {
        $this->generateLogFile($dto);
        $file = public_path() . "/download/user$dto->expeditorId.log";
        $fileName = "user$dto->expeditorId.log";
        $headers = [
            'Content-Type: application/log',
        ];

        return [$file, $fileName, $headers];
    }

    private function generateLogFile(GetLogDTO $dto): void
    {
        $logs = UserLog::query()
            ->where('expeditor_id', $dto->expeditorId)
            ->get()
            ->toArray();

        // Define the file path
        $filePath = public_path() . "/download/user$dto->expeditorId.log";

        // Open the file for writing (create if not exists)
        $fileHandle = fopen($filePath, 'a');

        // Loop through each log record and write it to the file
        foreach ($logs as $log) {
            fwrite($fileHandle, json_encode($log) . PHP_EOL);
        }

        // Close the file handle
        fclose($fileHandle);
    }
}
