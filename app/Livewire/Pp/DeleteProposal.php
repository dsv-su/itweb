<?php

namespace App\Livewire\Pp;

use App\Mail\ProposalDeleted;
use App\Models\ProjectProposal;
use App\Services\Settings\ProposalsDirectory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteProposal extends Component
{
    #[Locked]
    public string $proposalId;

    public function delete(): void
    {
        DB::transaction(function () {
            $proposal = ProjectProposal::query()->lockForUpdate()->findOrFail($this->proposalId);
            $dashboard = $proposal->dashboard()->lockForUpdate()->first();
            $proposal->setRelation('dashboard', $dashboard);
            abort_unless($proposal->allowDelete(), 403, 'This proposal is not eligible for deletion.');

            $user = $proposal->submitter()->firstOrFail();
            $mail = new ProposalDeleted($user->name, $proposal->name, $proposal->id, $proposal->created_at->format('Y-m-d'));
            $recipient = $user->email;
            $id = $proposal->id;

            $dashboard?->delete();
            $proposal->delete();

            DB::afterCommit(function () use ($mail, $recipient, $id) {
                Storage::deleteDirectory(ProposalsDirectory::MAIN.$id);
                Mail::to($recipient)->send($mail);
            });
        });

        $this->dispatch('pp-update');
    }

    public function render()
    {
        return view('livewire.pp.delete-proposal');
    }
}
