<?php

declare(strict_types=1);

namespace App\Orchid\Screens\TeacherAssignment;

use App\Orchid\Layouts\TeacherAssignment\TeacherAssignmentListLayout;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Orchid\Screen\Screen;

class TeacherAssignmentListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'teacher_assignments' => collect(),
        ];
    }

    public function name(): ?string
    {
        return __('Teacher assignments');
    }

    public function description(): ?string
    {
        return __('Which teacher works with which child — for scheduling and progress tracking.');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.domains.teacher_assignments',
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            TeacherAssignmentListLayout::class,
        ];
    }
}
