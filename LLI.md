# [ANCHORS]
- `resources/views/layouts/navigation.blade.php`
- `resources/views/components/dropdown.blade.php`
- `resources/views/components/dropdown-link.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/update-profile-information-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`

# [LLIs]
## Phase 1: Nav & Dropdown Light Mode
1. Edit `resources/views/layouts/navigation.blade.php`: Hapus class `dark:text-gray-200` pada komponen `x-application-logo`.
2. Edit `resources/views/components/dropdown.blade.php`: Cari properti `contentClasses` (biasanya `bg-white dark:bg-gray-700`) dan hapus atribut `dark:bg-gray-700` agar selalu light theme.
3. Edit `resources/views/components/dropdown-link.blade.php`: Hapus semua class dengan prefix `dark:` (misalnya `dark:text-gray-300`, `dark:hover:bg-gray-800`, `dark:focus:bg-gray-800`).

## Phase 2: Profile Page & Forms Light Mode
1. Edit `resources/views/profile/edit.blade.php`: Hapus semua utilitas `dark:` (seperti `dark:text-gray-200`, `dark:bg-gray-800`) agar wrapper dan header profile selalu light theme.
2. Edit `resources/views/profile/partials/update-profile-information-form.blade.php`: Lakukan pencarian dan penghapusan menyeluruh untuk semua string class `dark:` pada elemen-elemen form, h2, p, dan button.
3. Edit `resources/views/profile/partials/update-password-form.blade.php`: Hapus semua utilitas `dark:`.
4. Edit `resources/views/profile/partials/delete-user-form.blade.php`: Hapus semua utilitas `dark:`.
