<?php

namespace App\Livewire\Pp\Fo;

use App\Mail\NotifyAssignedFO;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Workflows\Partials\RequestStates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Assign extends Component
{
    public ProjectProposal $proposal;

    public bool $switchingFo = false;

    public string $selectedFoId = '';

    public string $foStatus = '';

    private function canSwitchFo(): bool
    {
        return auth()->check() && DB::table('group_user')
            ->where('group_id', 'ekonomi')->where('user_id', auth()->id())->exists();
    }

    public function switchFo(): void
    {
        abort_unless($this->canSwitchFo(), 403);
        $this->resetValidation();
        $this->foStatus = '';
        $this->selectedFoId = (string) $this->proposal->dashboard()->firstOrFail()->fo_id;
        $this->switchingFo = true;
    }

    public function cancelFoSwitch(): void
    {
        $this->reset('switchingFo', 'selectedFoId');
        $this->resetValidation();
    }

    public function saveFo(): void
    {
        abort_unless($this->canSwitchFo(), 403);
        $this->validate([
            'selectedFoId' => [
                'required', 'string', 'exists:users,id',
                Rule::exists('group_user', 'user_id')->where('group_id', 'ekonomi'),
            ],
        ], [], ['selectedFoId' => __('Financial officer')]);

        $dashboard = DB::transaction(function () {
            $dashboard = $this->proposal->dashboard()->lockForUpdate()->firstOrFail();
            if ((string) $dashboard->fo_id === $this->selectedFoId) {
                return null;
            }
            $dashboard->update(['fo_id' => $this->selectedFoId]);

            return $dashboard;
        });

        if ($dashboard && (string) $dashboard->state === RequestStates::HEAD_APPROVED) {
            $assignedFO = User::findOrFail($dashboard->fo_id);
            Mail::to($assignedFO->email)->send(new NotifyAssignedFO($assignedFO, $dashboard));
        }

        $this->cancelFoSwitch();
        $this->foStatus = $dashboard ? __('Financial officer updated.') : __('Financial officer unchanged.');
    }

    public function render()
    {
        $dashboard = $this->proposal->dashboard()->with('financialOfficer')->first();

        return view('livewire.pp.fo.assign', [
            'assignedName' => $dashboard?->financialOfficer?->name ?? __('Unassigned'),
            'canSwitchFo' => $dashboard !== null && $this->canSwitchFo(),
            'financialOfficers' => $this->switchingFo && $this->canSwitchFo()
                ? User::whereIn('id', DB::table('group_user')->where('group_id', 'ekonomi')->select('user_id'))
                    ->orderBy('name')->get()
                : collect(),
        ]);
    }
}
