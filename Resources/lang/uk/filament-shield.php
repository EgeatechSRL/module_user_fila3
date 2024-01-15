<?php

declare(strict_types=1);

return [
    /*
    |------------------------------------------------- -------------------------
    | Table Columns
    |------------------------------------------------- -------------------------
    */

    'column.name' => 'Ім\'я',
    'column.guard_name' => 'Ім\'я гварда',
    'column.roles' => 'Ролі',
    'column.permissions' => 'Дозволи',
    'column.updated_at' => 'Оновлено',

    /*
    |------------------------------------------------- -------------------------
    | Form Fields
    |------------------------------------------------- -------------------------
    */

    'field.name' => 'Ім\'я',
    'field.guard_name' => 'Ім\'я гварда',
    'field.permissions' => 'Дозволи',
    'field.select_all.name' => 'Вибрати все',
    'field.select_all.message' => 'Включити всі дозволи, які <span class="text-primary font-medium">Доступні</span> для цієї ролі',

    /*
    |------------------------------------------------- -------------------------
    | Navigation & Resource
    |------------------------------------------------- -------------------------
    */

    'nav.group' => 'Filament Shield',
    'nav.role.label' => 'Ролі',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Роль',
    'resource.label.roles' => 'Ролі',

    /*
    |------------------------------------------------- -------------------------
    | Section & Tabs
    |------------------------------------------------- -------------------------
    */

    'section' => 'Сутності',
    'resources' => 'Ресурси',
    'widgets' => 'Віджети',
    'pages' => 'Сторінки',
    'custom' => 'Користувальницькі дозволи',

    /*
    |------------------------------------------------- -------------------------
    | Messages
    |------------------------------------------------- -------------------------
    */

    'forbidden' => 'У вас немає доступу',

    /*
    |------------------------------------------------- -------------------------
    | Resource Permissions' Labels
    |------------------------------------------------- -------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Перегляд',
        'view_any' => 'Може дивитися будь-яке',
        'create' => 'Створення',
        'update' => 'Оновлення',
        'delete' => 'Видалення',
        'delete_any' => 'Може видалити будь-який',
        'force_delete' => 'Примусово видалити',
        'force_delete_any' => 'Може примусово видалити будь-який',
        'restore' => 'Відновлення',
        'reorder' => 'Зміна порядку',
        'restore_any' => 'Може відновити будь-який',
        'replicate' => 'Копіювати',
    ],
];