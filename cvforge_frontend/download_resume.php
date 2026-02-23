<?php
session_start();
include '../cvforge_api/db_connect.php';
require_once __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;

if (isset($_GET['resume_id'])) {
    $resume_id = $_GET['resume_id'];
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($user_id && $conn) {
        $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $resume_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resume = $result->fetch_assoc();
        
        if ($resume) {
           
            $_SESSION['current_header'] = json_decode($resume['header_data'], true) ?: [];
            $_SESSION['current_experiences'] = json_decode($resume['experience_data'], true) ?: [];
            $_SESSION['current_education'] = json_decode($resume['education_data'], true) ?: [];
            $_SESSION['current_skills'] = json_decode($resume['skills_data'], true) ?: [];
            $_SESSION['current_languages'] = json_decode($resume['languages_data'], true) ?: [];
            $_SESSION['current_summary'] = json_decode($resume['summary_data'], true) ?: [];
            
            
            $template_id = $resume['template_used'] ?? 1;
            $template_map = [
                1 => "templates/default_template.php",
                2 => "templates/creative_bold.php",
                3 => "templates/classic_elegance.php",
                4 => "templates/dark_edge.php",
                5 => "templates/creative.php",
                6 => "templates/standard.php"
            ];
            
            $selected_template_path = $template_map[$template_id] ?? $template_map[1];
            
            ob_start();
            include __DIR__ . '/' . $selected_template_path;
            $html = ob_get_clean();
            
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            
            $header = $_SESSION['current_header'][0] ?? [];
            $filename = 'Resume_' . preg_replace('/\s+/', '_', ($header['first_name'] ?? 'User')) . '.pdf';
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $dompdf->output();
            exit;
        }
        $stmt->close();
    }
}


header('Location: dashboard.php?error=download_failed');
exit;
?>