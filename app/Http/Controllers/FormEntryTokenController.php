<?php

namespace App\Http\Controllers;

use App\Directory;
use App\FormEntry;
use App\FormEntryToken as Token;
use App\FormEntryTokenStatus as TokenStatus;
use App\User;
use App\Http\Requests\FormEntryTokenRequest;

class FormEntryTokenController extends Controller
{
    public function search(
        FormEntryTokenRequest $request,
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

        return self::format(
            $user->formEntries()->where('form_id', $form->id)->get()
        );
    }

    public function open(
        FormEntryTokenRequest $request,
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

        if (!($user = User::findbyEmail($request->email))) {
            abort(400);
        }

        $openStatus = TokenStatus::findStatus('Open');

        $token = new Token();
        $token->form_entry_id = $formEntry->id;
        $token->user_id = $user->id;
        $token->form_entry_token_status_id = $openStatus->id;
        $token->value = str_random(20);
        $token->save();

        event(new FormEntryTokenCreated());

        return self::format([$formEntry])[0];
    }

    public function close(
        FormEntryTokenRequest $request,
        $directoryId,
        $formId,
        $formEntryId,
        $formEntrytokenId
    ) {
        $token = Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->entries()
            ->findOrFail($formEntryId);
        // resource_id ?????

        $closedStatus = TokenStatus::findStatus('Closed');

        $token->form_entry_token_status_id = $closedStatus->id;
        $token->update();

        return $token;
    }

    public static function format($formEntries)
    {
        $data = [];
        foreach($formEntries as $formEntry) {
            array_push($data, [
                'id'                 => $formEntry->id,
                'pagination_title'   => $formEntry->data['pagination_title'],
                'status'             => $formEntry->status,
                'has_open_token'     => FormEntry::hasOpenToken($formEntry),
                'has_locked_token'   => FormEntry::hasLockedToken($formEntry),
                'has_unclosed_token' => FormEntry::hasUnclosedToken($formEntry),
            ]);
        }
        return $data;
    }
}
