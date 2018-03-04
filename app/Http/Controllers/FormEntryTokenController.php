<?php

namespace App\Http\Controllers;

use App\Directory;
use App\FormEntry;
use App\FormEntryToken as Token;
use App\FormEntryTokenStatus as TokenStatus;
use App\User;
use App\Http\Requests\FormEntryTokenCloseRequest;
use App\Http\Requests\FormEntryTokenIndexRequest;
use App\Http\Requests\FormEntryTokenOpenRequest;
use App\Http\Requests\FormEntryTokenSearchRequest;

class FormEntryTokenController extends Controller
{
    public static $withRelationships = [
        'beforeUpdateFormEntry',
        'afterUpdateFormEntry',
        'user',
        'status'
    ];

    public function search(
        FormEntryTokenSearchRequest $request,
        $directoryId,
        $formId
    ) {
        $form = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId);

        // If user not found, return empty object.
        if (!($user = User::findByEmail($request->email))) {
            return $this->pageOrGet(FormEntry::where('id', 0));
        }

        $formEntries = $user
            ->formEntries()
            ->published()
            ->where('form_id', $form->id)
            ->get();

        return self::format($formEntries);
    }

    public function index(
        FormEntryTokenIndexRequest $request,
        $directoryId,
        $formId,
        $formEntryId
    ) {
        $formEntry = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId);

        $tokens = $formEntry
            ->tokens()
            ->with(self::$withRelationships);

        if ($request->orderByDesc) {
            $tokens->orderBy($request->orderByDesc, 'desc');
        }

        return $this->pageOrGet($tokens);
    }

    public function open(
        FormEntryTokenOpenRequest $request,
        $directoryId,
        $formId,
        $formEntryId
    ) {
        $formEntry = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId);

        // Get user either via "email" in request or logged-in user.
        $user = User::findbyEmail($request->email) ?: $request->user();

        $token = Token::openToken($formEntry, $user);

        $data = self::format([$formEntry])[0];
        if ($request->user()) {
            $data['wp_edit_url'] = $token->wp_edit_url;
        }
        return $data;
    }

    public function close(
        FormEntryTokenCloseRequest $request,
        $directoryId,
        $formId,
        $formEntryId,
        $formEntryTokenId
    ) {
        $token = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->formEntries()
            ->findOrFail($formEntryId)
            ->tokens()
            ->findOrFail($formEntryTokenId);

        $token = Token::closeToken($token);
        
        return Token::with(self::$withRelationships)->find($token->id);
    }

    private static function format($formEntries)
    {
        $data = [];

        foreach($formEntries as $formEntry) {
            array_push($data, [
                'id'                 => $formEntry->id,
                'pagination_title'   => $formEntry->data['pagination_title'],
                'status'             => $formEntry->status->name,
                'has_open_token'     => $formEntry->has_open_token,
                'has_locked_token'   => $formEntry->has_locked_token,
                'has_unclosed_token' => $formEntry->has_unclosed_token
            ]);
        }
        
        return $data;
    }
}
