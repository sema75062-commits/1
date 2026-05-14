<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardController extends Controller
{
    public function __construct(
        private readonly CardRepositoryInterface $cardRepository
    ) {}

    // предустановленные карточки (только global_admin)

    /*
      GET /api/cards/default
      список всех предустановленных карточек
    */
    public function indexDefault()
    {
        return response()->json($this->cardRepository->allDefault());
    }

    /*
      GET /api/cards
      список всех карточек (предустановленных + кастомных)
    */
    public function index()
    {
        return response()->json($this->cardRepository->all());
    }

    /*
      POST /api/cards/default
      создать предустановленную карточку (только global_admin)
    */
    public function storeDefault(Request $request)
    {
        try {
            $data = $request->validate([
                'title'   => 'required|string|max:50',
                'picture' => 'required|image|mimes:jpeg,png|max:5120',
                'audio'   => 'required|file|mimes:mp3,wav|max:20480',
            ]);

            $picturePath = $request->file('picture')->store('cards/pictures', 'public');
            $audioPath   = $request->file('audio')->store('cards/audio', 'public');

            $card = $this->cardRepository->create([
                'id'           => Str::uuid()->toString(),
                'title'        => $data['title'],
                'picture_path' => $picturePath,
                'audio_path'   => $audioPath,
                'is_default'   => true,
            ]);

            return response()->json($card, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create default card: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      PUT /api/cards/default/{id}
      обновить предустановленную карточку (только global_admin)
    */
    public function updateDefault(Request $request, string $id)
    {
        try {
            $card = $this->cardRepository->find($id);

            if (!$card || !$card->is_default) {
                return response()->json(['message' => 'Default card not found.'], 404);
            }

            $data = $request->validate([
                'title'   => 'sometimes|string|max:50',
                'picture' => 'sometimes|image|mimes:jpeg,png|max:5120',
                'audio'   => 'sometimes|file|mimes:mp3,wav|max:20480',
            ]);

            $updateData = [];

            if (isset($data['title'])) {
                $updateData['title'] = $data['title'];
            }

            if ($request->hasFile('picture')) {
                Storage::disk('public')->delete($card->picture_path);
                $updateData['picture_path'] = $request->file('picture')->store('cards/pictures', 'public');
            }

            if ($request->hasFile('audio')) {
                Storage::disk('public')->delete($card->audio_path);
                $updateData['audio_path'] = $request->file('audio')->store('cards/audio', 'public');
            }

            $this->cardRepository->update($id, $updateData);

            return response()->json($this->cardRepository->find($id));
        } catch (\Exception $e) {
            Log::error('Failed to update default card: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      DELETE /api/cards/default/{id}
      удалить предустановленную карточку (только global_admin)
    */
    public function destroyDefault(string $id)
    {
        $card = $this->cardRepository->find($id);

        if (!$card || !$card->is_default) {
            return response()->json(['message' => 'Default card not found.'], 404);
        }

        Storage::disk('public')->delete([$card->picture_path, $card->audio_path]);
        $this->cardRepository->delete($id);

        return response()->json(['message' => 'Default card deleted.']);
    }

    // Кастомные карточки
    /*
      POST /api/cards
      создать кастомную карточку (родитель, педагог, center_admin, global_admin)
    */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title'   => 'required|string|max:50',
                'picture' => 'required|image|mimes:jpeg,png|max:5120',
                'audio'   => 'required|file|mimes:mp3,wav|max:20480',
            ]);

            $picturePath = $request->file('picture')->store('cards/pictures', 'public');
            $audioPath   = $request->file('audio')->store('cards/audio', 'public');

            $card = $this->cardRepository->create([
                'id'           => Str::uuid()->toString(),
                'title'        => $data['title'],
                'picture_path' => $picturePath,
                'audio_path'   => $audioPath,
                'is_default'   => false,
            ]);

            return response()->json($card, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create custom card: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      GET /api/cards/{id}
      получить карточку по ID
    */
    public function show(string $id)
    {
        $card = $this->cardRepository->find($id);

        if (!$card) {
            return response()->json(['message' => 'Card not found.'], 404);
        }

        return response()->json($card);
    }

    /*
      PUT /api/cards/{id}
      если карточка предустановленная, то создаётся кастомная копия
    */
    public function update(Request $request, string $id)
    {
        try {
            $card = $this->cardRepository->find($id);

            if (!$card) {
                return response()->json(['message' => 'Card not found.'], 404);
            }

            // предустановленная карточка, дублируем в кастомную
            if ($card->is_default) {
                $copy = $this->cardRepository->duplicateAsCustom($id);

                $data = $request->validate([
                    'title'   => 'sometimes|string|max:50',
                    'picture' => 'sometimes|image|mimes:jpeg,png|max:5120',
                    'audio'   => 'sometimes|file|mimes:mp3,wav|max:20480',
                ]);

                $updateData = [];

                if (isset($data['title'])) {
                    $updateData['title'] = $data['title'];
                }

                if ($request->hasFile('picture')) {
                    Storage::disk('public')->delete($copy->picture_path);
                    $updateData['picture_path'] = $request->file('picture')->store('cards/pictures', 'public');
                }

                if ($request->hasFile('audio')) {
                    Storage::disk('public')->delete($copy->audio_path);
                    $updateData['audio_path'] = $request->file('audio')->store('cards/audio', 'public');
                }

                if (!empty($updateData)) {
                    $this->cardRepository->update($copy->id, $updateData);
                }

                return response()->json([
                    'message' => 'Preset card was duplicated as a custom copy.',
                    'card'    => $this->cardRepository->find($copy->id),
                ], 201);
            }

            // кастомная карточка, обновляем напрямую
            $data = $request->validate([
                'title'   => 'sometimes|string|max:50',
                'picture' => 'sometimes|image|mimes:jpeg,png|max:5120',
                'audio'   => 'sometimes|file|mimes:mp3,wav|max:20480',
            ]);

            $updateData = [];

            if (isset($data['title'])) {
                $updateData['title'] = $data['title'];
            }

            if ($request->hasFile('picture')) {
                Storage::disk('public')->delete($card->picture_path);
                $updateData['picture_path'] = $request->file('picture')->store('cards/pictures', 'public');
            }

            if ($request->hasFile('audio')) {
                Storage::disk('public')->delete($card->audio_path);
                $updateData['audio_path'] = $request->file('audio')->store('cards/audio', 'public');
            }

            $this->cardRepository->update($id, $updateData);

            return response()->json($this->cardRepository->find($id));
        } catch (\Exception $e) {
            Log::error('Failed to update card: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /*
      DELETE /api/cards/{id}
      удалить кастомную карточку
    */
    public function destroy(string $id)
    {
        $card = $this->cardRepository->find($id);

        if (!$card) {
            return response()->json(['message' => 'Card not found.'], 404);
        }

        if ($card->is_default) {
            return response()->json(['message' => 'Cannot delete a preset card via this endpoint. Use /api/cards/default/{id}.'], 403);
        }

        Storage::disk('public')->delete([$card->picture_path, $card->audio_path]);
        $this->cardRepository->delete($id);

        return response()->json(['message' => 'Card deleted.']);
    }
}
