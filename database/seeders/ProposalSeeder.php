<?php

namespace Database\Seeders;

use App\Models\Dashboard;
use App\Models\ProjectProposal;
use App\Models\User;
use App\Workflows\DSVProjectPWorkflow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Workflow\WorkflowStub;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Generate
        // '9e4d9745-5809-4cbc-803f-8768221f1297' - dev
        // '9e25a704-50e2-41b9-9133-742dc24b3cef' - test
        // '9b618981-74c2-45b0-9819-49f5d9bc206e' - prod

        $userId = $foUserId =  $viceId = '9e25a704-50e2-41b9-9133-742dc24b3cef';
        $unit_head = ['9e25a704-50e2-41b9-9133-742dc24b3cef'];



        for ($i = 1; $i < 10; $i++) {
            $timestamp = now()->startOfDay()->timestamp;

            // Create a new proposal directly
            $pp = new ProjectProposal();
            $pp->user_id = $userId;
            $pp->name = $name = $i . ' ' .$faker->sentence(4);
            $pp->created = $timestamp;
            $pp->status_stage1 = 'pending';
            $pp->status_stage2 = 'pending';
            $pp->status_stage3 = 'submitted';
            $json = '{
                      "Proposal.pdf": {
                        "path":"proposals/9f25b0c4-4685-488d-90fa-5ed572f72770/draft/53bymbb7MAkyEO7HVRC8VbKjyqRYU5zCd7yT6T4A.pdf",
                        "tmp":"f32qNekWqmJCznUjC0z1iv9xcjs21w-metaTWFudWFsX2ludGVybndlYmJlbi5wZGY=-.pdf",
                        "size":488,
                        "date":"2025-06-25",
                        "type":"draft",
                        "review":"pending",
                        "uploader":"Admin User"
                      },
                      "budget.xlsx": {
                        "path":"proposals/9f25b0c4-4685-488d-90fa-5ed572f72770/budget/2JinNo2iaCG8mrSVwS3Iky5R1Cm4SV2kn99XBAtt.xlsx",
                        "tmp":"p56yqLRR3G92DPFAonOeoMQ9XzO8za-metaRFNDMDE2MzEuanBn-.jpg",
                        "size":2224,
                        "date":"2025-06-14",
                        "type":"budget",
                        "review":"pending",
                        "uploader":"Admin User"
                      }
                    }';
            // Decode into an associative array
            $data = json_decode($json, true);

            if ($i % 2 === 1) {
                $pp->files = $data;
            } else{
                $pp->files = [];
            }
            //$pp->files = [];
            $pp->pp = [
                'title' => $name,
                'objective' => $faker->paragraph(),
                'principal_investigator' => $faker->name(),
                'principal_investigator_email' => $faker->safeEmail(),
                'co_investigator_name' => [$faker->name()],
                'co_investigator_email' => [$faker->safeEmail()],
                //'research_area' => 'Business Process Management and Enterprise Modeling',
                'research_area' => 'Cybersecurity',
                'dsvcoordinating' => 'yes',
                'other_coordination' => $faker->word(),
                'eu' => 'no',
                'eu_wallenberg' => 'no',
                'funding_organization' => 'Vinnova',
                'cofinancing' => $faker->boolean(),
                'other_cofinancing' => $faker->word(),
                'project_duration' => $faker->numberBetween(1, 5),
                'unit_head' => $unit_head,
                'program' => $faker->word(),
                'decision_exp' => '2025-06-25',
                'start_date' => '2025-07-01',
                'submission_deadline' => '2025-05-01',
                //'budget_project' => $faker->randomFloat(0, 10000, 500000),
                'budget_project' => 1000,
                //'budget_dsv' => $faker->randomFloat(0, 5000, 100000),
                'budget_dsv' => 100,
                'budget_phd' => 1,
                'currency' => 'usd',
                'oh_cost' => 10,
                'cofinancing_needed' => 500,
                'user_comments' => $faker->sentence(),
                'submitted' => $timestamp,
                'status' => 'pending',
            ];

            $pp->save();

            // Create corresponding dashboard entry
            $dashboardData = [
                'request_id' => $pp->id,
                'name' => $pp->name,
                'created' => $timestamp,
                'status' => 'unread',
                'type' => 'projectproposal',
                'user_id' => $userId,
                'fo_id' => $foUserId,
                'vice_id' => $viceId,
            ];

            $dashboard = Dashboard::updateOrCreate(['request_id' => $pp->id], $dashboardData);
            // Create unit head approved array

            $dashboard->unit_heads = $unit_head;
            $unit_head_approved = [];
            foreach ($unit_head as $uh) {
                $unit_head_approved[$uh] = 0;
            }
            // Encode associative array to JSON
            $dashboard->unit_head_approved = json_encode($unit_head_approved);
            $dashboard->save();
            if (count($unit_head) > 1) {
                //Flag multiple
                $dashboard->multiple_heads = true;
                $dashboard->save();
            }
            $workflow = WorkflowStub::make(DSVProjectPWorkflow::class);
            $dashboard->workflow_id = $workflow->id();
            $dashboard->save();
            $workflow->start($dashboard);
            $workflow->submit();
        }
    }
}
