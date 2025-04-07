<?php
namespace webspell;

class AccessControl
{
    public static function hasRole($userID, string $role): bool
    {
        if ($role === 'super') {
            return self::inGroup($userID, 'super');
        }

        // immer Zugriff für Superadmins
        return self::inGroup($userID, $role) || self::inGroup($userID, 'super');
    }

    private static function inGroup($userID, string $group): bool
    {
        $userID = (int) $userID;
        $result = safe_query(
            "SELECT userID FROM `" . PREFIX . "user_groups`
             WHERE `userID` = {$userID} AND `{$group}` = 1"
        );
        return (mysqli_num_rows($result) > 0);
    }

    public static function isBanned($userID): bool
    {
        $userID = (int) $userID;
        $result = safe_query(
            "SELECT userID FROM `" . PREFIX . "user`
             WHERE `userID` = {$userID} AND (`banned` = 'perm' OR `banned` IS NOT NULL)"
        );
        return (mysqli_num_rows($result) > 0);
    }
}