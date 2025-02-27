<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reports;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * Test la création d'un rapport sur un post.
     */
    public function test_can_create_user_report(): void
    {
        // Crée un utilisateur pour le test
        $user = User::factory()->create();
        
        // Crée un post à signaler
        $reportedUSer = User::factory()->create();
        
        // Données du rapport
        $reportData = [
            'user_id' => $reportedUSer->id,
            'reported_entity_type' => 'App\\Models\\User',
            'report_content' => $this->faker->paragraph,
        ];
        
        // Crée le rapport
        $report = Reports::create($reportData);
        
        // Vérifie que le rapport a été créé dans la base de données
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'user_id' => $reportedUSer->id,
            'reported_entity_type' => 'App\\Models\\User',
        ]);
    }
    
    /**
     * Test la récupération des rapports d'un utilisateur.
     */
    public function test_can_get_user_reports(): void
    {
        // Crée un utilisateur pour le test
        $user = User::factory()->create();
        // Crée plusieurs rapports pour cet utilisateur
        Reports::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);
        
        // Vérifie que l'utilisateur a bien 3 rapports
        $this->assertEquals(3, $user->reports()->count());
    }
    
    /**
     * Test la récupération des rapports pour une entité spécifique.
     */
    public function test_can_get_entity_reports(): void 
    {
        // Create a user that will be reported
        $reported_user = User::factory()->create();
        
        // Create multiple users who will report the user
        $reporter_users = User::factory()->count(3)->create();
        
        // Each user creates a report on the reported user
        foreach ($reporter_users as $user) {
            Reports::factory()->create([
                'user_id' => $user->id,
                'reported_entity_id' => $reported_user->id,
                'reported_entity_type' => 'App\\Models\\User',
            ]);
        }
        
        // Check that the reported user has 3 reports
        $reports = Reports::where('reported_entity_id', $reported_user->id)
            ->where('reported_entity_type', 'App\\Models\\User')
            ->get();
            
        $this->assertEquals(3, $reports->count());
    }
    
    /**
     * Test la suppression d'un rapport.
     */
    public function test_can_delete_report(): void
    {
        // Crée un rapport
        $report = Reports::factory()->create();
        
        // Supprime le rapport
        $report->delete();
        
        // Vérifie que le rapport a été supprimé
        $this->assertDatabaseMissing('reports', [
            'id' => $report->id,
        ]);
    }
    
    /**
     * Test la suppression des rapports à la suppression de l'utilisateur.
     */
    public function test_reports_deleted_when_user_deleted(): void
    {
        // Crée un utilisateur pour le test
        $user = User::factory()->create();
        
        // Crée un rapport pour cet utilisateur
        $report = Reports::factory()->create([
            'user_id' => $user->id,
        ]);
        
        // Récupère l'ID pour vérification après suppression
        $reportId = $report->id;
        
        // Supprime l'utilisateur
        $user->delete();
        
        // Vérifie que le rapport a également été supprimé (grâce à onDelete('cascade'))
        $this->assertDatabaseMissing('reports', [
            'id' => $reportId,
        ]);
    }
    
    /**
     * Test les rapports avec une entité nulle.
     */
    public function test_can_create_general_report(): void
    {
        // Crée un utilisateur pour le test
        $user = User::factory()->create();
        
        // Crée un rapport général (sans entité spécifique)
        $reportData = [
            'user_id' => $user->id,
            'reported_entity_id' => null,
            'reported_entity_type' => null,
            'report_content' => 'Signalement général du site',
        ];
        
        // Crée le rapport
        $report = Reports::create($reportData);
        
        // Vérifie que le rapport a été créé dans la base de données
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'reported_entity_id' => null,
            'reported_entity_type' => null,
            'report_content' => 'Signalement général du site',
        ]);
    }
}