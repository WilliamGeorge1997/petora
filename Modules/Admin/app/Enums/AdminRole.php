<?php

namespace Modules\Admin\Enums;

enum AdminRole: string
{
    case SuperAdmin = 'Super Admin';
    case CompanyManager = 'Company Manager';
    case StoreManager = 'Store Manager';
    case ClinicManager = 'Clinic Manager';

    /**
     * Get the human-readable label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => __('admin::role.super_admin'),
            self::CompanyManager => __('admin::role.company_manager'),
            self::StoreManager => __('admin::role.store_manager'),
            self::ClinicManager => __('admin::role.clinic_manager'),
        };
    }

    /**
     * Get all enum values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an associative array of value => label for dropdowns.
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function (array $carry, AdminRole $role) {
            $carry[$role->value] = $role->label();

            return $carry;
        }, []);
    }
}
