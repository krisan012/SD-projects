<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Project Creation', function () {
    it('allows authenticated users to create a project with basic information', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $projectData = [
            'title' => 'Test Project',
            'description' => 'A test project description',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/project', $projectData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Project created successfully!',
            ])
            ->assertJsonStructure([
                'message',
                'project' => [
                    'id',
                    'title',
                    'description',
                    'deadline',
                    'user_id',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Project',
            'description' => 'A test project description',
            'user_id' => $user->id,
        ]);
    });

    it('allows authenticated users to create a project with tasks', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $projectData = [
            'title' => 'Project with Tasks',
            'description' => 'A project with initial tasks',
            'deadline' => now()->addDays(14)->format('Y-m-d'),
            'tasks' => [
                [
                    'title' => 'First Task',
                    'status' => 'todo',
                    'due_date' => now()->addDays(3)->format('Y-m-d'),
                ],
                [
                    'title' => 'Second Task',
                    'status' => 'in_progress',
                    'due_date' => now()->addDays(5)->format('Y-m-d'),
                ],
            ],
        ];

        $response = $this->postJson('/api/project', $projectData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('projects', [
            'title' => 'Project with Tasks',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'First Task',
            'status' => 'todo',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Second Task',
            'status' => 'in_progress',
        ]);
    });

    it('validates required fields for project creation', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/project', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'deadline']);
    });

    it('validates deadline must be today or in the future', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $projectData = [
            'title' => 'Test Project',
            'deadline' => now()->subDays(1)->format('Y-m-d'), // Yesterday
        ];

        $response = $this->postJson('/api/project', $projectData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['deadline']);
    });
});

describe('Task Management', function () {
    it('allows adding tasks to existing projects via update', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $project = Project::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'tasks' => [
                [
                    'title' => 'New Task 1',
                    'status' => 'todo',
                    'due_date' => now()->addDays(2)->format('Y-m-d'),
                ],
                [
                    'title' => 'New Task 2',
                    'status' => 'in_progress',
                    'due_date' => now()->addDays(4)->format('Y-m-d'),
                ],
            ],
        ];

        $response = $this->postJson("/api/project/update/{$project->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project updated successfully']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task 1',
            'status' => 'todo',
            'project_id' => $project->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task 2',
            'status' => 'in_progress',
            'project_id' => $project->id,
        ]);
    });

    it('allows updating existing tasks', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Original Task',
            'status' => 'todo',
        ]);

        $updateData = [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'tasks' => [
                [
                    'id' => $task->id,
                    'title' => 'Updated Task Title',
                    'status' => 'done',
                    'due_date' => now()->addDays(1)->format('Y-m-d'),
                ],
            ],
        ];

        $response = $this->postJson("/api/project/update/{$project->id}", $updateData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Updated Task Title',
            'status' => 'done',
        ]);
    });

    it('allows deleting tasks', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $project = Project::factory()->create(['user_id' => $user->id]);
        $task1 = Task::factory()->create(['project_id' => $project->id]);
        $task2 = Task::factory()->create(['project_id' => $project->id]);

        $updateData = [
            'title' => $project->title,
            'description' => $project->description,
            'deadline' => $project->deadline,
            'tasks' => [
                [
                    'id' => $task1->id,
                    'title' => $task1->title,
                    'status' => $task1->status,
                ],
            ],
            'deletedTasks' => [$task2->id],
        ];

        $response = $this->postJson("/api/project/update/{$project->id}", $updateData);

        $response->assertStatus(200);
		
        $this->assertDatabaseMissing('tasks', ['id' => $task2->id]);
    });
});

describe('Authentication Protection', function () {
    it('prevents non-authenticated users from creating projects', function () {
        $projectData = [
            'title' => 'Unauthorized Project',
            'description' => 'Should not be created',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/project', $projectData);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('projects', [
            'title' => 'Unauthorized Project',
        ]);
    });

    it('prevents non-authenticated users from updating projects', function () {
        $project = Project::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->postJson("/api/project/update/{$project->id}", $updateData);

        $response->assertStatus(401);
    });

    it('prevents non-authenticated users from deleting projects', function () {
        $project = Project::factory()->create();

        $response = $this->postJson("/api/project/delete/{$project->id}");

        $response->assertStatus(401);
    });

    it('prevents users from accessing other users projects', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $otherUser = User::factory()->create();
        $otherUserProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $updateData = [
            'title' => 'Hacked Title',
            'description' => 'Hacked Description',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->postJson("/api/project/update/{$otherUserProject->id}", $updateData);

        // This should either be 403 (forbidden) or 404 (not found) depending on your authorization logic
        $response->assertStatus(403);
    });
});

describe('Project Assignment', function () {
    it('allows admin users to assign projects to other users', function () {
        // Create admin role first
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        
        // Create admin user with role
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin, 'sanctum');

        // Create target user and project
        $targetUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $admin->id]);

        $response = $this->postJson("/api/project/assign/{$project->id}", [
            'user_id' => $targetUser->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Project assigned successfully.',
            ])
            ->assertJsonStructure([
                'message',
                'project' => [
                    'id',
                    'user_id',
                    'user' => ['id', 'name', 'email'],
                ]
            ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $targetUser->id,
        ]);
    });

    it('prevents non-admin users from assigning projects', function () {
        $userRole = \App\Models\Role::firstOrCreate(['name' => 'user'], ['display_name' => 'User']);
        
        $user = User::factory()->create(['role_id' => $userRole->id]);
        $this->actingAs($user, 'sanctum');

        $targetUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/project/assign/{$project->id}", [
            'user_id' => $targetUser->id,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $user->id,
        ]);
    });

    it('validates required user_id for assignment', function () {
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin, 'sanctum');

        $project = Project::factory()->create(['user_id' => $admin->id]);

        $response = $this->postJson("/api/project/assign/{$project->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    });

    it('validates user_id exists in database', function () {
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $this->actingAs($admin, 'sanctum');

        $project = Project::factory()->create(['user_id' => $admin->id]);

        $response = $this->postJson("/api/project/assign/{$project->id}", [
            'user_id' => 99999, // Non-existent user ID
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    });

    it('prevents non-authenticated users from assigning projects', function () {
        $project = Project::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->postJson("/api/project/assign/{$project->id}", [
            'user_id' => $targetUser->id,
        ]);

        $response->assertStatus(401);
    });
});

describe('Project Deletion', function () {
    it('allows authenticated users to delete their own projects', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson("/api/project/delete/{$project->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    });

    it('prevents users from deleting other users projects', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $otherUser = User::factory()->create();
        $otherUserProject = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->postJson("/api/project/delete/{$otherUserProject->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('projects', [
            'id' => $otherUserProject->id,
        ]);
    });
});