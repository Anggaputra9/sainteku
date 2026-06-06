<?php

namespace Modules\MasterData\Support;

use Modules\MasterData\app\Models\User;

class UserIdService
{
    public function __construct(
        private readonly UserCodeGenerator $userCodeGenerator,
        private readonly UserIdMigrator $userIdMigrator,
    ) {}

    public function assignIdForNewUser(array $roleIds, string $unitId): string
    {
        $roleCode = $this->userCodeGenerator->resolvePrimaryRoleCodeFromRoleIds($roleIds);

        return $this->userCodeGenerator->generateNext($roleCode, $unitId);
    }

    public function reassignIfNeeded(User $user, array $roleIds, string $unitId): ?string
    {
        $roleCode = $this->userCodeGenerator->resolvePrimaryRoleCodeFromRoleIds($roleIds);

        if ($this->userCodeGenerator->matchesBucket($user->id, $roleCode, $unitId)) {
            return null;
        }

        $newId = $this->userCodeGenerator->generateNext($roleCode, $unitId);
        $this->userIdMigrator->changeUserId($user->id, $newId);

        return $newId;
    }
}