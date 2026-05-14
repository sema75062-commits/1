<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\TeacherAssignment;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TeacherAssignmentListLayout extends Table
{
    public $target = 'teacher_assignments';

    public function columns(): array
    {
        return [];
    }
}
