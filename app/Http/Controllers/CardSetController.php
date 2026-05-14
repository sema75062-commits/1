<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CardSetRepositoryInterface;
use App\Repositories\Interfaces\ChildRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CardSetController extends Controller
{
    public function __construct(
        private readonly CardSetRepositoryInterface $cardSetRepository,
        private readonly ChildRepositoryInterface   $childRepository,
    ) {}

    /*
      GET /api/card-sets
      список всех наборов
    */
    public function index()
    {
        return response()->json($this->cardSetRepository->all());
    }

    /*
      GET /api/card-sets/{id}
      получить набор с карточками
    */
    public function show(string $id)
    {
        $cardSet = $this->cardSetRepository->find($id);

        if (!$cardSet) {
            return response()->json(['message' => 'Card set not found.'], 404);
        }

        $cardSet->load('cards');

        return response()->json($cardSet);
    }

    /*
      POST /api/card-sets
      создать набор (center_admin, global_admin)
    */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:100',
            ]);

            $cardSet = $this->cardSetRepository->create([
                'id'    => Str::uuid()->toString(),
                'title' => $data['title'],
            ]);

            return response()->json($cardSet, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create card set: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      PUT /api/card-sets/{id}
      обновить набор (center_admin, global_admin)
    */
    public function update(Request $request, string $id)
    {
        try {
            $cardSet = $this->cardSetRepository->find($id);

            if (!$cardSet) {
                return response()->json(['message' => 'Card set not found.'], 404);
            }

            $data = $request->validate([
                'title' => 'required|string|max:100',
            ]);

            $this->cardSetRepository->update($id, ['title' => $data['title']]);

            return response()->json($this->cardSetRepository->find($id));
        } catch (\Exception $e) {
            Log::error('Failed to update card set: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      DELETE /api/card-sets/{id}
      удалить набор (center_admin, global_admin)
    */
    public function destroy(string $id)
    {
        $cardSet = $this->cardSetRepository->find($id);

        if (!$cardSet) {
            return response()->json(['message' => 'Card set not found.'], 404);
        }

        $this->cardSetRepository->delete($id);

        return response()->json(['message' => 'Card set deleted.']);
    }

    /*
      POST /api/card-sets/{id}/cards
      добавить карточку в набор (center_admin, global_admin)
      Body: { "card_id": "uuid" }
    */
    public function addCard(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'card_id' => 'required|uuid|exists:cards,id',
            ]);

            $this->cardSetRepository->addCard($id, $data['card_id']);

            return response()->json(['message' => 'Card added to set.']);
        } catch (\Exception $e) {
            Log::error('Failed to add card to set: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      DELETE /api/card-sets/{id}/cards/{cardId}
      убрать карточку из набора (center_admin, global_admin)
    */
    public function removeCard(string $id, string $cardId)
    {
        try {
            $this->cardSetRepository->removeCard($id, $cardId);

            return response()->json(['message' => 'Card removed from set.']);
        } catch (\Exception $e) {
            Log::error('Failed to remove card from set: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      POST /api/card-sets/{id}/assign
      назначить набор ребёнку
     
      center_admin — может назначать детям своего центра
      global_admin — может назначать любому ребёнку
      user/teacher — может назначать только своим детям (через teachers_has_children или family_accounts_has_children)
     
      Body: { "child_id": "uuid" }
    */
    public function assignToChild(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'child_id' => 'required|uuid|exists:children,id',
            ]);

            $user     = $request->user();
            $userRole = $user->role?->title;
            $childId  = $data['child_id'];

            // Проверка прав доступа к ребёнку
            if (!in_array($userRole, ['global_admin', 'center_admin'], true)) {
                $hasAccess = $user->children()->where('children.id', $childId)->exists();

                if (!$hasAccess) {
                    return response()->json(['message' => 'You do not have access to this child.'], 403);
                }
            }

            if ($userRole === 'center_admin') {
                $center = $user->managedCenter;

                if (!$center) {
                    return response()->json(['message' => 'You are not managing any center.'], 403);
                }

                $childInCenter = $center->children()->where('children.id', $childId)->exists();

                if (!$childInCenter) {
                    return response()->json(['message' => 'This child does not belong to your center.'], 403);
                }
            }

            $this->cardSetRepository->assignToChild($id, $childId);

            return response()->json(['message' => 'Card set assigned to child.']);
        } catch (\Exception $e) {
            Log::error('Failed to assign card set to child: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      DELETE /api/card-sets/{id}/assign/{childId}
      снять набор с ребёнка
    */
    public function detachFromChild(Request $request, string $id, string $childId)
    {
        try {
            $user     = $request->user();
            $userRole = $user->role?->title;

            if (!in_array($userRole, ['global_admin', 'center_admin'], true)) {
                $hasAccess = $user->children()->where('children.id', $childId)->exists();

                if (!$hasAccess) {
                    return response()->json(['message' => 'You do not have access to this child.'], 403);
                }
            }

            $this->cardSetRepository->detachFromChild($id, $childId);

            return response()->json(['message' => 'Card set detached from child.']);
        } catch (\Exception $e) {
            Log::error('Failed to detach card set from child: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
